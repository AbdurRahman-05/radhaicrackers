<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Show the shop page with optional category filtering
     */
    public function index(Request $request)
    {
        $query = Stock::query();
        $query->where(function($q) {
            $q->where(function($sub) {
                $sub->where('is_active', 1)
                    ->where('quantity', '>', 0);
            })->orWhere('show_on_shop', 1);
        });
        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }
        // Get products with pagination
        $products = $query->orderBy('created_at', 'desc')->paginate(12);
        // Get category counts for sidebar (including products with show_on_shop enabled)
        $categories = [
            'BIJILI CRACKERS' => Stock::where('category', 'BIJILI CRACKERS')->where(function($q) {
                $q->where(function($sub) {
                    $sub->where('is_active', 1)->where('quantity', '>', 0);
                })->orWhere('show_on_shop', 1);
            })->count(),
            'BOMBS' => Stock::where('category', 'BOMBS')->where(function($q) {
                $q->where(function($sub) {
                    $sub->where('is_active', 1)->where('quantity', '>', 0);
                })->orWhere('show_on_shop', 1);
            })->count(),
            'CHIT PUT' => Stock::where('category', 'CHIT PUT')->where(function($q) {
                $q->where(function($sub) {
                    $sub->where('is_active', 1)->where('quantity', '>', 0);
                })->orWhere('show_on_shop', 1);
            })->count(),
            'GIFT BOX' => Stock::where('category', 'GIFT BOX')->where(function($q) {
                $q->where(function($sub) {
                    $sub->where('is_active', 1)->where('quantity', '>', 0);
                })->orWhere('show_on_shop', 1);
            })->count(),
            'ROCKETS' => Stock::where('category', 'ROCKETS')->where(function($q) {
                $q->where(function($sub) {
                    $sub->where('is_active', 1)->where('quantity', '>', 0);
                })->orWhere('show_on_shop', 1);
            })->count(),
            'SINGLE FLASH' => Stock::where('category', 'SINGLE FLASH')->where(function($q) {
                $q->where(function($sub) {
                    $sub->where('is_active', 1)->where('quantity', '>', 0);
                })->orWhere('show_on_shop', 1);
            })->count(),
            'SPARKLERS' => Stock::where('category', 'SPARKLERS')->where(function($q) {
                $q->where(function($sub) {
                    $sub->where('is_active', 1)->where('quantity', '>', 0);
                })->orWhere('show_on_shop', 1);
            })->count(),
            'TWINKLING STAR' => Stock::where('category', 'TWINKLING STAR')->where(function($q) {
                $q->where(function($sub) {
                    $sub->where('is_active', 1)->where('quantity', '>', 0);
                })->orWhere('show_on_shop', 1);
            })->count(),
        ];
        return view('pages.shop', compact('products', 'categories'));
    }
} 