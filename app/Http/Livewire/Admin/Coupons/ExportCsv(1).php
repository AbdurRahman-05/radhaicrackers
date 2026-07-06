<?php
namespace App\Http\Livewire\Admin\Coupons;

use Livewire\Component;
use App\Models\Coupon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportCsv extends Component
{
    public function export()
    {
        $fileName = 'coupons_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];
        $columns = [
            'code', 'name', 'description', 'type', 'value', 'minimum_order_amount', 'maximum_discount', 'usage_limit', 'used_count', 'user_limit', 'starts_at', 'expires_at', 'is_active', 'applies_to_categories', 'excluded_products', 'bonus_product_id', 'bonus_quantity'
        ];
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach (Coupon::all() as $coupon) {
                $row = [];
                foreach ($columns as $col) {
                    $row[] = $coupon->$col;
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.admin.coupons.export-csv');
    }
} 