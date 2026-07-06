<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::query()
            ->when(request('search'), function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('item_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('youtube_url', 'like', "%{$search}%"); //newly added
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'youtube_url' => 'nullable|string|max:255' //newly added
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
            
            if (in_array($extension, ['xlsx', 'xls'])) {
                // Handle Excel files
                $data = $this->readExcelFile($file);
                
                // Process Excel data (already has headers as keys)
                foreach ($data as $index => $rowData) {
                    try {
                        // Validate required fields
                        if (empty($rowData['item_name']) || empty($rowData['category']) || empty($rowData['quantity']) || empty($rowData['price'])) {
                            $errors[] = "Row " . ($index + 2) . ": Missing required fields";
                            continue;
                        }

                        Stock::create([
                            'item_name' => trim($rowData['item_name']),
                            'category' => trim($rowData['category']),
                            'description' => trim($rowData['description'] ?? ''),
                            'quantity' => (int) $rowData['quantity'],
                            'price' => (float) $rowData['price'],
                            'original_price' => !empty($rowData['original_price']) ? (float) $rowData['original_price'] : null,
                            'discount_percentage' => !empty($rowData['discount_percentage']) ? (int) $rowData['discount_percentage'] : null,
                            'is_active' => $rowData['is_active'] == '1',
                            'show_on_shop' => true,
                            'last_released_at' => now(),
                            'next_release_at' => now()->addMinutes(10),
                            'youtube_url' => trim($rowData['youtube_url'] ?? '') //newly added
                        ]);
                        
                        $imported++;
                        
                    } catch (\Exception $e) {
                        $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                    }
                }
            } else {
                // Handle CSV files
                $data = $this->readCsvFile($file);
                
                // Skip header row and process data
                $header = array_shift($data);
                
                foreach ($data as $index => $row) {
                    try {
                        $rowData = array_combine($header, $row);
                        
                        // Validate required fields
                        if (empty($rowData['item_name']) || empty($rowData['category']) || empty($rowData['quantity']) || empty($rowData['price'])) {
                            $errors[] = "Row " . ($index + 2) . ": Missing required fields";
                            continue;
                        }

                        Stock::create([
                            'item_name' => trim($rowData['item_name']),
                            'category' => trim($rowData['category']),
                            'description' => trim($rowData['description'] ?? ''),
                            'quantity' => (int) $rowData['quantity'],
                            'price' => (float) $rowData['price'],
                            'original_price' => !empty($rowData['original_price']) ? (float) $rowData['original_price'] : null,
                            'discount_percentage' => !empty($rowData['discount_percentage']) ? (int) $rowData['discount_percentage'] : null,
                            'is_active' => $rowData['is_active'] == '1',
                            'show_on_shop' => true,
                            'last_released_at' => now(),
                            'next_release_at' => now()->addMinutes(10),
                            'youtube_url' => trim($rowData['youtube_url'] ?? '') //newly added
                        ]);
                        
                        $imported++;
                        
                    } catch (\Exception $e) {
                        $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                    }
                }
            }
            
            if ($imported > 0) {
                session()->flash('success', "Successfully imported {$imported} stocks!");
            }
            
            if (!empty($errors)) {
                session()->flash('error', 'Some rows failed to import: ' . implode(', ', array_slice($errors, 0, 5)));
            }
            
            return redirect()->route('admin.stocks');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function previewImport(Request $request)
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
            ['item_name', 'category', 'description', 'quantity', 'price', 'original_price', 'discount_percentage', 'is_active', 'youtube_url'],
            ['Sample Product', 'BOMBS', 'Sample description', '100', '50.00', '100.00', '50', '1', 'https://www.youtube.com/watch?v=sample'],
            ['Another Product', 'SPARKLERS', 'Another description', '200', '25.00', '', '', '1', '']
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