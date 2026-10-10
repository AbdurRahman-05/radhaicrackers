<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Show the shop page with optional category filtering
     */
    public function index(Request $request)
    {
        
          $query = Stock::with('images')
            ->where('is_active', 1);

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where(function($q) use ($request) {
                $q->where('category', $request->category)
                  ->orWhere('category_id', $request->category);
            });
        }

        // Get categories ordered by sort_order from the database
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->select('id', 'name', 'icon')
            ->get();

        // Build the CASE statement for ordering by category name
        $orderByCase = "CASE category ";
        foreach ($categories as $index => $category) {
            $orderByCase .= "WHEN '" . $category->name . "' THEN $index ";
        }
        $orderByCase .= "ELSE " . $categories->count() . " END";

        $products = $query->orderByRaw($orderByCase)
                         ->orderBy('created_at', 'desc')
                         ->paginate(12);        // Get categories with their counts and maintain database sort order
        $categoriesWithCounts = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(function($category) {
                $count = Stock::where('is_active', 1)
                    ->where(function($q) use ($category) {
                        $q->where('category', $category->name)
                          ->orWhere('category_id', $category->id);
                    })
                    ->count();
                
                return [$category->id => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'icon' => $category->icon ?? '🎆',
                    'count' => $count
                ]];
            });
        
        return view('pages.shop', [
            'products' => $products,
            'categories' => $categoriesWithCounts
        ]);
    }
} 