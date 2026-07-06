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
        $data = [];
        $imported = 0;
        $skipped = 0;
        $errors = 0;

        if (($handle = fopen($path, 'r')) !== false) {
            setlocale(LC_ALL, 'en_US.UTF-8');
            
            $header = fgetcsv($handle, 0, ',', '"', '\\');
            if (!$header) {
                throw new \Exception('Invalid CSV file format');
            }

            // Clean headers
            $header = array_map(function($h) {
                return trim(strtolower(str_replace([' ', '-'], '_', $h)));
            }, $header);

            while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        $skipped++;
                        continue;
                    }

                    $rowData = array_combine($header, $row);
                    
                    // Skip if essential fields are missing
                    if (empty($rowData['item_name']) || empty($rowData['category'])) {
                        $skipped++;
                        continue;
                    }

                    // Create stock item
                    Stock::create($this->processRowData($rowData));
                    $imported++;

                } catch (\Exception $e) {
                    $errors++;
                    Log::error('CSV row import error', ['row' => $row ?? [], 'error' => $e->getMessage()]);
                }
            }
            fclose($handle);
        }

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
        
        if (($handle = fopen($path, 'r')) !== false) {
            $count = 0;
            while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false && $count < 6) {
                $data[] = $row;
                $count++;
            }
            fclose($handle);
        }
        
        return $data;
    }

    private function processRowData($rowData)
    {
        return [
            'item_name' => trim($rowData['item_name']),
            'category' => trim($rowData['category']),
            'description' => trim($rowData['description'] ?? ''),
            'quantity' => $this->parseNumeric($rowData['quantity'] ?? 0, 'int'),
            'price' => $this->parseNumeric($rowData['price'] ?? 0, 'float'),
            'original_price' => $this->parseNumeric($rowData['original_price'] ?? null, 'float'),
            'discount_percentage' => $this->parseNumeric($rowData['discount_percentage'] ?? null, 'int'),
            'special_discount_percentage' => $this->parseNumeric($rowData['special_discount_percentage'] ?? null, 'int'),
            'is_active' => $this->parseBoolean($rowData['is_active'] ?? 1),
            'show_on_shop' => $this->parseBoolean($rowData['show_on_shop'] ?? 1),
            'is_popular' => $this->parseBoolean($rowData['is_popular'] ?? 0),
            'is_latest' => $this->parseBoolean($rowData['is_latest'] ?? 0),
            'expires_at' => $this->parseDateTime($rowData['expires_at'] ?? null),
            'ordered_count' => $this->parseNumeric($rowData['ordered_count'] ?? 0, 'int'),
            'last_released_at' => $this->parseDateTime($rowData['last_released_at'] ?? null) ?: now(),
            'next_release_at' => $this->parseDateTime($rowData['next_release_at'] ?? null) ?: now()->addMinutes(10),
            'youtube_url' => trim($rowData['youtube_url'] ?? ''),
            'image' => trim($rowData['image'] ?? '')
        ];
    }

    private function parseNumeric($value, $type = 'float')
    {
        if (empty($value) || $value === '') {
            return null;
        }
        
        $cleaned = preg_replace('/[^\d.-]/', '', strval($value));
        
        if ($cleaned === '' || $cleaned === '-' || $cleaned === '.') {
            return null;
        }
        
        return $type === 'int' ? (int) $cleaned : (float) $cleaned;
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
