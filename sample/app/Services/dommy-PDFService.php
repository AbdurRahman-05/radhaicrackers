<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Stock;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFService
{
    public function generatePriceList(): string
    {
        $stocks = Stock::where('is_active', true)
            ->where('quantity', '>', 0)
            ->orderBy('item_name')
            ->get();

        $pdf = PDF::loadView('pdf.price-list', [
            'stocks' => $stocks,
            'generated_at' => now(),
        ]);

        $filename = 'price-list-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        $path = 'pdfs/price-list/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    public function generateOrderConfirmation(Order $order)
    {
        try {
            $pdf = PDF::loadView('pdf.order-confirmation', [
                'order' => $order,
                'items' => $order->items,
                'user' => $order->user,
            ]);

            $filename = 'order-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . '.pdf';
            $path = 'pdfs/orders/' . $filename;
            Storage::disk('public')->put($path, $pdf->output());
            return $path;
        } catch (\Exception $e) {
            \Log::error('PDF generation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function downloadOrderConfirmation(Order $order)
    {
        try {
            $path = $this->getOrderPDF($order);
            
            if (!$path) {
                $path = $this->generateOrderConfirmation($order);
            }

            // Verify the file exists before attempting to download
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception("PDF file not found: {$path}");
            }

            $filename = 'order-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . '.pdf';
            
            return Storage::disk('public')->download($path, $filename);
        } catch (\Exception $e) {
            \Log::error('PDF download failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function downloadPriceList()
    {
        $path = $this->getLatestPriceList();
        
        if (!$path) {
            $path = $this->generatePriceList();
        }

        $filename = 'price-list-' . now()->format('Y-m-d') . '.pdf';
        
        return Storage::disk('public')->download($path, $filename);
    }

    public function getLatestPriceList(): ?string
    {
        $files = Storage::disk('public')->files('pdfs/price-list');
        
        if (empty($files)) {
            return null;
        }

        // Get the most recent file
        $latestFile = collect($files)->sortByDesc(function ($file) {
            return Storage::disk('public')->lastModified($file);
        })->first();

        return $latestFile;
    }

    public function getOrderPDF(Order $order): ?string
    {
        $filename = 'order-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . '.pdf';
        $path = 'pdfs/orders/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            return $path;
        }

        return null;
    }

    public function generateOrderReport($orders)
    {
        $pdf = PDF::loadView('pdf.order-report', [
            'orders' => $orders,
            'generated_at' => now(),
        ]);

        return $pdf;
    }

    public function generatePaymentReport($payments)
    {
        $pdf = PDF::loadView('pdf.payment-report', [
            'payments' => $payments,
            'generated_at' => now(),
        ]);

        return $pdf;
    }

    public function generateStockReport($stocks)
    {
        $pdf = PDF::loadView('pdf.stock-report', [
            'stocks' => $stocks,
            'generated_at' => now(),
        ]);

        return $pdf;
    }
} 