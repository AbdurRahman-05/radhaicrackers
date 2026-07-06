<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Services\PDFService;

class PdfManager extends Component
{
    use WithFileUploads;

    public $priceListPdf;
    public $lastUploaded = '';
    public $showUploadModal = false;
    public $selectedPdfs = [];

    protected $rules = [
        'priceListPdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
    ];

    public function mount()
    {
        $this->loadLastUploaded();
    }

    public function loadLastUploaded()
    {
        if (Storage::disk('public')->exists('pdfs/price-list.pdf')) {
            $this->lastUploaded = Storage::disk('public')->lastModified('pdfs/price-list.pdf');
        }
    }

    public function showUploadForm()
    {
        $this->showUploadModal = true;
    }

    public function uploadPriceList()
    {
        $this->validate([
            'priceListPdf' => 'required|file|mimes:pdf|max:10240'
        ]);

        if ($this->priceListPdf) {
            $filename = 'price-list.pdf';
            $path = $this->priceListPdf->storeAs('pdfs', $filename, 'public');
            
            $this->lastUploaded = now();
            $this->showUploadModal = false;
            $this->priceListPdf = null;
            
            session()->flash('success', 'Price list PDF uploaded successfully!');
        }
    }

    public function downloadPriceList()
    {
        if (Storage::disk('public')->exists('pdfs/price-list.pdf')) {
            return Storage::disk('public')->download('pdfs/price-list.pdf', 'price-list.pdf');
        }
        
        session()->flash('error', 'Price list PDF not found.');
    }

    public function deletePriceList()
    {
        if (Storage::disk('public')->exists('pdfs/price-list.pdf')) {
            Storage::disk('public')->delete('pdfs/price-list.pdf');
            $this->lastUploaded = '';
            session()->flash('success', 'Price list PDF deleted successfully!');
        } else {
            session()->flash('error', 'Price list PDF not found.');
        }
    }

    public function generateOrderReport()
    {
        try {
            $pdfService = new PDFService();
            $orders = \App\Models\Order::with(['user', 'items'])
                ->orderBy('created_at', 'desc')
                ->get();

            $pdf = $pdfService->generateOrderReport($orders);
            
            $filename = 'orders_report_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, $filename);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate order report: ' . $e->getMessage());
        }
    }

    public function generatePaymentReport()
    {
        try {
            $pdfService = new PDFService();
            $payments = \App\Models\Payment::with(['order.user'])
                ->orderBy('created_at', 'desc')
                ->get();

            $pdf = $pdfService->generatePaymentReport($payments);
            
            $filename = 'payments_report_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, $filename);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate payment report: ' . $e->getMessage());
        }
    }

    public function generateStockReport()
    {
        try {
            $pdfService = new PDFService();
            $stocks = \App\Models\Stock::orderBy('created_at', 'desc')->get();

            $pdf = $pdfService->generateStockReport($stocks);
            
            $filename = 'stock_report_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, $filename);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate stock report: ' . $e->getMessage());
        }
    }

    public function downloadOrderConfirmation($orderId)
    {
        try {
            $order = \App\Models\Order::with(['user', 'items'])->find($orderId);
            
            if (!$order) {
                session()->flash('error', 'Order not found.');
                return;
            }

            $pdfService = new PDFService();
            $pdf = $pdfService->generateOrderConfirmation($order);
            
            $filename = "order_{$order->id}_confirmation.pdf";
            
            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, $filename);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate order confirmation: ' . $e->getMessage());
        }
    }

    public function getOrderConfirmations()
    {
        $orders = \App\Models\Order::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return $orders;
    }

    public function render()
    {
        return view('livewire.admin.pdf-manager', [
            'orders' => $this->getOrderConfirmations(),
            'totalOrders' => \App\Models\Order::count(),
            'totalPayments' => \App\Models\Payment::count(),
            'totalStocks' => \App\Models\Stock::count(),
        ]);
    }
} 