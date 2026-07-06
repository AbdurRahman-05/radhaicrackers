<?php
namespace App\Http\Livewire\Admin\Coupons;

use Livewire\Component;
use App\Models\Coupon;
use App\Models\CouponCsvUpload;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    public $previewRows = [];
    public $previewHeaders = [];
    public $previewFileId = null;
    public $showPreview = false;

    public function previewCsv($id)
    {
        $upload = CouponCsvUpload::findOrFail($id);
        $path = storage_path('app/' . $upload->file_path);
        if (file_exists($path)) {
            $rows = array_map('str_getcsv', file($path));
            $this->previewHeaders = $rows[0] ?? [];
            $this->previewRows = array_slice(array_slice($rows, 1), 0, 5);
            $this->previewFileId = $id;
            $this->showPreview = true;
        }
    }

    public function closePreview()
    {
        $this->showPreview = false;
        $this->previewRows = [];
        $this->previewHeaders = [];
        $this->previewFileId = null;
    }

    public function deleteCsv($id)
    {
        $upload = CouponCsvUpload::findOrFail($id);
        Storage::delete($upload->file_path);
        $upload->delete();
        session()->flash('success', 'CSV file deleted successfully.');
    }

    public function render()
    {
        $coupons = Coupon::with('bonusProduct')->orderByDesc('id')->paginate(20);
        $csvUploads = CouponCsvUpload::orderByDesc('created_at')->get();
        return view('livewire.admin.coupons.index', [
            'coupons' => $coupons,
            'csvUploads' => $csvUploads,
            'previewRows' => $this->previewRows,
            'previewHeaders' => $this->previewHeaders,
            'previewFileId' => $this->previewFileId,
            'showPreview' => $this->showPreview,
        ]);
    }
} 