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
        // Get ALL active categories ordered by sort_order from database
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Create a mapping of category ID to name for fixing data inconsistencies
        $categoryMapping = [];
        foreach ($categories as $category) {
            $categoryMapping[$category->id] = $category->name;
        }

        $stockData = [];
        $activeCategories = [];
        $totalAmount = 0;

        foreach ($categories as $category) {
            $categoryName = $category->name;
            
            // Get stocks for this category (handle both name and ID matching)
            $stocks = Stock::query()
                ->where('is_active', 1)
                ->where('show_on_shop', 1)
                ->where(function($q) use ($categoryName, $category) {
                    $q->where('category', $categoryName)
                      ->orWhere('category', (string)$category->id)
                      ->orWhere('category_id', $category->id);
                })
                ->orderBy('order_within_category', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

            // Only show categories that have products available for the current active inventory
            if ($stocks->isNotEmpty()) {
                $stockData[$categoryName] = $stocks;
                $activeCategories[] = $category;
                foreach ($stocks as $stock) {
                    $totalAmount += $stock->price;
                }
            }
        }

        return view('pages.express-shop', [
            'stockData' => $stockData,
            'totalAmount' => $totalAmount,
            'categories' => !empty($activeCategories) ? collect($activeCategories) : $categories,
            'categoryMapping' => $categoryMapping
        ]);
    }



    // Estimate pdf

    public function generateEstimatePdf(Request $request)
    {
        return $this->estimatePdf($request);
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

            // Collect stock IDs to query additional stock details if needed
            $productIds = [];
            foreach ($items as $item) {
                $pid = $item['product_id'] ?? $item['stock_id'] ?? $item['id'] ?? null;
                if ($pid && is_numeric($pid) && (int)$pid < 999000) {
                    $productIds[] = (int)$pid;
                }
            }
            $stocks = !empty($productIds)
                ? \App\Models\Stock::whereIn('id', $productIds)->get()->keyBy('id')
                : collect();

            // Process items to ensure they have the correct structure for PDF
            $processedItems = [];
            foreach ($items as $item) {
                if (is_object($item)) {
                    $item = (array)$item;
                }
                $pid = $item['product_id'] ?? $item['stock_id'] ?? $item['id'] ?? '';
                $stock = is_numeric($pid) ? $stocks->get((int)$pid) : null;

                $productName = !empty($item['product_name'])
                    ? $item['product_name']
                    : (!empty($item['name']) ? $item['name'] : ($stock ? $stock->item_name : 'Product'));

                $desc = !empty($item['description'])
                    ? $item['description']
                    : ($stock && !empty($stock->description) ? $stock->description : '');

                $rate = isset($item['rate']) ? (float)$item['rate'] : (isset($item['price']) ? (float)$item['price'] : ($stock ? (float)$stock->price : 0));

                $origPrice = isset($item['original_price']) && (float)$item['original_price'] > 0
                    ? (float)$item['original_price']
                    : ($stock && (float)$stock->original_price > 0 ? (float)$stock->original_price : $rate);

                $qty = isset($item['quantity']) ? (int)$item['quantity'] : (isset($item['qty']) ? (int)$item['qty'] : 1);
                $isCombo = !empty($item['is_combo']) || ($pid >= 999000 && $pid <= 999999) || (str_contains(strtoupper($productName), 'COMBO'));

                $processedItems[] = [
                    'product_id' => $pid,
                    'stock_id' => $pid,
                    'product_name' => $productName,
                    'description' => $desc,
                    'content' => $item['content'] ?? '',
                    'rate' => $rate,
                    'price' => $rate,
                    'original_price' => $origPrice,
                    'discount_percentage' => $item['discount_percentage'] ?? ($stock ? $stock->discount_percentage : ($isCombo ? 0 : 70)),
                    'special_discount_percentage' => $item['special_discount_percentage'] ?? ($stock ? $stock->special_discount_percentage : ($isCombo ? 0 : 15)),
                    'quantity' => $qty,
                    'total' => $item['total'] ?? ($isCombo ? ($rate * $qty) : ($origPrice * $qty)),
                    'is_combo' => $isCombo,
                    'is_lucky_spin_gift' => !empty($item['is_lucky_spin_gift']),
                    'is_free_gift' => !empty($item['is_free_gift']),
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
                'items_json' => $processedItems,
                'coupon_code' => $request->input('coupon_code'),
                'coupon_discount' => $request->input('coupon_discount', 0),
                'lucky_spin_prize' => $request->input('lucky_spin_prize'),
                'lucky_spin_discount' => $request->input('lucky_spin_discount', 0),
            ];

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.express-shop-products-test', compact('order'))
                ->setPaper('A4', 'portrait');
            return $pdf->download('estimate.pdf');
        } catch (\Throwable $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response('PDF generation failed: ' . $e->getMessage(), 500);
        }
    }
} 