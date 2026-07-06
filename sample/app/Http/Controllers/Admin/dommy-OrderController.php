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
        $pdf = $pdfService->generateOrderConfirmation($order);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="order_' . $order->id . '_confirmation.pdf"');
    }

    public function downloadPdf($orderId)
    {
        $order = Order::with(['user', 'items'])->findOrFail($orderId);
        $pdfService = new PDFService();
        $pdf = $pdfService->generateOrderConfirmation($order);

        $filename = "order_{$order->id}_confirmation.pdf";

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
} 