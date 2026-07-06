<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // If using barryvdh/laravel-dompdf

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



    // Estimate pdf

    public function generateEstimatePdf(Request $request)
    {
        $items = $request->input('items'); // array of products
        $customer = $request->input('customer'); // optional: customer details

        // Prepare a fake order object for the PDF blade
        $order = (object)[
            'id' => rand(10000, 99999),
            'created_at' => now(),
            'customer_name' => $customer['name'] ?? 'Guest',
            'customer_mobile' => $customer['mobile'] ?? '',
            'customer_email' => $customer['email'] ?? '',
            'customer_city' => $customer['city'] ?? '',
            'customer_state' => $customer['state'] ?? '',
            'pin_code' => $customer['pin_code'] ?? '',
            'items_json' => $items,
            'coupon_code' => null,
            'coupon_discount' => null,
        ];

        $pdf = Pdf::loadView('pdf.express-shop-products', compact('order'));
        return $pdf->download('estimate.pdf');
    }
} 