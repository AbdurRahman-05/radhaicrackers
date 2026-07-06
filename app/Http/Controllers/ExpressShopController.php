<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // If using barryvdh/laravel-dompdf

class ExpressShopController extends Controller
{
    /**
     * Show the express shop page with real stock data
     */
    public function index()
    {
        // Get ALL categories ordered by sort_order from database
        $categories = Category::orderBy('sort_order')
            ->get();

        // Create a mapping of category ID to name for fixing data inconsistencies
        $categoryMapping = [];
        foreach ($categories as $category) {
            $categoryMapping[$category->id] = $category->name;
        }

        $stockData = [];
        $totalAmount = 0;

        foreach ($categories as $category) {
            $categoryName = $category->name;
            
            // Get stocks for this category (handle both name and ID matching)
            $stocks = Stock::query()
                ->where('is_active', 1)
                ->where(function($q) {
                    $q->where('quantity', '>', 0)
                       ->orWhere('show_on_shop', 1);
                })
                ->where(function($q) use ($categoryName, $category) {
                    $q->where('category', $categoryName)
                      ->orWhere('category', $category->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Always show the category, even if it has no products
            $stockData[$categoryName] = $stocks;
            // Calculate total based on minimum quantity (1) for each product
            foreach ($stocks as $stock) {
                $totalAmount += $stock->price;
            }
        }

        return view('pages.express-shop', [
            'stockData' => $stockData,
            'totalAmount' => $totalAmount,
            'categories' => $categories,
            'categoryMapping' => $categoryMapping
        ]);
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

    /**
     * Generate and stream the Express Shop Estimate PDF
     */
    public function estimatePdf(Request $request)
    {
        try {
            $items = $request->input('items', []);
            if (is_string($items)) {
                $items = json_decode($items, true) ?: [];
            }
            $customer = $request->input('customer', []);
            if (is_string($customer)) {
                $customer = json_decode($customer, true) ?: [];
            }
            
            \Log::info('PDF items:', ['items' => $items]);

            // Process items to ensure they have the correct structure for PDF
            $processedItems = [];
            foreach ($items as $item) {
                $processedItems[] = [
                    'product_id' => $item['product_id'] ?? '',
                    'product_name' => $item['product_name'] ?? '',
                    'description' => $item['description'] ?? '',
                    'content' => $item['content'] ?? '',
                    'rate' => $item['rate'] ?? 0,
                    'original_price' => $item['original_price'] ?? $item['rate'] ?? 0,
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'special_discount_percentage' => $item['special_discount_percentage'] ?? 0,
                    'quantity' => $item['quantity'] ?? 0,
                    'total' => $item['total'] ?? 0,
                ];
            }

            $order = (object)[
                'id' => rand(10000, 99999),
                'created_at' => now(),
                'customer_name' => $customer['name'] ?? 'Guest',
                'customer_mobile' => $customer['mobile'] ?? '',
                'customer_email' => $customer['email'] ?? '',
                'customer_city' => $customer['city'] ?? '',
                'customer_state' => $customer['state'] ?? '',
                'pin_code' => $customer['pin_code'] ?? '',
                'items' => $processedItems,
                'coupon_code' => $request->input('coupon_code'),
                'coupon_discount' => $request->input('coupon_discount', 0),
            ];

            // Estimate required height: header + (row count * row height) + summary + margin
            $rowHeight = 28; // points, adjust as needed
            $headerHeight = 250; // points, adjust as needed
            $summaryHeight = 220; // points, adjust as needed
            $margin = 40; // points
            $numRows = count($processedItems);
            $contentHeight = $headerHeight + ($numRows * $rowHeight) + $summaryHeight + $margin;
            // Minimum height to avoid too small page
            $minHeight = 842; // A4 height in points
            $finalHeight = max($contentHeight, $minHeight);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.express-shop-products-test', compact('order'))
                ->setPaper('A4','portrait'); // A4 width, dynamic height
            return $pdf->download('estimate.pdf');
        } catch (\Throwable $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response('PDF generation failed: ' . $e->getMessage(), 500);
        }
    }
} 