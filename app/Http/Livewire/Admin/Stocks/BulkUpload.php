<?php

namespace App\Http\Livewire\Admin\Stocks;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Stock;
use App\Imports\StockImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BulkUpload extends Component
{
    use WithFileUploads;

    public $uploadFile;
    public $uploadProgress = 0;
    public $isUploading = false;
    public $successMessage = '';
    public $errorMessage = '';
    public $warningMessage = '';
    public $importedCount = 0;
    public $skippedCount = 0;
    public $errorCount = 0;
    public $previewData = [];
    public $showPreview = false;
    public $fileInfo = [];

    protected $listeners = ['resetUpload'];

    protected $rules = [
        'uploadFile' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240', // 10MB max
    ];

    protected $messages = [
        'uploadFile.required' => 'Please select a file to upload.',
        'uploadFile.mimes' => 'File must be a CSV or Excel file (.csv, .txt, .xlsx, .xls).',
        'uploadFile.max' => 'File size must not exceed 10MB.',
    ];

    public function updatedUploadFile()
    {
        $this->validate();
        $this->previewFile();
    }

    public function previewFile()
    {
        if (!$this->uploadFile) {
            return;
        }

        try {
            $this->isUploading = true;
            $this->uploadProgress = 25;

            $extension = $this->uploadFile->getClientOriginalExtension();
            $this->fileInfo = [
                'name' => $this->uploadFile->getClientOriginalName(),
                'size' => $this->formatFileSize($this->uploadFile->getSize()),
                'type' => strtoupper($extension)
            ];

            $this->uploadProgress = 50;

            // Read file for preview
            if (in_array($extension, ['xlsx', 'xls'])) {
                $this->previewData = $this->previewExcelFile();
            } else {
                $this->previewData = $this->previewCsvFile();
            }

            $this->uploadProgress = 75;
            $this->showPreview = true;
            $this->uploadProgress = 100;

        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to preview file: ' . $e->getMessage();
            Log::error('File preview error', ['error' => $e->getMessage()]);
        } finally {
            $this->isUploading = false;
            $this->uploadProgress = 0;
        }
    }

    public function import()
    {
        $this->validate();
        
        try {
            $this->isUploading = true;
            $this->uploadProgress = 10;
            $this->resetMessages();

            $extension = $this->uploadFile->getClientOriginalExtension();
            
            $this->uploadProgress = 25;

            if (in_array($extension, ['xlsx', 'xls'])) {
                $result = $this->importExcelFile();
            } else {
                $result = $this->importCsvFile();
            }

            $this->uploadProgress = 90;

            $this->importedCount = $result['imported'];
            $this->skippedCount = $result['skipped'];
            $this->errorCount = $result['errors'];

            // Set appropriate messages
            if ($this->importedCount > 0) {
                $this->successMessage = "Successfully imported {$this->importedCount} products!";
            }

            if ($this->skippedCount > 0) {
                $this->warningMessage = "Skipped {$this->skippedCount} empty or invalid rows.";
            }

            if ($this->errorCount > 0) {
                $this->errorMessage = "Failed to import {$this->errorCount} rows. Check the file format and try again.";
            }

            if ($this->importedCount === 0 && $this->errorCount === 0) {
                $this->warningMessage = 'No valid data found in the file.';
            }

            $this->uploadProgress = 100;

            // Reset upload
            $this->uploadFile = null;
            $this->showPreview = false;
            $this->previewData = [];

            // Emit event to refresh parent component
            $this->emit('stocksUpdated');

        } catch (\Exception $e) {
            $this->errorMessage = 'Import failed: ' . $e->getMessage();
            Log::error('Import error', ['error' => $e->getMessage()]);
        } finally {
            $this->isUploading = false;
            $this->uploadProgress = 0;
        }
    }

    private function importExcelFile()
    {
        $import = new StockImport();
        Excel::import($import, $this->uploadFile->getRealPath());

        $failures = $import->failures();
        $errors = $import->getErrors();

        return [
            'imported' => $import->getImportedCount(),
            'skipped' => 0, // Excel import handles this differently
            'errors' => count($failures) + count($errors)
        ];
    }

    private function importCsvFile()
    {
        $path = $this->uploadFile->getRealPath();
        $content = file_get_contents($path);
        
        if ($content === false || trim($content) === '') {
            throw new \Exception('Invalid or empty CSV file');
        }

        // Strip UTF-8 BOM if present
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }
        $content = preg_replace('/^\x{FEFF}/u', '', $content);

        // Auto-detect delimiter
        $firstLine = strtok($content, "\r\n");
        $delimiter = ',';
        if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
            $delimiter = ';';
        }

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        $rawHeader = fgetcsv($stream, 0, $delimiter, '"');
        if (!$rawHeader) {
            fclose($stream);
            throw new \Exception('Invalid CSV header');
        }

        $header = array_map(function($h) {
            $clean = preg_replace('/^[\xEF\xBB\xBF\x{FEFF}\x{200B}]+/u', '', trim((string)$h));
            $clean = strtolower(trim(str_replace([' ', '-', '/'], '_', $clean)));
            $aliases = [
                'item_name' => ['item_name', 'name', 'product_name', 'product', 'item'],
                'category' => ['category', 'cat', 'category_name'],
                'description' => ['description', 'desc', 'packing', 'packaging', 'unit'],
                'meta_title' => ['meta_title', 'seo_title', 'title_tag'],
                'meta_description' => ['meta_description', 'seo_description', 'meta_desc'],
                'meta_keywords' => ['meta_keywords', 'keywords', 'seo_keywords', 'tags'],
                'quantity' => ['quantity', 'qty', 'stock'],
                'price' => ['price', 'rate', 'unit_price', 'selling_price'],
                'original_price' => ['original_price', 'mrp', 'orig_price'],
                'discount_percentage' => ['discount_percentage', 'discount', 'discount_%', 'disc_%'],
                'special_discount_percentage' => ['special_discount_percentage', 'special_discount', 'special_%'],
                'is_active' => ['is_active', 'active', 'status'],
                'show_on_shop' => ['show_on_shop', 'show_shop'],
                'is_popular' => ['is_popular', 'popular'],
                'is_latest' => ['is_latest', 'latest'],
                'youtube_url' => ['youtube_url', 'youtube', 'video_url', 'video'],
                'image' => ['image', 'image_url']
            ];
            foreach ($aliases as $standard => $list) {
                if (in_array($clean, $list)) return $standard;
            }
            return $clean;
        }, $rawHeader);

        $imported = 0;
        $skipped = 0;
        $errors = 0;

        while (($row = fgetcsv($stream, 0, $delimiter, '"')) !== false) {
            try {
                if (empty(array_filter($row, fn($v) => trim($v) !== ''))) {
                    $skipped++;
                    continue;
                }

                $headerCount = count($header);
                $rowCount = count($row);
                if ($rowCount < $headerCount) {
                    $row = array_pad($row, $headerCount, '');
                } elseif ($rowCount > $headerCount) {
                    $row = array_slice($row, 0, $headerCount);
                }

                $rowData = array_combine($header, $row);
                
                if (empty(trim($rowData['item_name'] ?? '')) || empty(trim($rowData['category'] ?? ''))) {
                    $skipped++;
                    continue;
                }

                Stock::create($this->processRowData($rowData));
                $imported++;

            } catch (\Exception $e) {
                $errors++;
                Log::error('CSV row import error', ['row' => $row ?? [], 'error' => $e->getMessage()]);
            }
        }
        fclose($stream);

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors
        ];
    }

    private function previewExcelFile()
    {
        try {
            $data = Excel::toArray(new StockImport(), $this->uploadFile->getRealPath());
            $firstSheet = $data[0] ?? [];
            
            return array_slice($firstSheet, 0, 5); // First 5 rows for preview
        } catch (\Exception $e) {
            Log::error('Excel preview error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    private function previewCsvFile()
    {
        $data = [];
        $path = $this->uploadFile->getRealPath();
        $content = file_get_contents($path);
        if ($content === false) return [];

        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        $count = 0;
        while (($row = fgetcsv($stream, 0, ',', '"')) !== false && $count < 6) {
            $data[] = $row;
            $count++;
        }
        fclose($stream);
        
        return $data;
    }

    private function processRowData($rowData)
    {
        $rawCategory = trim((string)($rowData['category'] ?? ''));
        $categoryId = null;
        $categoryName = $rawCategory;

        if (!empty($rawCategory)) {
            $catModel = \App\Models\Category::findOrCreateByName($rawCategory);
            if ($catModel) {
                $categoryId = $catModel->id;
                $categoryName = $catModel->name;
            }
        }

        $price = $this->parseNumeric($rowData['price'] ?? null, 'float');
        $originalPrice = $this->parseNumeric($rowData['original_price'] ?? null, 'float');
        $discount = $this->parseNumeric($rowData['discount_percentage'] ?? null, 'int');
        $specialDiscount = $this->parseNumeric($rowData['special_discount_percentage'] ?? null, 'int');

        if (($price === null || $price <= 0) && $originalPrice !== null && $originalPrice > 0) {
            $calc = $originalPrice;
            if ($discount && $discount > 0) {
                $calc = $calc * (1 - ($discount / 100));
            }
            if ($specialDiscount && $specialDiscount > 0) {
                $calc = $calc * (1 - ($specialDiscount / 100));
            }
            $price = round($calc, 2);
        }

        if (($originalPrice === null || $originalPrice <= 0) && $price !== null && $price > 0) {
            if ($discount && $discount > 0) {
                $originalPrice = round($price / (1 - ($discount / 100)), 2);
            } else {
                $originalPrice = $price;
            }
        }

        $data = [
            'item_name' => trim((string)($rowData['item_name'] ?? '')),
            'category' => $categoryName,
            'category_id' => $categoryId,
            'description' => trim((string)($rowData['description'] ?? '')),
            'meta_title' => !empty($rowData['meta_title']) ? trim((string)$rowData['meta_title']) : null,
            'meta_description' => !empty($rowData['meta_description']) ? trim((string)$rowData['meta_description']) : null,
            'meta_keywords' => !empty($rowData['meta_keywords']) ? trim((string)$rowData['meta_keywords']) : null,
            'quantity' => $this->parseNumeric($rowData['quantity'] ?? 0, 'int') ?? 0,
            'price' => $price ?? 0,
            'original_price' => $originalPrice,
            'discount_percentage' => $discount,
            'special_discount_percentage' => $specialDiscount,
            'is_active' => $this->parseBoolean($rowData['is_active'] ?? 1),
            'show_on_shop' => $this->parseBoolean($rowData['show_on_shop'] ?? 1),
            'is_popular' => $this->parseBoolean($rowData['is_popular'] ?? 0),
            'is_latest' => $this->parseBoolean($rowData['is_latest'] ?? 0),
            'expires_at' => $this->parseDateTime($rowData['expires_at'] ?? null),
            'ordered_count' => $this->parseNumeric($rowData['ordered_count'] ?? 0, 'int') ?? 0,
            'last_released_at' => $this->parseDateTime($rowData['last_released_at'] ?? null) ?: now(),
            'next_release_at' => $this->parseDateTime($rowData['next_release_at'] ?? null) ?: now()->addMinutes(10),
            'youtube_url' => trim((string)($rowData['youtube_url'] ?? '')),
            'image' => trim((string)($rowData['image'] ?? ''))
        ];

        if (!\Illuminate\Support\Facades\Schema::hasColumn('stocks', 'meta_title')) {
            unset($data['meta_title']);
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('stocks', 'meta_description')) {
            unset($data['meta_description']);
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('stocks', 'meta_keywords')) {
            unset($data['meta_keywords']);
        }

        return $data;
    }

    private function parseNumeric($value, $type = 'float')
    {
        if ($value === null || $value === '') {
            return null;
        }
        
        $str = trim((string)$value);
        if ($str === '') {
            return null;
        }

        $cleaned = preg_replace('/[^\d.-]/', '', $str);
        
        if ($cleaned === '' || $cleaned === '-' || $cleaned === '.') {
            return null;
        }
        
        return $type === 'int' ? (int) round((float) $cleaned) : (float) $cleaned;
    }

    private function parseBoolean($value)
    {
        if (is_bool($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['1', 'true', 'yes', 'on']);
        }
        
        return (bool) $value;
    }

    private function parseDateTime($value)
    {
        if (empty($value) || $value === '') {
            return null;
        }
        
        try {
            return \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function resetUpload()
    {
        $this->uploadFile = null;
        $this->showPreview = false;
        $this->previewData = [];
        $this->fileInfo = [];
        $this->resetMessages();
    }

    private function resetMessages()
    {
        $this->successMessage = '';
        $this->errorMessage = '';
        $this->warningMessage = '';
        $this->importedCount = 0;
        $this->skippedCount = 0;
        $this->errorCount = 0;
    }

    public function downloadTemplate($format = 'csv')
    {
        return redirect()->route('admin.stocks.download-template', ['format' => $format]);
    }

    public function render()
    {
        return view('livewire.admin.stocks.bulk-upload');
    }
}
