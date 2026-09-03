<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StockController extends Controller
{
    /**
     * Toggle the is_active status of a stock item
     */
    public function toggleStockStatus($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            $stock->is_active = !$stock->is_active;
            $stock->save();
            $status = $stock->is_active ? 'activated' : 'deactivated';
            return redirect()->route('admin.stocks')->with('success', "Stock {$status} for {$stock->item_name}!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to toggle stock status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk deactivate all stocks for a specific year or all-time
     */
    public function bulkDeactivateYear(Request $request)
    {
        try {
            $year = $request->input('year');
            $query = Stock::query();
            if ($year && $year !== 'all') {
                $query->whereYear('created_at', $year);
                $msgYear = "created in year {$year}";
            } else {
                $msgYear = "all-time";
            }
            $count = $query->update(['is_active' => false]);
            return redirect()->back()->with('success', "Deactivated {$count} stock items ({$msgYear})!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to bulk deactivate stocks: ' . $e->getMessage());
        }
    }

    /**
     * Bulk hide all stocks from shop for a specific year or all-time
     */
    public function bulkHideYear(Request $request)
    {
        try {
            $year = $request->input('year');
            $query = Stock::query();
            if ($year && $year !== 'all') {
                $query->whereYear('created_at', $year);
                $msgYear = "for year {$year}";
            } else {
                $msgYear = "all-time";
            }
            $count = $query->update(['show_on_shop' => false]);
            return redirect()->back()->with('success', "Hidden {$count} stock items from shop ({$msgYear})!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to bulk hide stocks: ' . $e->getMessage());
        }
    }

    /**
     * Bulk activate all stocks for a specific year or all-time
     */
    public function bulkActivateYear(Request $request)
    {
        try {
            $year = $request->input('year');
            $query = Stock::query();
            if ($year && $year !== 'all') {
                $query->whereYear('created_at', $year);
                $msgYear = "created in year {$year}";
            } else {
                $msgYear = "all-time";
            }
            $count = $query->update(['is_active' => true]);
            return redirect()->back()->with('success', "Activated {$count} stock items ({$msgYear})!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to bulk activate stocks: ' . $e->getMessage());
        }
    }

    /**
     * Bulk show/unhide all stocks on shop for a specific year or all-time
     */
    public function bulkShowYear(Request $request)
    {
        try {
            $year = $request->input('year');
            $query = Stock::query();
            if ($year && $year !== 'all') {
                $query->whereYear('created_at', $year);
                $msgYear = "for year {$year}";
            } else {
                $msgYear = "all-time";
            }
            $count = $query->update(['show_on_shop' => true]);
            return redirect()->back()->with('success', "Set {$count} stock items visible on shop ({$msgYear})!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to bulk show stocks: ' . $e->getMessage());
        }
    }
    /**
     * Export ordered items (product name, category, and count) as CSV based on selected year
     */
    public function exportOrderedItems(Request $request)
    {
        $selectedYear = $request->has('selected_year') ? $request->input('selected_year') : ($request->has('year') ? $request->input('year') : (string)date('Y'));

        // Query non-cancelled and non-pending orders for selected year
        $ordersQuery = \App\Models\Order::query()->whereNotIn('status', ['cancelled', 'pending']);
        if ($selectedYear !== '' && $selectedYear !== null && $selectedYear !== 'all') {
            $ordersQuery->whereYear('created_at', $selectedYear);
        }
        $orders = $ordersQuery->get();

        $countByStockId = [];
        $countByName = [];

        foreach ($orders as $ord) {
            $items = is_array($ord->items_json) ? $ord->items_json : json_decode($ord->items_json ?? '[]', true);
            if (is_array($items) && count($items) > 0) {
                foreach ($items as $item) {
                    $pId = $item['product_id'] ?? $item['stock_id'] ?? null;
                    $name = $item['product_name'] ?? $item['item_name'] ?? null;
                    $qty = (int)($item['quantity'] ?? 0);
                    if ($qty > 0) {
                        if ($pId) {
                            $countByStockId[$pId] = ($countByStockId[$pId] ?? 0) + $qty;
                        }
                        if ($name) {
                            $countByName[$name] = ($countByName[$name] ?? 0) + $qty;
                        }
                    }
                }
            } else {
                foreach ($ord->items as $oi) {
                    $qty = (int)($oi->quantity ?? 0);
                    if ($qty > 0) {
                        if ($oi->stock_id) {
                            $countByStockId[$oi->stock_id] = ($countByStockId[$oi->stock_id] ?? 0) + $qty;
                        }
                        if ($oi->product_name) {
                            $countByName[$oi->product_name] = ($countByName[$oi->product_name] ?? 0) + $qty;
                        }
                    }
                }
            }
        }

        $stocksQuery = Stock::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $stocksQuery->where(function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status_filter')) {
            $status = $request->input('status_filter');
            switch($status) {
                case 'active':
                    $stocksQuery->where('is_active', true);
                    break;
                case 'inactive':
                    $stocksQuery->where('is_active', false);
                    break;
                case 'available':
                    $stocksQuery->where('show_on_shop', true);
                    break;
                case 'out_of_stock':
                    $stocksQuery->where('show_on_shop', false);
                    break;
            }
        }

        $stocks = $stocksQuery->get();

        $filename = 'ordered_items_' . ($selectedYear && $selectedYear !== 'all' ? "year_{$selectedYear}_" : "all_") . date('Y-m-d_H-i-s') . '.csv';

        $csv = "Product Name,Category,Ordered Count\n";
        $processedNames = [];
        foreach ($stocks as $stock) {
            $count = $countByStockId[$stock->id] ?? $countByName[$stock->item_name] ?? 0;
            if ($count > 0) {
                $csv .= '"' . str_replace('"', '""', $stock->item_name) . '",'
                      . '"' . str_replace('"', '""', $stock->category) . '",'
                      . $count . "\n";
                $processedNames[$stock->item_name] = true;
            }
        }

        // Add any ordered items whose name was not matched to a Stock record
        foreach ($countByName as $name => $cnt) {
            if ($cnt > 0 && !isset($processedNames[$name])) {
                $csv .= '"' . str_replace('"', '""', $name) . '",'
                      . '"Other",'
                      . $cnt . "\n";
            }
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
    public function index()
    {
        // Fetch all categories in order so stock management displays all items regardless of category active toggle
        $categories = Category::ordered()->get();
        
        // Auto-sync any stocks belonging to currently inactive categories to is_active=false, show_on_shop=false
        $inactiveCats = Category::where('is_active', false)->get(['id', 'name']);
        if ($inactiveCats->isNotEmpty()) {
            $inactiveNames = $inactiveCats->pluck('name')->toArray();
            $inactiveIds = $inactiveCats->pluck('id')->toArray();
            Stock::where(function($q) use ($inactiveNames, $inactiveIds) {
                $q->whereIn('category', $inactiveNames)
                  ->orWhereIn('category_id', $inactiveIds);
            })->where('is_active', true)->update([
                'is_active' => false,
                'show_on_shop' => false
            ]);
        }

        // Available years from orders
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
        $availableYears = array_values(array_unique($orderYears));
        rsort($availableYears);

        // Default selected year to current year (2026) if not provided in request
        $selectedYear = request()->has('selected_year') ? request('selected_year') : (string)date('Y');

        // Ensure baseline lifetime ordered_counts are synced
        Stock::recalculateOrderedCounts();

        // Calculate ordered count map per product for the selected year (confirmed, non-cancelled orders)
        $ordersQuery = \App\Models\Order::query()->whereNotIn('status', ['cancelled', 'pending']);
        if ($selectedYear !== '' && $selectedYear !== null) {
            $ordersQuery->whereYear('created_at', $selectedYear);
        }
        $orders = $ordersQuery->get(['items_json']);

        $orderedCountsMap = [];
        foreach ($orders as $ord) {
            $items = is_array($ord->items_json) ? $ord->items_json : json_decode($ord->items_json ?? '[]', true);
            if (is_array($items)) {
                foreach ($items as $item) {
                    $productId = $item['product_id'] ?? $item['stock_id'] ?? null;
                    $qty = (int)($item['quantity'] ?? 0);
                    if ($productId && $qty > 0) {
                        $orderedCountsMap[$productId] = ($orderedCountsMap[$productId] ?? 0) + $qty;
                    }
                }
            }
        }
        
        // Fetch all stocks, filtered by search, status, and selected creation year
        $stocksQuery = Stock::query()
            ->select('*')
            ->when($selectedYear !== '' && $selectedYear !== null, function($query) use ($selectedYear) {
                $query->whereYear('created_at', $selectedYear);
            })
            ->when(request('search'), function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('item_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('youtube_url', 'like', "%{$search}%");
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
            });
            
        $stocks = $stocksQuery->get();

        // Attach year-specific ordered count to each stock object
        foreach ($stocks as $stock) {
            if ($selectedYear !== '' && $selectedYear !== null) {
                $stock->display_ordered_count = $orderedCountsMap[$stock->id] ?? 0;
            } else {
                $stock->display_ordered_count = $stock->ordered_count;
            }
        }

        // Custom sort: released first (oldest to newest), then unreleased (null or future last_released_at, by created_at)
        $stocksByCategory = $stocks->groupBy('category')->map(function($group) {
            // Released: last_released_at in the past
            $released = $group->filter(function($item) {
                return $item->last_released_at && $item->last_released_at <= now();
            })->sortBy('last_released_at');
            // Unreleased: last_released_at null or in the future
            $unreleased = $group->filter(function($item) {
                return !$item->last_released_at || $item->last_released_at > now();
            })->sortBy('created_at');
            return $released->concat($unreleased)->values();
        });

        $statsQuery = Stock::query()
            ->when($selectedYear !== '' && $selectedYear !== null, function($query) use ($selectedYear) {
                $query->whereYear('created_at', $selectedYear);
            });

        $totalStocks = (clone $statsQuery)->count();
        $activeStocks = (clone $statsQuery)->where('is_active', true)->count();
        $availableStocks = (clone $statsQuery)->where('show_on_shop', true)->count();
        $outOfStock = (clone $statsQuery)->where('show_on_shop', false)->count();
        $totalValue = (clone $statsQuery)->sum(\DB::raw('quantity * price'));

        return view('admin.stocks.index-new', compact(
            'categories', 'stocksByCategory', 'totalStocks', 'activeStocks', 
            'availableStocks', 'outOfStock', 'totalValue', 'availableYears', 'selectedYear'
        ));
    }

    public function addForm()
    {
        // Sync any category names from stocks table that are missing in categories table
        $this->syncMissingCategories();

        // Fetch categories from the database
        $categories = \App\Models\Category::active()->ordered()->pluck('name', 'id');
        return view('admin.stocks.add', compact('categories'));
    }

    public function edit($id)
    {
        $this->syncMissingCategories();
        $stock = Stock::findOrFail($id);
        // Fetch categories from the database
        $categories = \App\Models\Category::active()->ordered()->pluck('name', 'id');
        return view('admin.stocks.edit', compact('stock', 'categories'));
    }

    /**
     * Automatically synchronize distinct categories from stocks table to categories table
     */
    public function syncMissingCategories()
    {
        try {
            $distinctCategories = \App\Models\Stock::whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->pluck('category');

            foreach ($distinctCategories as $catName) {
                $trimmed = trim($catName);
                if ($trimmed === '') continue;

                $cat = \App\Models\Category::findOrCreateByName($trimmed);
                if ($cat) {
                    \App\Models\Stock::where('category', $trimmed)
                        ->whereNull('category_id')
                        ->update(['category_id' => $cat->id]);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Category sync notice: ' . $e->getMessage());
        }
    }
//create stock function
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'special_discount_percentage' => 'nullable|integer|min:0|max:100',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'youtube_url' => 'nullable|string|max:255' //newly added
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('stocks', 'public');
                Stock::syncUploadedFile($imagePath);
            }

            // Get category name from ID or name safely
            $category = Category::findOrCreateByName($request->category);

            $stock = Stock::create([
                'item_name' => $request->item_name,
                'description' => $request->description,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'original_price' => $request->original_price > 0 ? $request->original_price : null,
                'discount_percentage' => $request->discount_percentage > 0 ? $request->discount_percentage : null,
                'special_discount_percentage' => $request->special_discount_percentage > 0 ? $request->special_discount_percentage : null,
                'category' => $category ? $category->name : trim($request->category),
                'category_id' => $category ? $category->id : null,
                'is_active' => $request->has('is_active'),
                'show_on_shop' => true, // Default to true for new stocks
                'image' => $imagePath,
                'youtube_url' => $request->youtube_url, 
                'last_released_at' => now(),
                'next_release_at' => now()->addMinutes(10)
            ]);

            // Log the stock creation
            $stock->logAction('manual', 'Stock created with initial quantity: ' . $request->quantity);

            return redirect()->route('admin.stocks')->with('success', 'Stock created successfully!');
            
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create stock: ' . $e->getMessage());
        }
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
            'special_discount_percentage' => 'nullable|integer|min:0|max:100',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'youtube_url' => 'nullable|string|max:255' //newly added
        ]);

        try {
            // Get category name from ID or name safely
            $category = Category::findOrCreateByName($request->category);

            $data = [
                'item_name' => $request->item_name,
                'description' => $request->description,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'original_price' => $request->original_price > 0 ? $request->original_price : null,
                'discount_percentage' => $request->discount_percentage > 0 ? $request->discount_percentage : null,
                'special_discount_percentage' => $request->special_discount_percentage > 0 ? $request->special_discount_percentage : null,
                'category' => $category ? $category->name : trim($request->category),
                'category_id' => $category ? $category->id : null,
                'is_active' => $request->has('is_active'),
                'youtube_url' => $request->youtube_url
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($stock->image) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($stock->image);
                }
                
                $data['image'] = $request->file('image')->store('stocks', 'public');
                Stock::syncUploadedFile($data['image']);
            }

            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image) {
                if ($stock->image) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($stock->image);
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
    
    public function toggleShowOnHome($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            $stock->show_on_home = !$stock->show_on_home;
            $stock->save();
            $status = $stock->show_on_home ? 'enabled' : 'disabled';
            return redirect()->route('admin.stocks')->with('success', "Show on Home {$status} for {$stock->item_name}!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to toggle show on home: ' . $e->getMessage());
        }
    }
    
    
    // bulk upload stock data  starts here
    //newly created copy this
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240'
        ]);

        try {
            $file = $request->file('csv_file');
            $extension = strtolower($file->getClientOriginalExtension());
            
            $imported = 0;
            $errors = [];
            $skipped = 0;
            
            if (in_array($extension, ['xlsx', 'xls'])) {
                // Handle Excel files
                $data = $this->readExcelFile($file);
                
                // Process Excel data
                foreach ($data as $index => $rawRow) {
                    try {
                        $rowData = $this->normalizeRowKeys($rawRow);

                        // Skip if all values in the row are empty or whitespace
                        $hasContent = false;
                        foreach ($rowData as $val) {
                            if ($val !== null && trim((string)$val) !== '') {
                                $hasContent = true;
                                break;
                            }
                        }
                        if (!$hasContent) {
                            $skipped++;
                            continue;
                        }

                        // Skip empty rows where both item_name and category are blank
                        if (empty(trim((string)($rowData['item_name'] ?? ''))) && empty(trim((string)($rowData['category'] ?? '')))) {
                            $skipped++;
                            continue;
                        }

                        // Validate required fields
                        if (empty(trim((string)($rowData['item_name'] ?? ''))) || empty(trim((string)($rowData['category'] ?? '')))) {
                            $errors[] = "Row " . ($index + 2) . ": Missing required fields (item_name, category)";
                            continue;
                        }

                        // Process data with category auto-creation
                        $processedData = $this->processRowData($rowData, $index + 2);
                        if ($processedData['error']) {
                            $errors[] = $processedData['error'];
                            continue;
                        }

                        Stock::create($processedData['data']);
                        $imported++;
                        
                    } catch (\Exception $e) {
                        $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                    }
                }
            } else {
                // Handle CSV files with enhanced parsing
                $rows = $this->readCsvFileEnhanced($file);
                
                if (empty($rows)) {
                    throw new \Exception('No data found in CSV file.');
                }
                
                // First row is the header
                $rawHeaders = array_shift($rows);
                $normalizedHeaders = $this->normalizeHeaderList($rawHeaders);
                
                foreach ($rows as $index => $row) {
                    try {
                        // Skip empty rows
                        if (empty(array_filter($row, fn($v) => trim((string)$v) !== ''))) {
                            $skipped++;
                            continue;
                        }

                        // Match columns with headers
                        $headerCount = count($normalizedHeaders);
                        $rowCount = count($row);
                        if ($rowCount < $headerCount) {
                            $row = array_pad($row, $headerCount, '');
                        } elseif ($rowCount > $headerCount) {
                            $row = array_slice($row, 0, $headerCount);
                        }

                        $rowData = array_combine($normalizedHeaders, $row);
                        
                        // Skip if both item_name and category are empty
                        if (empty(trim((string)($rowData['item_name'] ?? ''))) && empty(trim((string)($rowData['category'] ?? '')))) {
                            $skipped++;
                            continue;
                        }

                        // Validate required fields
                        if (empty(trim((string)($rowData['item_name'] ?? ''))) || empty(trim((string)($rowData['category'] ?? '')))) {
                            $errors[] = "Row " . ($index + 2) . ": Missing required fields (item_name, category)";
                            continue;
                        }

                        // Process data with category auto-creation
                        $processedData = $this->processRowData($rowData, $index + 2);
                        if ($processedData['error']) {
                            $errors[] = $processedData['error'];
                            continue;
                        }

                        Stock::create($processedData['data']);
                        $imported++;
                        
                    } catch (\Exception $e) {
                        $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                    }
                }
            }
            
            // Sync all categories once after batch import completes
            $this->syncMissingCategories();
            
            $message = "Import completed! ";
            if ($imported > 0) {
                $message .= "Successfully imported {$imported} stocks. ";
            }
            if ($skipped > 0) {
                $message .= "Skipped {$skipped} empty rows. ";
            }
            
            if ($imported > 0) {
                session()->flash('success', $message);
            }
            
            if (!empty($errors)) {
                $errorMessage = 'Some rows failed to import: ' . implode(' | ', array_slice($errors, 0, 10));
                if (count($errors) > 10) {
                    $errorMessage .= ' and ' . (count($errors) - 10) . ' more errors.';
                }
                session()->flash('error', $errorMessage);
            }
            
            if ($imported === 0 && empty($errors)) {
                session()->flash('warning', 'No valid data found to import.');
            }

            return redirect()->route('admin.stocks');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Process row data with auto-category creation and field mapping
     */
    private function processRowData($rowData, $rowNumber)
    {
        try {
            $rawCategory = trim((string)($rowData['category'] ?? ''));
            $categoryId = null;
            $categoryName = $rawCategory;

            // Automatically find or create Category safely using Category::findOrCreateByName
            if (!empty($rawCategory)) {
                $catModel = \App\Models\Category::findOrCreateByName($rawCategory);
                if ($catModel) {
                    $categoryId = $catModel->id;
                    $categoryName = $catModel->name;
                }
            }

            $price = $this->parseNumeric($rowData['price'] ?? null, 'float');
            $originalPrice = $this->parseNumeric($rowData['original_price'] ?? null, 'float');
            $discount = $this->parseNumeric($rowData['discount_percentage'] ?? null, 'int');
            $specialDiscount = $this->parseNumeric($rowData['special_discount_percentage'] ?? null, 'int');

            // If price is missing or 0, but original_price is present, calculate price
            if (($price === null || $price <= 0) && $originalPrice !== null && $originalPrice > 0) {
                $calc = $originalPrice;
                if ($discount && $discount > 0) {
                    $calc = $calc * (1 - ($discount / 100));
                }
                if ($specialDiscount && $specialDiscount > 0) {
                    $calc = $calc * (1 - ($specialDiscount / 100));
                }
                $price = round($calc, 2);
            }

            // If original_price is missing or 0, but price is present, set original_price
            if (($originalPrice === null || $originalPrice <= 0) && $price !== null && $price > 0) {
                if ($discount && $discount > 0) {
                    $originalPrice = round($price / (1 - ($discount / 100)), 2);
                } else {
                    $originalPrice = $price;
                }
            }

            // Enhanced data processing
            $data = [
                'item_name' => trim((string)($rowData['item_name'] ?? '')),
                'category' => $categoryName,
                'category_id' => $categoryId,
                'description' => trim((string)($rowData['description'] ?? '')),
                'meta_title' => !empty($rowData['meta_title']) ? trim((string)$rowData['meta_title']) : null,
                'meta_description' => !empty($rowData['meta_description']) ? trim((string)$rowData['meta_description']) : null,
                'meta_keywords' => !empty($rowData['meta_keywords']) ? trim((string)$rowData['meta_keywords']) : null,
                'quantity' => $this->parseNumeric($rowData['quantity'] ?? 0, 'int', 0),
                'price' => $price ?? 0,
                'original_price' => $originalPrice,
                'discount_percentage' => $discount,
                'special_discount_percentage' => $specialDiscount,
                'is_active' => $this->parseBoolean($rowData['is_active'] ?? 1),
                'show_on_shop' => $this->parseBoolean($rowData['show_on_shop'] ?? 1),
                'is_popular' => $this->parseBoolean($rowData['is_popular'] ?? 0),
                'is_latest' => $this->parseBoolean($rowData['is_latest'] ?? 0),
                'expires_at' => $this->parseDateTime($rowData['expires_at'] ?? null),
                'ordered_count' => $this->parseNumeric($rowData['ordered_count'] ?? 0, 'int', 0),
                'last_released_at' => $this->parseDateTime($rowData['last_released_at'] ?? null) ?: now(),
                'next_release_at' => $this->parseDateTime($rowData['next_release_at'] ?? null) ?: now()->addMinutes(10),
                'youtube_url' => trim((string)($rowData['youtube_url'] ?? '')),
                'image' => trim((string)($rowData['image'] ?? ''))
            ];

            // Additional validation
            if ($data['price'] <= 0) {
                return ['error' => "Row {$rowNumber}: Price must be greater than 0", 'data' => null];
            }

            if ($data['quantity'] < 0) {
                return ['error' => "Row {$rowNumber}: Quantity cannot be negative", 'data' => null];
            }

            return ['error' => null, 'data' => $data];

        } catch (\Exception $e) {
            return ['error' => "Row {$rowNumber}: Data processing error - " . $e->getMessage(), 'data' => null];
        }
    }

    /**
     * Enhanced CSV reading with BOM removal and flexible line reading
     */
    private function readCsvFileEnhanced($file)
    {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        
        if ($content === false || trim($content) === '') {
            return [];
        }

        // Strip UTF-8 BOM if present
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }
        $content = preg_replace('/^\x{FEFF}/u', '', $content);

        // Auto-detect delimiter (, or ;)
        $firstLine = strtok($content, "\r\n");
        $delimiter = ',';
        if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
            $delimiter = ';';
        }

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        $data = [];
        while (($row = fgetcsv($stream, 0, $delimiter, '"')) !== false) {
            $cleanRow = array_map(function($field) {
                return trim((string)$field);
            }, $row);
            $data[] = $cleanRow;
        }
        fclose($stream);

        return $data;
    }

    /**
     * Normalize an array of header names
     */
    private function normalizeHeaderList(array $headers): array
    {
        return array_map(function($h) {
            // Strip any BOM or invisible chars
            $clean = preg_replace('/^[\xEF\xBB\xBF\x{FEFF}\x{200B}]+/u', '', trim((string)$h));
            $clean = strtolower(trim(str_replace([' ', '-', '/'], '_', $clean)));

            $aliases = [
                'item_name' => ['item_name', 'name', 'product_name', 'product', 'item'],
                'category' => ['category', 'cat', 'category_name'],
                'description' => ['description', 'desc', 'packing', 'packaging', 'unit'],
                'meta_title' => ['meta_title', 'seo_title', 'title_tag', 'meta_title_tag'],
                'meta_description' => ['meta_description', 'seo_description', 'meta_desc', 'meta_tag_description'],
                'meta_keywords' => ['meta_keywords', 'keywords', 'seo_keywords', 'tags'],
                'quantity' => ['quantity', 'qty', 'stock', 'available_qty'],
                'price' => ['price', 'rate', 'unit_price', 'selling_price', 'our_price'],
                'original_price' => ['original_price', 'mrp', 'orig_price', 'actual_price'],
                'discount_percentage' => ['discount_percentage', 'discount', 'discount_%', 'disc_%', 'disc_percent'],
                'special_discount_percentage' => ['special_discount_percentage', 'special_discount', 'special_discount_%', 'special_%'],
                'is_active' => ['is_active', 'active', 'status'],
                'show_on_shop' => ['show_on_shop', 'show_shop', 'available'],
                'is_popular' => ['is_popular', 'popular'],
                'is_latest' => ['is_latest', 'latest'],
                'youtube_url' => ['youtube_url', 'youtube', 'video_url', 'video'],
                'image' => ['image', 'image_url', 'photo']
            ];

            foreach ($aliases as $standard => $aliasList) {
                if (in_array($clean, $aliasList)) {
                    return $standard;
                }
            }

            return $clean;
        }, $headers);
    }

    /**
     * Normalize key names for associative rows (e.g. from Excel)
     */
    private function normalizeRowKeys(array $row): array
    {
        $normalized = [];
        $headerMap = $this->normalizeHeaderList(array_keys($row));
        $values = array_values($row);

        foreach ($headerMap as $index => $standardKey) {
            $normalized[$standardKey] = $values[$index] ?? null;
        }

        return $normalized;
    }

    /**
     * Parse numeric values with proper handling
     */
    private function parseNumeric($value, $type = 'float', $default = null)
    {
        if ($value === null || $value === '') {
            return $default;
        }
        
        $str = trim((string)$value);
        if ($str === '') {
            return $default;
        }

        // Remove currency symbols, commas, and other non-numeric chars except digits, minus, and dot
        $cleaned = preg_replace('/[^\d.-]/', '', $str);
        
        if ($cleaned === '' || $cleaned === '-' || $cleaned === '.') {
            return $default;
        }
        
        if ($type === 'int') {
            return (int) round((float) $cleaned);
        }
        
        return (float) $cleaned;
    }

    /**
     * Parse boolean values
     */
    private function parseBoolean($value)
    {
        if (is_bool($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['1', 'true', 'yes', 'on']);
        }
        
        return (bool) $value;
    }

    /**
     * Parse datetime values
     */
    private function parseDateTime($value)
    {
        if (empty($value) || $value === '') {
            return null;
        }
        
        try {
            return \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }    public function previewImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048'
        ]);

        try {
            $file = $request->file('csv_file');
            $extension = $file->getClientOriginalExtension();
            
            $previewData = [];
            $headers = [];
            
            if (in_array($extension, ['xlsx', 'xls'])) {
                // Handle Excel files
                $data = $this->readExcelFile($file);
                
                if (!empty($data)) {
                    $headers = array_keys($data[0]);
                    // Take first 10 rows for preview
                    $previewData = array_slice($data, 0, 10);
                }
            } else {
                // Handle CSV files
                $data = $this->readCsvFile($file);
                
                if (!empty($data)) {
                    $headers = $data[0];
                    // Take first 10 rows for preview (skip header)
                    $previewData = array_slice($data, 1, 10);
                }
            }
            
            return response()->json([
                'success' => true,
                'headers' => $headers,
                'preview_data' => $previewData,
                'total_rows' => count($data) - 1, // Exclude header
                'filename' => $file->getClientOriginalName()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to read file: ' . $e->getMessage()
            ], 400);
        }
    }

    private function readCsvFile($file)
    {
        $path = $file->getRealPath();
        $data = [];
        
        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }
        
        return $data;
    }

    private function readExcelFile($file)
    {
        try {
            // Use Maatwebsite Excel to read the file
            $data = Excel::toArray(new class implements ToArray, WithHeadingRow {
                public function array(array $array)
                {
                    return $array;
                }
            }, $file);
            
            // The first sheet data
            return $data[0] ?? [];
            
        } catch (\Exception $e) {
            throw new \Exception('Failed to read Excel file: ' . $e->getMessage());
        }
    }


    // sample data template for bulk upload
    //function  added , ui not added for now
    public function downloadTemplate(Request $request)
    {
        $format = $request->get('format', 'csv'); // Default to CSV
        
        $data = [
            // Headers
            ['item_name', 'category', 'description', 'meta_title', 'meta_description', 'meta_keywords', 'quantity', 'price', 'original_price', 'discount_percentage', 'special_discount_percentage', 'is_active', 'show_on_shop', 'is_popular', 'is_latest', 'expires_at', 'ordered_count', 'last_released_at', 'next_release_at', 'youtube_url', 'image'],
            
            // Sample data rows matching your CSV structure
            ['4" Gold Lakshmi', 'SINGLE FLASH', '1 Pkt/5 Pcs', 'Buy 4" Gold Lakshmi Online - Sivakasi Crackers', 'Premium 4 inch Gold Lakshmi single flash crackers at wholesale prices.', 'gold lakshmi, single flash, diwali crackers', '', '31', '120', '70', '15', '1', '0', '0', '0', '', '', '', '', '', ''],
            ['2 3/4" Kuruvi', 'SINGLE FLASH', '1 Pkt/5 Pcs', 'Buy 2 3/4" Kuruvi Crackers Online', 'Best quality traditional 2 3/4 Kuruvi crackers from Sivakasi.', 'kuruvi crackers, single flash', '', '7', '28', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['4" Lakshmi', 'SINGLE FLASH', '1 Pkt/5 Pcs', '', '', '', '', '15', '60', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['Red Bijili', 'BIJILI CRACKERS', '1 Pkt/50 Pcs', 'Red Bijili Crackers 50 Pcs Pack', 'Authentic Red Bijili sound crackers pack for Diwali celebrations.', 'red bijili, bijili crackers', '', '18', '72', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['Hydro Bomb', 'BOMB', '1 Box/10 Pcs', '', '', '', '', '67', '264', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['7 cm Electric Sparklers', 'SPARKLERS', '1 Box/10 Pcs', '', '', '', '', '7', '28', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['Flower Pots Big', 'FlOWER POTS - Regular', '1 Box/10 Pcs', '', '', '', '', '57', '224', '70', '15', '1', '1', '0', '0', '', '', '', '', '', '']
        ];
        
        if ($format === 'xlsx') {
            return $this->downloadExcelTemplate($data);
        } else {
            return $this->downloadCsvTemplate($data);
        }
    }

    private function downloadCsvTemplate($data)
    {
        $filename = 'stock_upload_template.csv';
        
        $csvContent = '';
        foreach ($data as $row) {
            $csvContent .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    private function downloadExcelTemplate($data)
    {
        try {
            // Create a temporary file for the Excel export
            $filename = 'stock_upload_template.xlsx';
            
            // Use Maatwebsite Excel to create the file
            return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
                private $data;
                
                public function __construct($data)
                {
                    $this->data = $data;
                }
                
                public function array(): array
                {
                    return array_slice($this->data, 1);
                }
                
                public function headings(): array
                {
                    return $this->data[0] ?? [];
                }
            }, $filename);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate Excel template: ' . $e->getMessage());
            return redirect()->route('admin.stocks');
        }
    }
        // bulk upload stock data ends here


} 