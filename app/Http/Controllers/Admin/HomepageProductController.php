<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageProduct;
use App\Models\Category;

class HomepageProductController extends Controller
{
    public function index()
    {
        $products = HomepageProduct::orderBy('created_at', 'desc')->get();
        return view('admin.homepage_products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->pluck('name', 'id');
        return view('admin.homepage_products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|integer',
            'description' => 'nullable|string',
            'original_price' => 'nullable|numeric',
            'discount_percentage' => 'nullable|integer',
            'special_discount_percentage' => 'nullable|integer',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'youtube_url' => 'nullable|string|max:255',
            'is_popular' => 'boolean',
            'is_latest' => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('homepage_products', 'public');
        }
        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_latest'] = $request->has('is_latest');
        HomepageProduct::create($validated);
        return redirect()->route('admin.homepage_products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = HomepageProduct::findOrFail($id);
        $categories = Category::active()->ordered()->pluck('name', 'id');
        return view('admin.homepage_products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = HomepageProduct::findOrFail($id);
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|integer',
            'description' => 'nullable|string',
            'original_price' => 'nullable|numeric',
            'discount_percentage' => 'nullable|integer',
            'special_discount_percentage' => 'nullable|integer',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'youtube_url' => 'nullable|string|max:255',
            'is_popular' => 'boolean',
            'is_latest' => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('homepage_products', 'public');
        }
        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_latest'] = $request->has('is_latest');
        $product->update($validated);
        return redirect()->route('admin.homepage_products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = HomepageProduct::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.homepage_products.index')->with('success', 'Product deleted successfully.');
    }
}
