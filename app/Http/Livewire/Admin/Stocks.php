<?php

namespace App\Http\Livewire\Admin;

use App\Models\Stock;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class Stocks extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $status_filter = '';
    public $showAddModal = false;
    public $showEditModal = false;
    public $editingStock = null;
    
    public $selected_year = '2025';
    public $available_years = [];

    public function mount()
    {
        $orderYears = \App\Models\Order::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (!in_array(2025, $orderYears)) {
            array_unshift($orderYears, 2025);
        }
        if (!in_array(2026, $orderYears)) {
            array_unshift($orderYears, 2026);
        }

        $this->available_years = array_values(array_unique($orderYears));
    }

    public function exportOrderedItems()
    {
        // Get all stocks with ordered_count > 0
        $stocks = $this->getFilteredStocks()->get();
        $ordered = $stocks->filter(function($stock) {
            return $stock->ordered_count > 0;
        });

        $filename = 'ordered_items_' . date('Y-m-d_H-i-s') . '.csv';
        $csvData = [];
        $csvData[] = ['Product Name', 'Ordered Count'];
        foreach ($ordered as $stock) {
            $csvData[] = [$stock->item_name, $stock->ordered_count];
        }
        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }
        session(['export_csv_content' => $csvContent, 'export_csv_filename' => $filename]);
        $this->dispatch('download-csv');
        session()->flash('success', 'Ordered items export ready! Download will start automatically.');
    }


    
    // Add/Edit form fields
    public $item_name = '';
    public $description = '';
    public $quantity = 0;
    public $price = 0;
    public $original_price = 0;
    public $discount_percentage = 0;
    public $category = '';
    public $is_active = true;
    public $image;
    public $imagePreview;
    public $youtube_url = '';
    
    // Bulk upload properties
    public $showBulkUploadModal = false;
    public $csv_file;
    public $bulk_upload_progress = 0;

    protected $rules = [
        'item_name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'original_price' => 'nullable|numeric|min:0',
        'discount_percentage' => 'nullable|integer|min:0|max:100',
        'category' => 'required|string|max:255',
        'is_active' => 'boolean',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'youtube_url' => 'nullable|string|max:500|url'
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'status_filter']);
        $this->resetPage();
    }

    public function showAddStock()
    {
        $this->resetForm();
        $this->showAddModal = true;
    }

    public function showEditStock($stockId)
    {
        $stock = Stock::find($stockId);
        if (!$stock) {
            session()->flash('error', 'Stock not found.');
            return;
        }

        $this->editingStock = $stock;
        $this->item_name = $stock->item_name;
        $this->description = $stock->description;
        $this->quantity = $stock->quantity;
        $this->price = $stock->price;
        $this->original_price = $stock->original_price;
        $this->discount_percentage = $stock->discount_percentage;
        $this->category = $stock->category;
        $this->is_active = $stock->is_active;
        $this->youtube_url = $stock->youtube_url;
        $this->imagePreview = $stock->image ? asset('storage/' . $stock->image) : null;
        $this->showEditModal = true;
    }

    public function resetForm()
    {
        $this->reset(['item_name', 'description', 'quantity', 'price', 'original_price', 'discount_percentage', 'category', 'is_active', 'image', 'imagePreview', 'youtube_url']);
        $this->editingStock = null;
    }

    public function updatedImage()
    {
        if ($this->image) {
            $this->imagePreview = $this->image->temporaryUrl();
        } else {
            $this->imagePreview = null;
        }
    }

    public function removeImage()
    {
        if ($this->editingStock && $this->editingStock->image) {
            \Storage::disk('public')->delete($this->editingStock->image);
            $this->editingStock->update(['image' => null]);
            $this->imagePreview = null;
        }
        $this->image = null;
        session()->flash('success', 'Image removed successfully!');
    }

    public function addStock()
    {
        $this->validate();

        $data = [
            'item_name' => $this->item_name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'discount_percentage' => $this->discount_percentage,
            'category' => $this->category,
            'is_active' => $this->is_active,
            'youtube_url' => $this->youtube_url,
            'last_released_at' => now(),
            'next_release_at' => now()->addMinutes(10)
        ];

        // Handle image upload
        if ($this->image) {
            $imagePath = $this->image->store('stocks', 'public');
            $data['image'] = $imagePath;
        }

        Stock::create($data);

        $this->showAddModal = false;
        $this->resetForm();
        session()->flash('success', 'Stock added successfully!');
    }

    public function updateStock()
    {
        $this->validate();

        if (!$this->editingStock) {
            session()->flash('error', 'Stock not found.');
            return;
        }

        $data = [
            'item_name' => $this->item_name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'discount_percentage' => $this->discount_percentage,
            'category' => $this->category,
            'is_active' => $this->is_active,
            'youtube_url' => $this->youtube_url
        ];

        // Handle image upload
        if ($this->image) {
            // Delete old image if exists
            if ($this->editingStock->image) {
                \Storage::disk('public')->delete($this->editingStock->image);
            }
            
            $imagePath = $this->image->store('stocks', 'public');
            $data['image'] = $imagePath;
        }

        $this->editingStock->update($data);

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('success', 'Stock updated successfully!');
    }

    public function deleteStock($stockId)
    {
        $stock = Stock::find($stockId);
        if (!$stock) {
            session()->flash('error', 'Stock not found.');
            return;
        }

        // Delete image if exists
        if ($stock->image) {
            \Storage::disk('public')->delete($stock->image);
        }

        $stock->delete();
        session()->flash('success', 'Stock deleted successfully!');
    }

    public function toggleStockStatus($stockId)
    {
        $stock = Stock::find($stockId);
        if (!$stock) {
            session()->flash('error', 'Stock not found.');
            return;
        }

        $stock->update(['is_active' => !$stock->is_active]);
        session()->flash('success', 'Stock status updated successfully!');
    }

    public function manualRelease($stockId)
    {
        $stock = Stock::find($stockId);
        if (!$stock) {
            session()->flash('error', 'Stock not found.');
            return;
        }

        // Release 10 units
        $stock->update([
            'quantity' => $stock->quantity + 10,
            'last_released_at' => now(),
            'next_release_at' => now()->addMinutes(10)
        ]);

        session()->flash('success', 'Stock released manually!');
    }

    public function resetStock($stockId)
    {
        $stock = Stock::find($stockId);
        if (!$stock) {
            session()->flash('error', 'Stock not found.');
            return;
        }

        $stock->update([
            'quantity' => 0,
            'last_released_at' => null,
            'next_release_at' => null
        ]);

        session()->flash('success', 'Stock reset successfully!');
    }

    public function autoReleaseStocks()
    {
        $stocksToRelease = Stock::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('next_release_at')
                      ->orWhere('next_release_at', '<=', now());
            })
            ->get();

        foreach ($stocksToRelease as $stock) {
            $stock->update([
                'quantity' => $stock->quantity + 10,
                'last_released_at' => now(),
                'next_release_at' => now()->addMinutes(10)
            ]);
        }

        session()->flash('success', count($stocksToRelease) . ' stocks auto-released!');
    }

    public function autoExpireStocks()
    {
        $stocksToExpire = Stock::where('is_active', true)
            ->where('last_released_at', '<=', now()->subMinutes(10))
            ->where('quantity', '>', 0)
            ->get();

        foreach ($stocksToExpire as $stock) {
            $stock->update([
                'quantity' => 0,
                'last_released_at' => null,
                'next_release_at' => null
            ]);
        }

        session()->flash('success', count($stocksToExpire) . ' stocks auto-expired!');
    }

    public function exportStocks()
    {
        $stocks = $this->getFilteredStocks();
        
        $filename = 'stocks_' . date('Y-m-d_H-i-s') . '.csv';
        
        $csvData = [];
        
        // CSV headers
        $csvData[] = [
            'item_name', 'category', 'description', 'quantity', 'price', 
            'original_price', 'discount_percentage', 'is_active'
        ];

        foreach ($stocks as $stock) {
            $csvData[] = [
                $stock->item_name,
                $stock->category,
                $stock->description ?? '',
                $stock->quantity,
                $stock->price,
                $stock->original_price ?? '',
                $stock->discount_percentage ?? '',
                $stock->is_active ? '1' : '0'
            ];
        }

        // Convert to CSV string
        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }

        // Store CSV content in session for download
        session(['export_csv_content' => $csvContent, 'export_csv_filename' => $filename]);
        
        // Dispatch download event
        $this->dispatch('download-csv');
        
        session()->flash('success', 'Export ready! Download will start automatically.');
    }

    public function showBulkUpload()
    {
        $this->showBulkUploadModal = true;
    }

    public function downloadTemplate()
    {
        $filename = 'stock_upload_template.csv';
        
        $csvData = [
            ['item_name', 'category', 'description', 'quantity', 'price', 'original_price', 'discount_percentage', 'is_active'],
            ['Sample Product', 'BOMBS', 'Sample description', '100', '50.00', '100.00', '50', '1'],
            ['Another Product', 'SPARKLERS', 'Another description', '200', '25.00', '', '', '1']
        ];
        
        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }

        session(['export_csv_content' => $csvContent, 'export_csv_filename' => $filename]);
        $this->dispatch('download-csv');
        
        session()->flash('success', 'Template downloaded! Use this format for bulk upload.');
    }

    public function bulkUpload()
    {
        $this->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        try {
            $path = $this->csv_file->getRealPath();
            $file = fopen($path, 'r');
            
            // Skip header row
            $header = fgetcsv($file);
            
            $imported = 0;
            $errors = [];
            
            while (($row = fgetcsv($file)) !== false) {
                try {
                    $data = array_combine($header, $row);
                    
                    // Validate required fields
                    if (empty($data['item_name']) || empty($data['category']) || empty($data['quantity']) || empty($data['price'])) {
                        $errors[] = "Row " . ($imported + 2) . ": Missing required fields";
                        continue;
                    }

                    Stock::create([
                        'item_name' => trim($data['item_name']),
                        'category' => trim($data['category']),
                        'description' => trim($data['description'] ?? ''),
                        'quantity' => (int) $data['quantity'],
                        'price' => (float) $data['price'],
                        'original_price' => !empty($data['original_price']) ? (float) $data['original_price'] : null,
                        'discount_percentage' => !empty($data['discount_percentage']) ? (int) $data['discount_percentage'] : null,
                        'is_active' => $data['is_active'] == '1',
                        'last_released_at' => now(),
                        'next_release_at' => now()->addMinutes(10)
                    ]);
                    
                    $imported++;
                    $this->bulk_upload_progress = ($imported / 100) * 100; // Assuming max 100 rows
                    
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($imported + 2) . ": " . $e->getMessage();
                }
            }
            
            fclose($file);
            
            $this->showBulkUploadModal = false;
            $this->reset(['csv_file', 'bulk_upload_progress']);
            
            if ($imported > 0) {
                session()->flash('success', "Successfully imported {$imported} stocks!");
            }
            
            if (!empty($errors)) {
                session()->flash('error', 'Some rows failed to import: ' . implode(', ', array_slice($errors, 0, 5)));
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function toggleShowOnShop($stockId)
    {
        $stock = Stock::findOrFail($stockId);
        $stock->show_on_shop = !$stock->show_on_shop;
        $stock->save();
        session()->flash('success', 'Show on Shop status updated!');
    }

    private function getFilteredStocks()
    {
        $query = Stock::query()->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('item_name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status_filter && $this->status_filter !== '') {
            if ($this->status_filter === 'active') {
                $query->where('is_active', true);
            } elseif ($this->status_filter === 'inactive') {
                $query->where('is_active', false);
            } elseif ($this->status_filter === 'available') {
                $query->where('quantity', '>', 0);
            } elseif ($this->status_filter === 'out_of_stock') {
                $query->where('quantity', 0);
            }
        }

        return $query;
    }

    public function render()
    {
        $stocks = $this->getFilteredStocks()->paginate(10);

        // Pre-calculate full active stocks serial mapping to match price list catalog serials
        $allActiveCats = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get();
        $allActiveStocks = \App\Models\Stock::where('is_active', true)->get()->groupBy('category');
        $catalogSnoMap = [];
        $snoCounter = 0;
        foreach ($allActiveCats as $cat) {
            $catStocks = $allActiveStocks->get($cat->name) ?? $allActiveStocks->get($cat->id) ?? collect();
            foreach ($catStocks->sortBy('order_within_category') as $stockItem) {
                $snoCounter++;
                $catalogSnoMap[$stockItem->id] = $snoCounter;
            }
        }

        // Add order count for each stock (excluding 'pending' orders)
        foreach ($stocks as $stock) {
            $jsonCount = \App\Models\Order::whereIn('status', ['confirmed', 'dispatched', 'cancelled'])
                ->whereNotNull('items_json')
                ->when($this->selected_year, function($q) {
                    $q->whereYear('created_at', $this->selected_year);
                })
                ->get()
                ->sum(function($order) use ($stock) {
                    $items = $order->items_json ?? [];
                    return collect($items)
                        ->sum(function($item) use ($stock) {
                            $productName = $item['product_name'] ?? '';
                            $decodedProductName = html_entity_decode($productName, ENT_QUOTES, 'UTF-8');
                            $quantity = (int)($item['quantity'] ?? 1);
                            if ($decodedProductName === $stock->item_name) {
                                  return $quantity;
                            }
                            $partialMatches = \App\Models\Stock::where('item_name', 'LIKE', '%' . $decodedProductName . '%')->get();
                            if ($partialMatches->count() === 1 && $partialMatches->first()->id === $stock->id) {
                                return $quantity;
                            }
                            return 0;
                        });
                });
            $orderedCount = \App\Models\OrderItem::where('stock_id', $stock->id)
                ->whereHas('order', function($q) {
                    $q->whereIn('status', ['confirmed', 'dispatched', 'cancelled']);
                    if ($this->selected_year) {
                        $q->whereYear('created_at', $this->selected_year);
                    }
                })
                ->sum('quantity');
            if ($orderedCount == 0) {
                $orderedCount = \App\Models\OrderItem::where('product_name', $stock->item_name)
                    ->whereHas('order', function($q) {
                        $q->whereIn('status', ['confirmed', 'dispatched', 'cancelled']);
                        if ($this->selected_year) {
                            $q->whereYear('created_at', $this->selected_year);
                        }
                    })
                    ->sum('quantity');
            }
            $stock->ordered_count = $jsonCount + $orderedCount;
        }

        return view('livewire.admin.stocks', [
            'stocks' => $stocks,
            'totalStocks' => Stock::count(),
            'activeStocks' => Stock::where('is_active', true)->count(),
            'availableStocks' => Stock::where('quantity', '>', 0)->count(),
            'outOfStock' => Stock::where('quantity', 0)->count(),
            'totalValue' => Stock::sum(\DB::raw('quantity * price')),
            'catalogSnoMap' => $catalogSnoMap,
        ]);
    }
} 