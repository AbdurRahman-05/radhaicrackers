<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Stock;

class PriceListController extends Controller
{
    public function show()
    {
        $stocks = \App\Models\Stock::where('is_active', true)
            ->where('quantity', '>', 0)
            ->orderBy('item_name')
            ->get();
        return view('pages.price-list', compact('stocks'));
    }

    public function download()
    {
        $stocks = \App\Models\Stock::where('is_active', true)
            ->where('quantity', '>', 0)
            ->orderBy('item_name')
            ->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.price-list', [
            'stocks' => $stocks,
        ])->setPaper('a4', 'portrait');
        return $pdf->download('price-list.pdf');
    }
} 