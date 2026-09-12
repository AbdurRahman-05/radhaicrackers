<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageProduct;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Support\Facades\File;

class HomepageProductController extends Controller
{
    /**
     * Display all homepage products.
     */
    public function index()
    {
        $products = HomepageProduct::with('categoryRelation')
            ->orderBy('created_at', 'desc')
            ->get();

        // Also check how many stocks in inventory have popular/latest flag
        $popularStocksCount = Stock::where('is_popular', true)->count();
        $latestStocksCount = Stock::where('is_latest', true)->count();

        return view('admin.homepage_products.index', compact('products', 'popularStocksCount', 'latestStocksCount'));
    }

    /**
     * Show form to add a new homepage product.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->pluck('name', 'id');

        // Fetch all inventory products, prioritizing Current Year Active Products (is_active = 1)
        $stocks = Stock::orderByRaw('CASE WHEN is_active = 1 THEN 0 ELSE 1 END')
            ->orderBy('item_name')
            ->get(['id', 'item_name', 'category', 'category_id', 'description', 'price', 'original_price', 'discount_percentage', 'special_discount_percentage', 'quantity', 'image', 'youtube_url', 'is_popular', 'is_latest', 'is_active', 'created_at'])
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->item_name,
                    'category' => $s->category,
                    'category_id' => $s->category_id,
                    'description' => $s->description ?? '',
                    'price' => (float)$s->price,
                    'original_price' => (float)$s->original_price,
                    'discount_percentage' => (int)($s->discount_percentage ?? 70),
                    'special_discount_percentage' => (int)($s->special_discount_percentage ?? 15),
                    'quantity' => (int)($s->quantity ?? 0),
                    'image' => $s->image,
                    'image_url' => $s->image_url,
                    'youtube_url' => $s->youtube_url ?? '',
                    'is_popular' => (bool)$s->is_popular,
                    'is_latest' => (bool)$s->is_latest,
                    'is_active' => (bool)$s->is_active,
                    'is_current_active' => ($s->is_active == 1),
                ];
            });

        $availableImages = \App\Models\HomepageCategory::getAvailable2026Images();

        return view('admin.homepage_products.create', compact('categories', 'stocks', 'availableImages'));
    }

    /**
     * Store newly created homepage product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'nullable',
            'description' => 'nullable|string',
            'original_price' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric',
            'special_discount_percentage' => 'nullable|numeric',
            'price' => 'required|numeric',
            'quantity' => 'nullable|integer',
            'youtube_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
            'existing_image' => 'nullable|string',
            'selected_image' => 'nullable|string',
        ]);

        $imagePath = null;

        // Handle uploaded file or selected 2026 gallery image or existing image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());

            $targetDir = public_path('storage/homepage_products');
            $storageTargetDir = storage_path('app/public/homepage_products');
            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            if (!File::isDirectory($storageTargetDir)) {
                File::makeDirectory($storageTargetDir, 0755, true);
            }

            $file->move($targetDir, $filename);
            @copy($targetDir . '/' . $filename, $storageTargetDir . '/' . $filename);

            $imagePath = 'homepage_products/' . $filename;
        } elseif (!empty($validated['selected_image'])) {
            $imagePath = $validated['selected_image'];
        } elseif (!empty($validated['existing_image'])) {
            $imagePath = $validated['existing_image'];
        }

        // Clean category
        $categoryValue = $validated['category'] ?? '';
        if (is_numeric($categoryValue)) {
            $categoryValue = (int)$categoryValue;
        } else {
            // Find category ID by name if possible
            $cat = Category::where('name', $categoryValue)->first();
            if ($cat) {
                $categoryValue = $cat->id;
            }
        }

        $product = new HomepageProduct();
        $product->item_name = $validated['item_name'];
        $product->category = $categoryValue ?: 0;
        $product->description = $validated['description'] ?? '';
        $product->original_price = $validated['original_price'] ?? null;
        $product->discount_percentage = $validated['discount_percentage'] ?? 70;
        $product->special_discount_percentage = $validated['special_discount_percentage'] ?? 15;
        $product->price = $validated['price'];
        $product->quantity = $validated['quantity'] ?? 0;
        $product->youtube_url = $validated['youtube_url'] ?? null;
        $product->is_active = $request->boolean('is_active', true);
        $product->is_popular = $request->boolean('is_popular');
        $product->is_latest = $request->boolean('is_latest');
        if ($imagePath) {
            $product->image = $imagePath;
        }
        $product->save();

        return redirect()->route('admin.homepage_products.index')->with('success', 'Home page product added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $product = HomepageProduct::findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('name')->pluck('name', 'id');

        $stocks = Stock::orderByRaw('CASE WHEN is_active = 1 THEN 0 ELSE 1 END')
            ->orderBy('item_name')
            ->get(['id', 'item_name', 'category', 'category_id', 'description', 'price', 'original_price', 'discount_percentage', 'special_discount_percentage', 'quantity', 'image', 'youtube_url', 'is_popular', 'is_latest', 'is_active', 'created_at'])
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->item_name,
                    'category' => $s->category,
                    'category_id' => $s->category_id,
                    'description' => $s->description ?? '',
                    'price' => (float)$s->price,
                    'original_price' => (float)$s->original_price,
                    'discount_percentage' => (int)($s->discount_percentage ?? 70),
                    'special_discount_percentage' => (int)($s->special_discount_percentage ?? 15),
                    'quantity' => (int)($s->quantity ?? 0),
                    'image' => $s->image,
                    'image_url' => $s->image_url,
                    'youtube_url' => $s->youtube_url ?? '',
                    'is_popular' => (bool)$s->is_popular,
                    'is_latest' => (bool)$s->is_latest,
                    'is_active' => (bool)$s->is_active,
                    'is_current_active' => ($s->is_active == 1),
                ];
            });

        $availableImages = \App\Models\HomepageCategory::getAvailable2026Images();

        return view('admin.homepage_products.edit', compact('product', 'categories', 'stocks', 'availableImages'));
    }

    /**
     * Update homepage product.
     */
    public function update(Request $request, $id)
    {
        $product = HomepageProduct::findOrFail($id);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'nullable',
            'description' => 'nullable|string',
            'original_price' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric',
            'special_discount_percentage' => 'nullable|numeric',
            'price' => 'required|numeric',
            'quantity' => 'nullable|integer',
            'youtube_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
            'existing_image' => 'nullable|string',
            'selected_image' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());

            $targetDir = public_path('storage/homepage_products');
            $storageTargetDir = storage_path('app/public/homepage_products');
            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            if (!File::isDirectory($storageTargetDir)) {
                File::makeDirectory($storageTargetDir, 0755, true);
            }

            $file->move($targetDir, $filename);
            @copy($targetDir . '/' . $filename, $storageTargetDir . '/' . $filename);

            $product->image = 'homepage_products/' . $filename;
        } elseif (!empty($validated['selected_image'])) {
            $product->image = $validated['selected_image'];
        } elseif (!empty($validated['existing_image'])) {
            $product->image = $validated['existing_image'];
        }

        // Clean category
        $categoryValue = $validated['category'] ?? '';
        if (is_numeric($categoryValue)) {
            $categoryValue = (int)$categoryValue;
        } else {
            $cat = Category::where('name', $categoryValue)->first();
            if ($cat) {
                $categoryValue = $cat->id;
            }
        }

        $product->item_name = $validated['item_name'];
        if ($categoryValue) {
            $product->category = $categoryValue;
        }
        $product->description = $validated['description'] ?? '';
        $product->original_price = $validated['original_price'] ?? null;
        $product->discount_percentage = $validated['discount_percentage'] ?? 70;
        $product->special_discount_percentage = $validated['special_discount_percentage'] ?? 15;
        $product->price = $validated['price'];
        $product->quantity = $validated['quantity'] ?? 0;
        $product->youtube_url = $validated['youtube_url'] ?? null;
        $product->is_active = $request->boolean('is_active');
        $product->is_popular = $request->boolean('is_popular');
        $product->is_latest = $request->boolean('is_latest');
        $product->save();

        return redirect()->route('admin.homepage_products.index')->with('success', 'Home page product updated successfully.');
    }

    /**
     * Delete homepage product.
     */
    public function destroy($id)
    {
        $product = HomepageProduct::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.homepage_products.index')->with('success', 'Home page product deleted successfully.');
    }

    /**
     * Toggle Popular status.
     */
    public function togglePopular($id)
    {
        $product = HomepageProduct::findOrFail($id);
        $product->is_popular = !$product->is_popular;
        $product->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'is_popular' => $product->is_popular]);
        }

        return redirect()->back()->with('success', 'Popular status updated.');
    }

    /**
     * Toggle Latest status.
     */
    public function toggleLatest($id)
    {
        $product = HomepageProduct::findOrFail($id);
        $product->is_latest = !$product->is_latest;
        $product->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'is_latest' => $product->is_latest]);
        }

        return redirect()->back()->with('success', 'Latest status updated.');
    }

    /**
     * Import all popular & latest products from Stock catalog.
     */
    public function syncFromStocks()
    {
        $featuredStocks = Stock::where('is_active', 1)
            ->where(function ($q) {
                $q->where('is_popular', true)->orWhere('is_latest', true);
            })
            ->get();

        $importedCount = 0;
        foreach ($featuredStocks as $stock) {
            // Find category ID
            $catId = $stock->category_id;
            if (!$catId) {
                $c = Category::where('name', $stock->category)->first();
                $catId = $c ? $c->id : 0;
            }

            // Check if already exists in HomepageProduct
            $existing = HomepageProduct::where('item_name', $stock->item_name)->first();
            if (!$existing) {
                HomepageProduct::create([
                    'item_name' => $stock->item_name,
                    'category' => $catId ?: 0,
                    'description' => $stock->description ?? '',
                    'original_price' => $stock->original_price,
                    'discount_percentage' => $stock->discount_percentage ?? 70,
                    'special_discount_percentage' => $stock->special_discount_percentage ?? 15,
                    'price' => $stock->price,
                    'quantity' => $stock->quantity ?? 0,
                    'is_active' => true,
                    'image' => $stock->image,
                    'youtube_url' => $stock->youtube_url,
                    'is_popular' => (bool)$stock->is_popular,
                    'is_latest' => (bool)$stock->is_latest,
                ]);
                $importedCount++;
            } else {
                $existing->update([
                    'is_popular' => (bool)$stock->is_popular,
                    'is_latest' => (bool)$stock->is_latest,
                    'price' => $stock->price,
                    'image' => $stock->image ?: $existing->image,
                ]);
            }
        }

        return redirect()->route('admin.homepage_products.index')
            ->with('success', "Successfully synced featured products from inventory ({$importedCount} new imported).");
    }
}
