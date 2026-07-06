<?php
namespace App\Http\Livewire\Admin\Coupons;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use App\Models\CouponCsvUpload;
use Illuminate\Support\Facades\Log;

class BulkUpload extends Component
{
    use WithFileUploads;

    public $csv_file;
    public $successMessage = '';
    public $errorMessage = '';

    protected $rules = [
        'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ];

    public function upload()
    {
        Log::info('BulkUpload: upload() called', ['csv_file' => $this->csv_file]);
        $this->validate();

        if (!$this->csv_file) {
            $this->errorMessage = 'No file received by Livewire.';
            Log::error('BulkUpload: No file received by Livewire.');
            return;
        }

        try {
            // Store the uploaded file in storage/app/coupon_uploads
            $storedPath = $this->csv_file->store('coupon_uploads');
            Log::info('BulkUpload: File stored', ['path' => $storedPath]);
            // Save file info in the database
            $upload = CouponCsvUpload::create([
                'file_path' => $storedPath,
                'original_name' => $this->csv_file->getClientOriginalName(),
            ]);
            Log::info('BulkUpload: DB record created', ['id' => $upload->id]);

            $data = array_map('str_getcsv', file(storage_path('app/' . $storedPath)));
            $header = array_map('trim', $data[0]);
            unset($data[0]);

            foreach ($data as $row) {
                $row = array_combine($header, $row);

                // Map CSV fields to Coupon fields
                $couponData = [
                    'code' => $row['code'] ?? null,
                    'name' => $row['name'] ?? ($row['code'] ?? ''),
                    'description' => $row['description'] ?? null,
                    'type' => $row['type'] ?? 'fixed_amount',
                    'value' => $row['value'] ?? $row['discount'] ?? 0,
                    'minimum_order_amount' => $row['minimum_order_amount'] ?? 0,
                    'maximum_discount' => $row['maximum_discount'] ?? null,
                    'usage_limit' => $row['usage_limit'] ?? null,
                    'used_count' => 0,
                    'user_limit' => $row['user_limit'] ?? null,
                    'starts_at' => $row['starts_at'] ?? null,
                    'expires_at' => $row['expires_at'] ?? null,
                    'is_active' => $row['is_active'] ?? 1,
                    'applies_to_categories' => $row['applies_to_categories'] ?? null,
                    'excluded_products' => $row['excluded_products'] ?? null,
                    'bonus_product_id' => $row['bonus_product_id'] ?? null,
                    'bonus_quantity' => $row['bonus_quantity'] ?? 1,
                ];

                $validator = Validator::make($couponData, [
                    'code' => 'required|string|unique:coupons,code',
                    'type' => 'required|in:percentage,fixed_amount,bonus_items',
                    'value' => 'required|numeric|min:0',
                    'expires_at' => 'nullable|date',
                ]);

                if ($validator->fails()) {
                    $this->errorMessage = "Row failed: " . implode(', ', $validator->errors()->all());
                    Log::error('BulkUpload: Row validation failed', ['errors' => $validator->errors()->all(), 'row' => $couponData]);
                    return;
                }

                Log::info('BulkUpload: Creating coupon', ['data' => $couponData]);
                Coupon::create($couponData);
            }

            $this->successMessage = "Coupons imported successfully!";
            $this->csv_file = null;
            Log::info('BulkUpload: Success');
        } catch (\Exception $e) {
            $this->errorMessage = "Import failed: " . $e->getMessage();
            Log::error('BulkUpload: Exception', ['error' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.admin.coupons.bulk-upload');
    }
} 