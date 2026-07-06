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
            ->orderBy('category')
            ->orderBy('item_name')
            ->get();

        $pdf = PDF::loadView('pdf.price-list', [
            'stocks' => $stocks,
            'generated_at' => now(),
        ])->setPaper('a4', 'portrait');

        $filename = 'price-list-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        $path = 'pdfs/price-list/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    public function generateOrderConfirmation(Order $order)
    {
        // Always reload with relationships
        $order = Order::with(['items', 'user', 'payment', 'logs'])->find($order->id);
        try {
            $items = $order->items;
            // Calculate dynamic page height
            $rowHeight = 28; 
            $headerHeight = 250; 
            $summaryHeight = 220; 
            $margin = 40;
            $numRows = is_array($items) ? count($items) : (is_countable($items) ? count($items) : 0);
            $contentHeight = $headerHeight + ($numRows * $rowHeight) + $summaryHeight + $margin;
            $minHeight = 842; // A4 minimum height
            $maxHeight = 14400; // Maximum allowed height
            $finalHeight = min(max($contentHeight, $minHeight), $maxHeight);

            // Generate PDF with proper formatting
            $pdf = PDF::loadView('pdf.order-confirmation', [
                'order' => $order,
                'items' => $items,
                'user' => $order->user,
                'generated_at' => now(),
                'payment' => $order->payment,
                'logs' => $order->logs
            ])->setPaper([0, 0, 595.28, $finalHeight]); // 595.28 is A4 width

            // Save PDF to storage
            $filename = 'order-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . '.pdf';
            $path = 'pdfs/orders/' . $filename;
            Storage::disk('public')->put($path, $pdf->output());
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('PDF generation failed for order #' . $order->id . ': ' . $e->getMessage());
            throw $e;
        }
    }

    public function downloadOrderConfirmation(Order $order)
    {
        // Always reload with relationships
        $order = Order::with(['items', 'user', 'payment', 'logs'])->find($order->id);
        try {
            // First try to get existing PDF
            $path = $this->getOrderPDF($order);
            
            // Generate new PDF if it doesn't exist
            if (!$path || !Storage::disk('public')->exists($path)) {
                $path = $this->generateOrderConfirmation($order);
            }

            // Double check file existence
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception("PDF file could not be generated: {$path}");
            }

            // Prepare filename with proper formatting
            $filename = 'order-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . '-' . now()->format('Y-m-d') . '.pdf';
            
            // Return file download response
            return Storage::disk('public')->download($path, $filename, [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        } catch (\Exception $e) {
            \Log::error('PDF download failed for order #' . $order->id . ': ' . $e->getMessage());
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
        $rowHeight = 28; $headerHeight = 120; $summaryHeight = 120; $margin = 40;
        $numRows = is_countable($orders) ? count($orders) : $orders->count();
        $contentHeight = $headerHeight + ($numRows * $rowHeight) + $summaryHeight + $margin;
        $minHeight = 842;
        $maxHeight = 14400;
        $finalHeight = min(max($contentHeight, $minHeight), $maxHeight);
        $pdf = PDF::loadView('pdf.order-report', [
            'orders' => $orders,
            'generated_at' => now(),
        ])->setPaper([0, 0, 595.28, $finalHeight]);
        return $pdf;
    }

    public function generatePaymentReport($payments)
    {
        $rowHeight = 28; $headerHeight = 120; $summaryHeight = 120; $margin = 40;
        $numRows = is_countable($payments) ? count($payments) : $payments->count();
        $contentHeight = $headerHeight + ($numRows * $rowHeight) + $summaryHeight + $margin;
        $minHeight = 842;
        $maxHeight = 14400;
        $finalHeight = min(max($contentHeight, $minHeight), $maxHeight);
        $pdf = PDF::loadView('pdf.payment-report', [
            'payments' => $payments,
            'generated_at' => now(),
        ])->setPaper([0, 0, 595.28, $finalHeight]);
        return $pdf;
    }

    public function generateStockReport($stocks)
    {
        $rowHeight = 28; $headerHeight = 120; $summaryHeight = 120; $margin = 40;
        $numRows = is_countable($stocks) ? count($stocks) : $stocks->count();
        $contentHeight = $headerHeight + ($numRows * $rowHeight) + $summaryHeight + $margin;
        $minHeight = 842;
        $maxHeight = 14400;
        $finalHeight = min(max($contentHeight, $minHeight), $maxHeight);
        $pdf = PDF::loadView('pdf.stock-report', [
            'stocks' => $stocks,
            'generated_at' => now(),
        ])->setPaper([0, 0, 595.28, $finalHeight]);
        return $pdf;
    }

    public function downloadUserOrders($orders, $user)
    {
        try {
            $rowHeight = 28; $headerHeight = 120; $summaryHeight = 120; $margin = 40;
            $numRows = is_countable($orders) ? count($orders) : $orders->count();
            $contentHeight = $headerHeight + ($numRows * $rowHeight) + $summaryHeight + $margin;
            $minHeight = 842;
            $maxHeight = 14400;
            $finalHeight = min(max($contentHeight, $minHeight), $maxHeight);
            $pdf = PDF::loadView('pdf.user-orders', [
                'orders' => $orders,
                'user' => $user,
                'generated_at' => now(),
            ])->setPaper([0, 0, 595.28, $finalHeight]);

            $filename = 'my-orders-' . $user->id . '-' . now()->format('Y-m-d-H-i-s') . '.pdf';
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            \Log::error('User orders PDF generation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function generateAdminAllOrdersInvoice($orders)
    {
        try {
            // Ensure we have a proper Order object or collection
            if (!is_object($orders)) {
                throw new \InvalidArgumentException('Invalid order data provided');
            }

            // If single order, convert to collection
            if ($orders instanceof Order) {
                $orders = collect([$orders]);
            }

            // Eager load relationships if not already loaded
            if (method_exists($orders, 'first') && $orders->first() instanceof Order) {
                foreach ($orders as $order) {
                    if (!$order->relationLoaded('items')) {
                        $order->load(['items', 'user', 'payment', 'logs']);
                    }
                }
            }
            
            // Calculate dynamic page height based on content
            $rowHeight = 28; 
            $headerHeight = 250; 
            $summaryHeight = (count($orders) === 1 ? 500 : 220); 
            $margin = 40;
            
            // Calculate total rows from all orders
            $totalRows = 0;
            foreach ($orders as $order) {
                $items = $order->items;
                $totalRows += is_array($items) ? count($items) : (is_countable($items) ? count($items) : 0);
            }
            
            // Calculate content height
            // $contentHeight = (count($orders) * ($headerHeight + $summaryHeight + $margin)) + ($totalRows * $rowHeight);
            // $minHeight = 842; // A4 minimum height
            // $maxHeight = 14400; // Maximum allowed height
            // $finalHeight = min(max($contentHeight, $minHeight), $maxHeight);
            
            // Generate PDF with all necessary data
            $pdf = PDF::loadView('pdf.admin-all-orders-invoice', [
                'orders' => $orders,
                'order' => $orders->first(), // For compatibility
                'generated_at' => now(),
                'total_orders' => count($orders),
                'total_items' => $totalRows
            ])->setPaper('a4','portrait'); // 595.28 is A4 width
            
            // Save PDF to storage
            $filename = 'admin-all-orders-' . now()->format('Y-m-d-H-i-s') . '.pdf';
            $path = 'pdfs/admin-orders/' . $filename;
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory('pdfs/admin-orders');
            Storage::disk('public')->put($path, $pdf->output());
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('Admin all orders PDF generation failed: ' . $e->getMessage());
            throw $e;
        }
    }
} 