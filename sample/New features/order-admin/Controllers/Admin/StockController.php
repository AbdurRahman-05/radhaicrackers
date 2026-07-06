<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::query()
            ->when(request('search'), function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('item_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when(request('status_filter'), function($query, $status) {
                switch($status) {
                    case 'active':
                        $query->where('is_active', true);
                        break;
                    case 'inactive':
                        $query->where('is_active', false);
                        break;
                    case 'available':
                        $query->where('show_on_shop', true);
                        break;
                    case 'out_of_stock':
                        $query->where('show_on_shop', false);
                        break;
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalStocks = Stock::count();
        $activeStocks = Stock::where('is_active', true)->count();
        $availableStocks = Stock::where('show_on_shop', true)->count();
        $outOfStock = Stock::where('show_on_shop', false)->count();
        $totalValue = Stock::sum(\DB::raw('quantity * price'));

        return view('admin.stocks.index-new', compact('stocks', 'totalStocks', 'activeStocks', 'availableStocks', 'outOfStock', 'totalValue'));
    }

    public function addForm()
    {
        $categories = [
            'BIJILI CRACKERS' => '⚡ Bijili Crackers',
            'BOMBS' => '💣 Bombs',
            'CHIT PUT' => '🎆 Chit Put',
            'GIFT BOX' => '🎁 Gift Box',
            'ROCKETS' => '🚀 Rockets',
            'SINGLE FLASH' => '⚡ Single Flash',
            'SPARKLERS' => '✨ Sparklers',
            'TWINKLING STAR' => '⭐ Twinkling Star'
        ];

        return view('admin.stocks.add', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('stocks', 'public');
            }

            $stock = Stock::create([
                'item_name' => $request->item_name,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'original_price' => $request->original_price > 0 ? $request->original_price : null,
                'discount_percentage' => $request->discount_percentage > 0 ? $request->discount_percentage : null,
                'category' => $request->category,
                'is_active' => $request->has('is_active'),
                'show_on_shop' => true, // Default to true for new stocks
                'image' => $imagePath,
                'last_released_at' => now(),
                'next_release_at' => now()->addMinutes(10)
            ]);

            // Log the stock creation
            $stock->logAction('manual', 'Stock created with initial quantity: ' . $request->quantity);

            return redirect()->route('admin.stocks')->with('success', 'Stock added successfully!');
            
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to add stock: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $categories = [
            'BIJILI CRACKERS' => '⚡ Bijili Crackers',
            'BOMBS' => '💣 Bombs',
            'CHIT PUT' => '🎆 Chit Put',
            'GIFT BOX' => '🎁 Gift Box',
            'ROCKETS' => '🚀 Rockets',
            'SINGLE FLASH' => '⚡ Single Flash',
            'SPARKLERS' => '✨ Sparklers',
            'TWINKLING STAR' => '⭐ Twinkling Star'
        ];

        return view('admin.stocks.edit', compact('stock', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        try {
            $data = [
                'item_name' => $request->item_name,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'original_price' => $request->original_price > 0 ? $request->original_price : null,
                'discount_percentage' => $request->discount_percentage > 0 ? $request->discount_percentage : null,
                'category' => $request->category,
                'is_active' => $request->has('is_active')
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($stock->image) {
                    \Storage::disk('public')->delete($stock->image);
                }
                
                $imagePath = $request->file('image')->store('stocks', 'public');
                $data['image'] = $imagePath;
            }

            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image) {
                if ($stock->image) {
                    \Storage::disk('public')->delete($stock->image);
                }
                $data['image'] = null;
            }

            $oldQuantity = $stock->quantity;
            $stock->update($data);

            // Log the stock update if quantity changed
            if ($oldQuantity != $request->quantity) {
                $stock->logAction('manual', "Quantity updated from {$oldQuantity} to {$request->quantity}");
            }

            return redirect()->route('admin.stocks')->with('success', 'Stock updated successfully!');
            
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update stock: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            
            // Log the stock deletion
            $stock->logAction('manual', 'Stock deleted');
            
            // Delete image if exists
            if ($stock->image) {
                \Storage::disk('public')->delete($stock->image);
            }
            
            $stock->delete();
            
            return redirect()->route('admin.stocks')->with('success', 'Stock deleted successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete stock: ' . $e->getMessage());
        }
    }

    public function toggleShowOnShop($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            $stock->show_on_shop = !$stock->show_on_shop;
            $stock->save();
            
            $status = $stock->show_on_shop ? 'enabled' : 'disabled';
            return redirect()->route('admin.stocks')->with('success', "Show on Stock {$status} for {$stock->item_name}!");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to toggle show on stock: ' . $e->getMessage());
        }
    }
} 