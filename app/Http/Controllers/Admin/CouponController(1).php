<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CouponController extends Controller
{
    public function index()
    {
     $query = Coupon::with('bonusProduct');

        // Search filter
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('type', 'like', "%$search%")
                  ->orWhere('value', 'like', "%$search%")
                  ;
            });
        }

        // Type filter
        if (request()->filled('type')) {
            $type = request('type');
            if (in_array($type, ['percentage', 'fixed_amount', 'bonus_items'])) {
                $query->where('type', $type);
            }
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->only('search', 'type'));

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $products = Stock::where('is_active', true)
                        ->where('show_on_shop', true)
                        ->orderBy('item_name')
                        ->get();

        $categories = Stock::distinct()->pluck('category')->filter()->sort()->values();

        return view('admin.coupons.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(['percentage', 'fixed_amount', 'bonus_items'])],
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'user_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
            'applies_to_categories' => 'nullable|array',
            'applies_to_categories.*' => 'string',
            'excluded_products' => 'nullable|array',
            'excluded_products.*' => 'exists:stocks,id',
            'bonus_product_id' => 'nullable|required_if:type,bonus_items|exists:stocks,id',
            'bonus_quantity' => 'nullable|required_if:type,bonus_items|integer|min:1',
        ];
        if (in_array($request->type, ['percentage', 'fixed_amount'])) {
            $rules['value'] = 'required|numeric|min:0';
        }
        $validated = $request->validate($rules);
        if ($request->type === 'bonus_items') {
            $validated['value'] = 0;
        }

        // Auto-generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = strtoupper(Str::random(8));
        }

        // Validate bonus product is active
        if ($validated['type'] === 'bonus_items' && $validated['bonus_product_id']) {
            $bonusProduct = Stock::find($validated['bonus_product_id']);
            if (!$bonusProduct || !$bonusProduct->is_active) {
                return back()->withErrors(['bonus_product_id' => 'Selected bonus product is not available.']);
            }
        }

        $coupon = Coupon::create($validated);

        return redirect()->route('admin.coupons')
                        ->with('success', 'Coupon created successfully!');
    }

    public function edit(Coupon $coupon)
    {
        $products = Stock::where('is_active', true)
                        ->where('show_on_shop', true)
                        ->orderBy('item_name')
                        ->get();

        $categories = Stock::distinct()->pluck('category')->filter()->sort()->values();

        return view('admin.coupons.edit', compact('coupon', 'products', 'categories'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $rules = [
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons')->ignore($coupon->id)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(['percentage', 'fixed_amount', 'bonus_items'])],
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'user_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
            'applies_to_categories' => 'nullable|array',
            'applies_to_categories.*' => 'string',
            'excluded_products' => 'nullable|array',
            'excluded_products.*' => 'exists:stocks,id',
            'bonus_product_id' => 'nullable|required_if:type,bonus_items|exists:stocks,id',
            'bonus_quantity' => 'nullable|required_if:type,bonus_items|integer|min:1',
        ];
        if (in_array($request->type, ['percentage', 'fixed_amount'])) {
            $rules['value'] = 'required|numeric|min:0';
        }
        $validated = $request->validate($rules);
        if ($request->type === 'bonus_items') {
            $validated['value'] = 0;
        }

        // Validate bonus product is active
        if ($validated['type'] === 'bonus_items' && $validated['bonus_product_id']) {
            $bonusProduct = Stock::find($validated['bonus_product_id']);
            if (!$bonusProduct || !$bonusProduct->is_active) {
                return back()->withErrors(['bonus_product_id' => 'Selected bonus product is not available.']);
            }
        }

        $coupon->update($validated);

        return redirect()->route('admin.coupons')
                        ->with('success', 'Coupon updated successfully!');
    }

    public function destroy(Coupon $coupon)
    {
        if ($coupon->used_count > 0) {
            return back()->with('error', 'Cannot delete coupon that has been used.');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons')
                        ->with('success', 'Coupon deleted successfully!');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        $status = $coupon->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Coupon {$status} successfully!");
    }

    public function generateCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Coupon::where('code', $code)->exists());

        return response()->json(['code' => $code]);
    }

    public function usage(Coupon $coupon)
    {
        $usages = $coupon->usages()
                        ->with(['user', 'order'])
                        ->orderBy('used_at', 'desc')
                        ->paginate(15);

        return view('admin.coupons.usage', compact('coupon', 'usages'));
    }

    // Bulk upload: Download CSV template
    public function downloadTemplate()
    {
        $data = [
            ['code', 'name', 'description', 'type', 'value', 'usage_limit', 'user_limit', 'minimum_order_amount', 'is_active', 'starts_at', 'expires_at'],
            ['WELCOME10', 'Welcome Offer', '10% off for new users', 'percentage', '10', '100', '1', '0', '1', '2025-08-01 00:00:00', '2025-12-31 23:59:59'],
            ['FESTIVE50', 'Festive Sale', 'Flat ₹50 off', 'fixed_amount', '50', '200', '2', '500', '1', '2025-08-01 00:00:00', '2025-10-31 23:59:59']
        ];
        $filename = 'coupon_upload_template.csv';
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

    // Bulk upload: Import CSV
    
    public function importCsv(Request $request)
    {
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt,xls,xlsx|max:2048'
    ]);
    
    try {
        $file = $request->file('csv_file');
        $imported = 0;
        $errors = [];
        
        // Use Excel facade to read the file
        $data = Excel::toArray([], $file);
        
        if (empty($data) || empty($data[0])) {
            return back()->with('error', 'No data found in the uploaded file.');
        }
        
        $rows = $data[0]; // Get first sheet
        $header = array_shift($rows); // Remove header row
        
        foreach ($rows as $index => $row) {
            try {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                $rowData = array_combine($header, $row);
                
                // Validate required fields
                if (empty($rowData['code']) || empty($rowData['name']) || empty($rowData['type']) || empty($rowData['value'])) {
                    $errors[] = "Row " . ($index + 2) . ": Missing required fields (code, name, type, value)";
                    continue;
                }
                
                // Validate type field
                if (!in_array(strtolower($rowData['type']), ['percentage', 'fixed_amount', 'bonus_items'])) {
                    $errors[] = "Row " . ($index + 2) . ": Invalid type. Must be percentage, fixed_amount, or bonus_items";
                    continue;
                }
                
                // Check if coupon code already exists
                if (Coupon::where('code', trim($rowData['code']))->exists()) {
                    $errors[] = "Row " . ($index + 2) . ": Coupon code '{$rowData['code']}' already exists";
                    continue;
                }
                
                // Create coupon
                Coupon::create([
                    'code' => trim($rowData['code']),
                    'name' => trim($rowData['name']),
                    'description' => trim($rowData['description'] ?? ''),
                    'type' => strtolower(trim($rowData['type'])),
                    'value' => (float) $rowData['value'],
                    'usage_limit' => isset($rowData['usage_limit']) && !empty($rowData['usage_limit']) ? (int) $rowData['usage_limit'] : null,
                    'user_limit' => isset($rowData['user_limit']) && !empty($rowData['user_limit']) ? (int) $rowData['user_limit'] : null,
                    'minimum_order_amount' => isset($rowData['minimum_order_amount']) && !empty($rowData['minimum_order_amount']) ? (float) $rowData['minimum_order_amount'] : 0,
                    'is_active' => isset($rowData['is_active']) ? (bool) $rowData['is_active'] : true,
                    'starts_at' => !empty($rowData['starts_at']) ? \Carbon\Carbon::parse($rowData['starts_at']) : null,
                    'expires_at' => !empty($rowData['expires_at']) ? \Carbon\Carbon::parse($rowData['expires_at']) : null,
                ]);
                
                $imported++;
                
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }
        
        // Flash messages
        if ($imported > 0) {
            session()->flash('success', "Successfully imported {$imported} coupons!");
        }
        
        if (!empty($errors)) {
            $errorMessage = 'Some rows failed to import: ' . implode(', ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $errorMessage .= ' and ' . (count($errors) - 5) . ' more errors';
            }
            session()->flash('error', $errorMessage);
        }
        
        return redirect()->route('admin.coupons');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Upload failed: ' . $e->getMessage());
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
} 