<?php

namespace App\Imports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class StockImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    SkipsOnError, 
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $importedCount = 0;
    protected $errors = [];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip if essential fields are empty
        if (empty($row['item_name']) || empty($row['category'])) {
            return null;
        }

        try {
            $stock = new Stock([
                'item_name' => $this->cleanString($row['item_name']),
                'category' => $this->cleanString($row['category']),
                'description' => $this->cleanString($row['description'] ?? ''),
                'quantity' => $this->parseNumeric($row['quantity'] ?? 0, 'int'),
                'price' => $this->parseNumeric($row['price'] ?? 0, 'float'),
                'original_price' => $this->parseNumeric($row['original_price'] ?? null, 'float'),
                'discount_percentage' => $this->parseNumeric($row['discount_percentage'] ?? null, 'int'),
                'special_discount_percentage' => $this->parseNumeric($row['special_discount_percentage'] ?? null, 'int'),
                'is_active' => $this->parseBoolean($row['is_active'] ?? 1),
                'show_on_shop' => $this->parseBoolean($row['show_on_shop'] ?? 1),
                'is_popular' => $this->parseBoolean($row['is_popular'] ?? 0),
                'is_latest' => $this->parseBoolean($row['is_latest'] ?? 0),
                'expires_at' => $this->parseDateTime($row['expires_at'] ?? null),
                'ordered_count' => $this->parseNumeric($row['ordered_count'] ?? 0, 'int'),
                'last_released_at' => $this->parseDateTime($row['last_released_at'] ?? null) ?: now(),
                'next_release_at' => $this->parseDateTime($row['next_release_at'] ?? null) ?: now()->addMinutes(10),
                'youtube_url' => $this->cleanString($row['youtube_url'] ?? ''),
                'image' => $this->cleanString($row['image'] ?? '')
            ]);

            $this->importedCount++;
            return $stock;

        } catch (\Exception $e) {
            $this->errors[] = "Error processing row: " . $e->getMessage();
            Log::error('Stock import error', ['row' => $row, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Validation rules for each row
     */
    public function rules(): array
    {
        return [
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'numeric|min:0',
            'quantity' => 'integer|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'special_discount_percentage' => 'nullable|integer|min:0|max:100',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages(): array
    {
        return [
            'item_name.required' => 'Item name is required.',
            'category.required' => 'Category is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be greater than or equal to 0.',
            'quantity.integer' => 'Quantity must be a valid integer.',
            'quantity.min' => 'Quantity must be greater than or equal to 0.',
        ];
    }

    /**
     * Clean string values
     */
    private function cleanString($value)
    {
        if (is_null($value)) {
            return '';
        }
        
        return trim(strval($value));
    }

    /**
     * Parse numeric values with proper handling
     */
    private function parseNumeric($value, $type = 'float')
    {
        if (empty($value) || $value === '') {
            return null;
        }
        
        // Remove any non-numeric characters except decimal point and negative sign
        $cleaned = preg_replace('/[^\d.-]/', '', strval($value));
        
        if ($cleaned === '' || $cleaned === '-' || $cleaned === '.') {
            return null;
        }
        
        if ($type === 'int') {
            return (int) $cleaned;
        }
        
        return (float) $cleaned;
    }

    /**
     * Parse boolean values
     */
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

    /**
     * Parse datetime values
     */
    private function parseDateTime($value)
    {
        if (empty($value) || $value === '') {
            return null;
        }
        
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get imported count
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    /**
     * Get import errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Batch size for processing
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Chunk size for reading
     */
    public function chunkSize(): int
    {
        return 100;
    }
}
