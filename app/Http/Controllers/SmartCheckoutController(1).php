<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Coupon;
use App\Services\CouponService;
use Illuminate\Support\Facades\Log;

class SmartCheckoutController extends Controller
{
    public function show()
    {
        return view('pages.smart-checkout');
    }

    public function validateCoupon(Request $request)
    {
        $code = $request->input('code');
        $orderAmount = floatval($request->input('order_amount'));

        $coupon = \App\Models\Coupon::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon code.'
            ]);
        }

        // Check minimum order amount
        if ($coupon->minimum_order_amount && $orderAmount < $coupon->minimum_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount of ₹' . number_format($coupon->minimum_order_amount, 2) . ' is required to use this coupon.'
            ]);
        }

        // Calculate discount
        $discount = 0;
        if ($coupon->type === 'percentage') {
            $discount = $orderAmount * ($coupon->value / 100);
        } elseif ($coupon->type === 'fixed' || $coupon->type === 'fixed_amount') {
            $discount =(float) $coupon->value;
        }

        // Prevent discount > total
        if ($discount > $orderAmount) {
            $discount = $orderAmount;
        }

        return response()->json([
            'success' => true,
            'coupon' => [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'minimum_order_amount' => $coupon->minimum_order_amount
            ],
            'discount_amount' => $discount,
            'new_total' => $orderAmount - $discount
        ]);
    }

    public function getAvailableCoupons()
    {
        try {
            $coupons = Coupon::where('is_active', true)
                ->where('expires_at', '>', now())
                ->where('usage_limit', '>', 0)
                ->orderBy('discount_value', 'desc')
                ->get(['code', 'description', 'discount_type', 'discount_value', 'minimum_order_amount']);

            return response()->json([
                'success' => true,
                'coupons' => $coupons
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching available coupons: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching coupons.'
            ]);
        }
    }

    public function submit(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => 'required|digits:10',
            'customer_email' => 'nullable|email',
            'customer_state' => 'required|string',
            'customer_district' => 'required|string',
            'customer_city' => 'required|string',
            'delivery_point' => 'required|string',
            'pin_code' => 'required|digits:6',
            'coupon_code' => 'nullable|string',
            'coupon_discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'items' => 'required|string'
        ]);

        try {
            // Parse cart items
            $itemsJson = $request->input('items');
            $items = json_decode($itemsJson, true);

            if (!is_array($items) || count($items) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty or invalid. Please add items to your cart and try again.'
                ]);
            }

            // Calculate total from items to ensure accuracy
            $calculatedTotal = 0;
            foreach ($items as $item) {
                $itemTotal = ($item['original_price'] ?? 0) * ($item['quantity'] ?? 0);
                $calculatedTotal += $itemTotal;
            }

            // Apply discounts
            $discount70 = $calculatedTotal * 0.7;
            $afterDiscount70 = $calculatedTotal - $discount70;
            $discount15 = $afterDiscount70 * 0.15;
            $afterDiscount15 = $afterDiscount70 - $discount15;
            $packingCharge = $afterDiscount15 * 0.05;
            $finalTotal = $afterDiscount15 + $packingCharge;

            // Calculate coupon discount only if code is present
            $couponDiscount = 0;
            $couponCode = $request->input('coupon_code');
            if (!empty($couponCode)) {
                $coupon = Coupon::where('code', $couponCode)
                    ->where('is_active', true)
                    ->first();
                
                if ($coupon) {
                    if ($coupon->type === 'percentage') {
                        $couponDiscount = $finalTotal * ($coupon->value / 100);
                    } elseif ($coupon->type === 'fixed' || $coupon->type === 'fixed_amount') {
                        $couponDiscount = $coupon->value;
                    }
                    // Ensure coupon discount doesn't exceed final total
                    $couponDiscount = min($couponDiscount, $finalTotal);
                }
            }
            
            $finalTotal = max(0, $finalTotal - $couponDiscount);
            $mailTotal = $finalTotal;

            // Prepare order data
            $orderData = $request->only([
                'customer_name', 'customer_mobile', 'customer_email',
                'customer_state', 'customer_district', 'customer_city',
                'delivery_point', 'pin_code'
            ]);

            $orderData['items_json'] = $items;
            $orderData['total_amount'] = $mailTotal;
            $orderData['total'] = $mailTotal;
            $orderData['coupon_code'] = $request->input('coupon_code');
            $orderData['coupon_discount'] = $couponDiscount;
            $orderData['user_id'] = auth()->id() ?? 1;
            $orderData['status'] = 'pending';
            $orderData['payment_status'] = 'pending';

            // Create order
            $order = Order::create($orderData);

            // Increment ordered_count for each stock item
            foreach ($items as $item) {
                // Try product_id, fallback to id or key
                $productId = $item['product_id'] ?? $item['id'] ?? null;
                if ($productId) {
                    $stock = \App\Models\Stock::find($productId);
                    if ($stock) {
                        $stock->increment('ordered_count', $item['quantity'] ?? 1);
                    }
                }
            }

            // Log coupon usage
            if (!empty($request->input('coupon_code'))) {
                $coupon = Coupon::where('code', $request->input('coupon_code'))->first();
                if ($coupon) {
                    // Only update coupon usage count
                    $coupon->decrement('usage_limit');
                }
            }

            Log::info('Smart checkout order created successfully', [
                'order_id' => $order->id,
                'items_count' => count($items),
                'total_amount' => $order->total_amount,
                'coupon_code' => $order->coupon_code,
                'coupon_discount' => $order->coupon_discount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'redirect_url' => route('user.orders.show', $order->id)
            ]);

        } catch (\Exception $e) {
            Log::error('Smart checkout error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error placing order. Please try again.'
            ]);
        }
    }

    public function saveDraft(Request $request)
    {
        $request->validate([
            'customer_data' => 'required|array',
            'cart_data' => 'required|array',
            'coupon_data' => 'nullable|array'
        ]);

        try {
            $draftData = [
                'customer' => $request->input('customer_data'),
                'cart' => $request->input('cart_data'),
                'coupon' => $request->input('coupon_data'),
                'user_id' => auth()->id(),
                'created_at' => now()
            ];

            // Store draft in session or database
            session(['checkout_draft' => $draftData]);

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving draft: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving draft.'
            ]);
        }
    }

    public function loadDraft()
    {
        try {
            $draft = session('checkout_draft');
            
            if (!$draft) {
                return response()->json([
                    'success' => false,
                    'message' => 'No draft found.'
                ]);
            }

            return response()->json([
                'success' => true,
                'draft' => $draft
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading draft: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading draft.'
            ]);
        }
    }
} 