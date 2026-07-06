<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\PDFService;

class OrderController extends Controller
{
    public function viewPdf($orderId)
    {
        $order = Order::with(['user', 'items'])->findOrFail($orderId);
        $pdfService = new PDFService();
        $path = $pdfService->generateOrderConfirmation($order);
        $filename = 'order_' . $order->id . '_confirmation.pdf';
        $pdfContent = \Storage::disk('public')->get($path);
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    public function downloadPdf($orderId)
    {
        $order = Order::with(['user', 'items'])->findOrFail($orderId);
        $pdfService = new PDFService();
        $path = $pdfService->generateOrderConfirmation($order);
        $filename = "order_{$order->id}_confirmation.pdf";
        $pdfContent = \Storage::disk('public')->get($path);
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function downloadInvoicePdf($orderId)
    {
        $order = Order::with(['user', 'items'])->findOrFail($orderId);
        // Dynamic single-page PDF height logic (prevent multi-page)
        // $rowHeight = 28; // points
        // $headerHeight = 250; // points
        // $summaryHeight = 500; // points (match user controller)
        // $margin = 40; // points
        // $numRows = 0;
        // if (isset($order->items) && (is_array($order->items) || $order->items instanceof \Countable)) {
        //     $numRows = count($order->items);
        // }
        // $contentHeight = $headerHeight + ($numRows * $rowHeight) + $summaryHeight + $margin;
        // $minHeight = 842; // A4 height in points
        // $finalHeight = max($contentHeight, $minHeight);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-order-invoice', compact('order'))->setPaper('a4', 'portrait');
        $filename = "order_{$order->id}_invoice.pdf";
        return $pdf->download($filename);
    }

    public function downloadAllInvoicePdf()
    {
        $orders = Order::with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();
        if ($orders->isEmpty()) {
            return back()->with('error', 'No orders found to download.');
        }
        // Dynamic single-page PDF height logic for all orders (prevent multi-page)
        $rowHeight = 28; $headerHeight = 250; $summaryHeight = 500; $margin = 40;
        $totalRows = 0;
        foreach ($orders as $order) {
            if (isset($order->items) && (is_array($order->items) || $order->items instanceof \Countable)) {
                $totalRows += count($order->items);
            }
        }
        $contentHeight = (count($orders) * ($headerHeight + $summaryHeight + $margin)) + ($totalRows * $rowHeight);
        $minHeight = 842;
        $finalHeight = max($contentHeight, $minHeight);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.admin-all-orders-invoice', compact('orders'))->setPaper([0, 0, 595.28, $finalHeight]);
        $filename = "all_orders_invoice_" . date('Y-m-d_H-i-s') . ".pdf";
        return $pdf->download($filename);
    }
} 