<?php
namespace App\Http\Livewire\Admin\Coupons;

use Livewire\Component;

class ExportCsv extends Component
{
    public function export()
    {
        // Redirect to the controller method for proper CSV export
        return redirect()->route('admin.coupons.export-csv');
    }

    public function render()
    {
        return view('livewire.admin.coupons.export-csv');
    }
} 