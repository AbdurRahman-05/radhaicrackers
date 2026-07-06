<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('bonusProduct')
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

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
} 