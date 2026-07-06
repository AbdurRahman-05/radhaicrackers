<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class ExpressShopController extends Controller
{
    /**
     * Show the express shop page with real stock data
     */
    public function index()
    {
        // Get all active stocks grouped by category
        $categories = [
            'BOMBS',
            'SINGLE FLASH', 
            'ROCKETS',
            'SPARKLERS',
            'CHIT PUT',
            'TWINKLING STAR',
            'GIFT BOX',
            'BIJILI CRACKERS'
        ];

        $stockData = [];
        $totalAmount = 0;

        foreach ($categories as $category) {
            $stocks = Stock::active()
                ->where('category', $category)
                ->where('quantity', '>', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($stocks->count() > 0) {
                $stockData[$category] = $stocks;
                // Calculate total based on minimum quantity (1) for each product
                foreach ($stocks as $stock) {
                    $totalAmount += $stock->price;
                }
            }
        }

        return view('pages.express-shop', compact('stockData', 'totalAmount'));
    }
} 