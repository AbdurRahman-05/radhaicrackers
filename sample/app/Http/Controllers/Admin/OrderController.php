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
} 