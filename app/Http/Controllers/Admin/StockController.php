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
     * Export ordered items (product name and count only) as CSV
     */
    /**
     * Export ordered items (product name and count only) as CSV
     */
    public function exportOrderedItems()
    {
        $orderedItems = \App\Models\Stock::where('ordered_count', '>', 0)
            ->get(['item_name', 'ordered_count']);

        $csv = "Product Name,Ordered Count\n";
        foreach ($orderedItems as $item) {
            $csv .= '"' . str_replace('"', '""', $item->item_name) . '",' . $item->ordered_count . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="ordered_items.csv"');
    }
    public function index()
    {
        // Fetch all categories (active, ordered)
        $categories = Category::active()->ordered()->get();
        
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

        // Default selected year to 2025 if not provided in request
        $selectedYear = request()->has('selected_year') ? request('selected_year') : '2025';

        // Calculate ordered count map per product for the selected year
        $ordersQuery = \App\Models\Order::query();
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

        $totalStocks = Stock::count();
        $activeStocks = Stock::where('is_active', true)->count();
        $availableStocks = Stock::where('show_on_shop', true)->count();
        $outOfStock = Stock::where('show_on_shop', false)->count();
        $totalValue = Stock::sum(\DB::raw('quantity * price'));

        return view('admin.stocks.index-new', compact(
            'categories', 'stocksByCategory', 'totalStocks', 'activeStocks', 
            'availableStocks', 'outOfStock', 'totalValue', 'availableYears', 'selectedYear'
        ));
    }

    public function addForm()
    {
        // Fetch categories from the database
        $categories = \App\Models\Category::active()->ordered()->pluck('name', 'id');
        return view('admin.stocks.add', compact('categories'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'youtube_url' => 'nullable|string|max:255' //newly added
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('stocks', 'public');
            }

            // Get category name from ID
            $category = Category::find($request->category);
            if (!$category) {
                return back()->withInput()->with('error', 'Invalid category selected');
            }

            $stock = Stock::create([
                'item_name' => $request->item_name,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'original_price' => $request->original_price > 0 ? $request->original_price : null,
                'discount_percentage' => $request->discount_percentage > 0 ? $request->discount_percentage : null,
                'special_discount_percentage' => $request->special_discount_percentage > 0 ? $request->special_discount_percentage : null,
                'category' => $category->name,
                'category_id' => $category->id,
                'is_active' => $request->has('is_active'),
                'show_on_shop' => true, // Default to true for new stocks
                'image' => $imagePath,
                'youtube_url' => $request->youtube_url, 
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
        // Fetch categories from the database
        $categories = \App\Models\Category::active()->ordered()->pluck('name', 'id');
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
            'special_discount_percentage' => 'nullable|integer|min:0|max:100',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'youtube_url' => 'nullable|string|max:255' //newly added
        ]);

        try {
            // Get category name from ID
            $category = Category::find($request->category);
            if (!$category) {
                return back()->withInput()->with('error', 'Invalid category selected');
            }

            $data = [
                'item_name' => $request->item_name,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'original_price' => $request->original_price > 0 ? $request->original_price : null,
                'discount_percentage' => $request->discount_percentage > 0 ? $request->discount_percentage : null,
                'special_discount_percentage' => $request->special_discount_percentage > 0 ? $request->special_discount_percentage : null,
                'category' => $category->name,
                'category_id' => $category->id,
                'is_active' => $request->has('is_active'),
                'youtube_url' => $request->youtube_url //newly added
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
            'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048'
        ]);

        try {
            $file = $request->file('csv_file');
            $extension = $file->getClientOriginalExtension();
            
            $imported = 0;
            $errors = [];
            $skipped = 0;
            
            if (in_array($extension, ['xlsx', 'xls'])) {
                // Handle Excel files
                $data = $this->readExcelFile($file);
                
                // Process Excel data (already has headers as keys)
                foreach ($data as $index => $rowData) {
                    try {
                        // Skip empty rows
                        if (empty($rowData['item_name']) && empty($rowData['category'])) {
                            $skipped++;
                            continue;
                        }

                        // Validate required fields (only item_name and category are truly required)
                        if (empty(trim($rowData['item_name'])) || empty(trim($rowData['category']))) {
                            $errors[] = "Row " . ($index + 2) . ": Missing required fields (item_name, category)";
                            continue;
                        }

                        // Process data with enhanced field mapping
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
                $data = $this->readCsvFileEnhanced($file);
                
                if (empty($data)) {
                    throw new \Exception('No data found in CSV file');
                }
                
                // Skip header row and process data
                $header = array_shift($data);
                
                // Clean and normalize headers
                $header = array_map(function($h) {
                    return trim(strtolower(str_replace([' ', '-'], '_', $h)));
                }, $header);
                
                foreach ($data as $index => $row) {
                    try {
                        // Skip empty rows
                        if (empty(array_filter($row))) {
                            $skipped++;
                            continue;
                        }

                        $rowData = array_combine($header, $row);
                        
                        // Skip if both item_name and category are empty
                        if (empty($rowData['item_name']) && empty($rowData['category'])) {
                            $skipped++;
                            continue;
                        }

                        // Validate required fields (only item_name and category are truly required)
                        if (empty(trim($rowData['item_name'])) || empty(trim($rowData['category']))) {
                            $errors[] = "Row " . ($index + 2) . ": Missing required fields (item_name, category)";
                            continue;
                        }

                        // Process data with enhanced field mapping
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
     * Process row data with enhanced field mapping and validation
     */
    private function processRowData($rowData, $rowNumber)
    {
        try {
            // Accept both category name and ID
            $category = $rowData['category'] ?? '';
            if (is_numeric($category)) {
                $catModel = \App\Models\Category::find($category);
                $category = $catModel ? $catModel->name : $rowData['category'];
            }

            // Enhanced data processing
            $data = [
                'item_name' => trim($rowData['item_name']),
                'category' => trim($category),
                'description' => trim($rowData['description'] ?? ''),
                'quantity' => $this->parseNumeric($rowData['quantity'] ?? 0, 'int', 0), // Default to 0 if empty
                'price' => $this->parseNumeric($rowData['price'] ?? 0, 'float'), // Price should be provided in CSV
                'original_price' => $this->parseNumeric($rowData['original_price'] ?? null, 'float'),
                'discount_percentage' => $this->parseNumeric($rowData['discount_percentage'] ?? null, 'int'),
                'special_discount_percentage' => $this->parseNumeric($rowData['special_discount_percentage'] ?? null, 'int'),
                'is_active' => $this->parseBoolean($rowData['is_active'] ?? 1),
                'show_on_shop' => $this->parseBoolean($rowData['show_on_shop'] ?? 1),
                'is_popular' => $this->parseBoolean($rowData['is_popular'] ?? 0),
                'is_latest' => $this->parseBoolean($rowData['is_latest'] ?? 0),
                'expires_at' => $this->parseDateTime($rowData['expires_at'] ?? null),
                'ordered_count' => $this->parseNumeric($rowData['ordered_count'] ?? 0, 'int', 0), // Default to 0 if empty
                'last_released_at' => $this->parseDateTime($rowData['last_released_at'] ?? null) ?: now(),
                'next_release_at' => $this->parseDateTime($rowData['next_release_at'] ?? null) ?: now()->addMinutes(10),
                'youtube_url' => trim($rowData['youtube_url'] ?? ''),
                'image' => trim($rowData['image'] ?? '')
            ];

            // Additional validation
            if ($data['price'] <= 0) {
                return ['error' => "Row {$rowNumber}: Price must be greater than 0", 'data' => null];
            }

            if ($data['quantity'] < 0) {
                return ['error' => "Row {$rowNumber}: Quantity cannot be negative", 'data' => null];
            }

            // Note: Allow quantity to be 0 (out of stock items)
            // Only validate that it's not negative

            return ['error' => null, 'data' => $data];

        } catch (\Exception $e) {
            return ['error' => "Row {$rowNumber}: Data processing error - " . $e->getMessage(), 'data' => null];
        }
    }

    /**
     * Enhanced CSV reading with better handling of quotes and special characters
     */
    private function readCsvFileEnhanced($file)
    {
        $path = $file->getRealPath();
        $data = [];
        
        if (($handle = fopen($path, 'r')) !== false) {
            // Set locale for proper character handling
            setlocale(LC_ALL, 'en_US.UTF-8');
            
            while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
                // Clean each field
                $cleanRow = array_map(function($field) {
                    return trim($field);
                }, $row);
                
                $data[] = $cleanRow;
            }
            fclose($handle);
        }
        
        return $data;
    }

    /**
     * Parse numeric values with proper handling
     */
    private function parseNumeric($value, $type = 'float', $default = null)
    {
        if (empty($value) || $value === '') {
            return $default;
        }
        
        // Remove any non-numeric characters except decimal point and negative sign
        $cleaned = preg_replace('/[^\d.-]/', '', $value);
        
        if ($type === 'int') {
            return (int) $cleaned;
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
            ['item_name', 'category', 'description', 'quantity', 'price', 'original_price', 'discount_percentage', 'special_discount_percentage', 'is_active', 'show_on_shop', 'is_popular', 'is_latest', 'expires_at', 'ordered_count', 'last_released_at', 'next_release_at', 'youtube_url', 'image'],
            
            // Sample data rows matching your CSV structure
            ['"4"" Gold Lakshmi"', 'SINGLE FLASH', '1 Pkt/5 Pcs', '', '31', '120', '70', '15', '1', '0', '0', '0', '', '', '', '', '', ''],
            ['"2 3/4"" Kuruvi"', 'SINGLE FLASH', '1 Pkt/5 Pcs', '', '7', '28', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['"4"" Lakshmi"', 'SINGLE FLASH', '1 Pkt/5 Pcs', '', '15', '60', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['Red Bijili', 'BIJILI CRACKERS', '1 Pkt/50 Pcs', '', '18', '72', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['Hydro Bomb', 'BOMB', '1 Box/10 Pcs', '', '67', '264', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['7 cm Electric Sparklers', 'SPARKLERS', '1 Box/10 Pcs', '', '7', '28', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['Flower Pots Big', 'FlOWER POTS - Regular', '1 Box/10 Pcs', '', '57', '224', '70', '15', '1', '1', '0', '0', '', '', '', '', '', '']
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
                    return $this->data;
                }
                
                public function headings(): array
                {
                    return $this->data[0] ?? [];
                }
            }, $filename);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate Excel template: ' . $e->getMessage());
            return redirect()->route('admin.stocks.download-template');
        }
    }
        // bulk upload stock data ends here


} 