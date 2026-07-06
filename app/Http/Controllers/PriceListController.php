<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Stock;
use App\Models\Category;

class PriceListController extends Controller
{
    private function getCategoriesOrderCase()
    {
        // Get categories ordered by sort_order from database
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Create the CASE statement for ordering categories
        $orderByCase = "CASE category ";
        foreach ($categories as $index => $category) {
            $orderByCase .= "WHEN '{$category->name}' THEN $index ";
        }
        $orderByCase .= "ELSE " . $categories->count() . " END";

        return [$orderByCase, $categories];
    }

    public function show()
    {
        [$orderByCase, $categories] = $this->getCategoriesOrderCase();

        // Show all products that are active, ordered by category and then by name
        $stocks = Stock::where('is_active', true)
            ->orderByRaw($orderByCase)
            ->orderBy('item_name')
            ->get();
            
        return view('pages.price-list', [
            'stocks' => $stocks,
            'categories' => $categories
        ]);
    }

    public function download(Request $request)
    {
        [$orderByCase, $categories] = $this->getCategoriesOrderCase();

        // Show all products that are active, ordered by category and then by name
        $stocks = Stock::where('is_active', true)
            ->orderByRaw($orderByCase)
            ->orderBy('item_name')
            ->get();

        $showImages = $request->query('images', '1') !== '0';
        $showPrices = $request->query('prices', '1') !== '0';

        $pdf = Pdf::loadView('pdf.price-list', [
            'stocks' => $stocks,
            'categories' => $categories,
            'showImages' => $showImages,
            'showPrices' => $showPrices,
        ])->setPaper('a4', 'portrait');

        $filename = 'price-list';
        if (!$showImages) {
            $filename .= '-no-images';
        }
        if (!$showPrices) {
            $filename .= '-no-prices';
        }
        $filename .= '.pdf';

        return $pdf->download($filename);
    }
}