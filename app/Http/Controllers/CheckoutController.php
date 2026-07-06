<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class CheckoutController extends Controller
{
    public function showForm(Request $request)
    {
        $items = $request->query('items', '');
        $total = (float) $request->query('total', 0);
        $coupon_code = $request->query('coupon_code');
        $coupon_discount = 0;

        // Dynamic calculation
        $discount_70 = round($total * 0.7, 2);
        $subtotal_after_70 = $total - $discount_70;
        $discount_15 = round($subtotal_after_70 * 0.15, 2);
        $subtotal_after_15 = $subtotal_after_70 - $discount_15;
        $packing_charge = round($subtotal_after_15 * 0.05, 2);
        $final_total = round($subtotal_after_15 + $packing_charge, 2);

        if ($coupon_code) {
            $coupon = \App\Models\Coupon::whereRaw('LOWER(code) = ?', [strtolower($coupon_code)])->first();
            if ($coupon && $coupon->isValid() && $final_total >= $coupon->minimum_order_amount) {
                $coupon_discount = $coupon->calculateDiscount($final_total);
                $final_total -= $coupon_discount;
                session()->flash('coupon_success', "Coupon Applied: {$coupon->code} - Discount: ₹" . number_format($coupon_discount, 2));
            } else {
                session()->flash('coupon_error', 'Invalid or inapplicable coupon.');
            }
        }

        $states = [
            'Tamil Nadu', 'Kerala', 'Karnataka', 'Andhra Pradesh', 'Telangana', 'Other'
        ];

        return view('pages.checkout', compact(
            'items', 'states', 'total',
            'discount_70', 'discount_15', 'packing_charge', 'final_total',
            'coupon_code', 'coupon_discount'
        ));
    }

    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => 'required|digits:10',
            'customer_email' => 'nullable|email',
            'customer_state' => 'required|string',
            'customer_district' => 'required|string',
            'customer_city' => 'required|string',
            'delivery_point' => 'required|string',
            'pin_code' => 'required|digits:6',
            'verify_code' => 'required|string',
            'coupon_code' => 'nullable|string',
            'coupon_discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        // Get cart items from form
        $itemsJson = $request->input('items');
        $items = json_decode($itemsJson, true);
        \Log::info('Order items received', ['items' => $items, 'raw' => $itemsJson]);
        
        if (!is_array($items) || count($items) === 0) {
            return back()->withInput()->with('message', 'Cart is empty or invalid. Please add items to your cart and try again.');
        }

        // Calculate total from items to ensure accuracy
        $calculatedTotal = 0;
            foreach ($items as $item) {
            $itemTotal = ($item['rate'] ?? 0) * ($item['quantity'] ?? 0);
            $calculatedTotal += $itemTotal;
        }

        // Unified discount calculation
        $discount70 = round($calculatedTotal * 0.70, 2);
        $after70 = $calculatedTotal - $discount70;
        $discount15 = round($after70 * 0.15, 2);
        $after15 = $after70 - $discount15;
        $packing = round($after15 * 0.05, 2);
        $netAmount = $after15 + $packing;
        $couponDiscount = $request->input('coupon_discount') ?? 0;
        $finalAmountAfterCoupon = $netAmount - $couponDiscount;

        // Prepare order data
        $orderData = $validated;
        $orderData['items_json'] = $items; // Save all items as JSON array
        $orderData['total_amount'] = $validated['total']; // This is final amount including coupon
        $orderData['total'] = $calculatedTotal; // Save calculated total from items
        $orderData['coupon_code'] = $validated['coupon_code'] ?? null;
        $orderData['coupon_discount'] = $couponDiscount;
        $orderData['final_amount_after_coupon'] = $finalAmountAfterCoupon;
        $orderData['user_id'] = auth()->id() ?? 1; // Assign to current user or default user
        
        // Create order with all items stored in items_json
        $order = Order::create($orderData);

        // Update user name if it is currently numeric or default
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->name === 'User-' . $user->phone || empty($user->name) || preg_match('/^User-\d+$/', $user->name)) {
                $user->update(['name' => $orderData['customer_name']]);
            }
        }
        
        // Increment ordered_count for each stock item
        foreach ($items as $item) {
            if (isset($item['product_id'])) {
                $stock = \App\Models\Stock::find($item['product_id']);
                if ($stock) {
                    $stock->increment('ordered_count', $item['quantity'] ?? 1);
                }
            }
        }
        
        \Log::info('Order created successfully', [
                    'order_id' => $order->id,
            'items_count' => count($items),
            'total_amount' => $order->total_amount,
            'items_json' => $order->items_json
        ]);

        // Apply coupon usage and record in DB if coupon_code is present
        if (!empty($validated['coupon_code'])) {
            $coupon = \App\Models\Coupon::where('code', $validated['coupon_code'])->first();
            if ($coupon) {
                $couponService = app(\App\Services\CouponService::class);
                $couponService->applyCouponToOrder($coupon, $order, $items);
            }
        }

        // Clear the cart after successful order
        if ($request->has('clear_cart') && $request->input('clear_cart') === 'true') {
            // This will be handled by JavaScript to clear localStorage
        }

        // Redirect to user order details page
        return redirect()->route('user.orders.show', $order->id)->with('success', 'Order placed successfully!');
    }
} 