<?php

namespace App\Http\Controllers;

use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    protected $pdfService;

    public function __construct(PDFService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function downloadPriceList()
    {
        $pdfPath = $this->pdfService->getLatestPriceList();

        if (!$pdfPath) {
            $pdfPath = $this->pdfService->generatePriceList();
        }

        return response()->download(storage_path('app/public/' . $pdfPath));
    }

    public function downloadOrderPDF($id)
    {
        $order = \App\Models\Order::where('user_id', auth()->id())->findOrFail($id);
        $pdfPath = $this->pdfService->getOrderPDF($order);

        if (!$pdfPath) {
            $pdfPath = $this->pdfService->generateOrderConfirmation($order);
        }

        return response()->download(storage_path('app/public/' . $pdfPath));
    }
} 