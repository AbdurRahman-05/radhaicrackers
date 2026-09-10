<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Coupon;
use App\Services\CouponService;
use App\Services\SMSService;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SmartCheckoutController extends Controller
{
    public function show()
    {
        $stocks = \App\Models\Stock::all();
        $stockMap = [];
        foreach ($stocks as $stock) {
            $stockMap[$stock->id] = [
                'id' => $stock->id,
                'name' => $stock->item_name,
                'price' => (float)$stock->price,
                'original_price' => (float)($stock->original_price > 0 ? $stock->original_price : $stock->price),
            ];
        }
        return view('pages.smart-checkout', compact('stockMap'));
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

    public function submit(Request $request, SMSService $smsService)
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
            'lucky_spin_prize' => 'nullable|string',
            'lucky_spin_discount' => 'nullable|numeric|min:0',
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

            // Calculate total from items to ensure accuracy (exclude free gifts)
            $regularSubtotal = 0;
            $comboSubtotal = 0;
            $calculatedTotal = 0;

            foreach ($items as &$item) {
                if (!empty($item['is_lucky_spin_gift']) || !empty($item['is_free_gift'])) {
                    continue;
                }
                $qty = (int)($item['quantity'] ?? 1);
                $pId = (int)($item['product_id'] ?? 0);
                $pName = (string)($item['product_name'] ?? '');
                $isCombo = !empty($item['is_combo']) || ($pId >= 999000 && $pId <= 999999) || str_contains(strtoupper($pName), 'COMBO');

                if ($isCombo) {
                    $item['is_combo'] = true;
                    $comboPrice = (float)($item['price'] ?? $item['rate'] ?? $item['original_price'] ?? 0);
                    $comboSubtotal += $comboPrice * $qty;
                    $calculatedTotal += $comboPrice * $qty;
                } else {
                    $itemTotal = (float)($item['original_price'] ?? $item['rate'] ?? 0) * $qty;
                    $regularSubtotal += $itemTotal;
                    $calculatedTotal += $itemTotal;
                }
            }
            unset($item);

            // Apply discounts ONLY on regular items (combos consume their exact net offer price)
            $discount70 = $regularSubtotal * 0.7;
            $afterDiscount70 = $regularSubtotal - $discount70;
            $discount15 = $afterDiscount70 * 0.15;
            $afterDiscount15 = $afterDiscount70 - $discount15;
            $totalPayableItems = $afterDiscount15 + $comboSubtotal;
            // No delivery/packing charge for combo packs; 5% packing charge applies ONLY to regular products
            $packingCharge = $afterDiscount15 * 0.05;
            $finalTotal = $totalPayableItems + $packingCharge;

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

            // Process Lucky Spinning Wheel Prize (strictly for NORMAL purchases >= ₹5,000)
            $luckySpinPrize = $request->input('lucky_spin_prize');
            $luckySpinDiscount = 0;

            // Check if eligible for lucky spin:
            // STRICT RULE: Lucky Wheel is ONLY unlocked for normal purchase above ₹5,000 (combos do not count)
            $normalPurchaseTotal = max(0, ($afterDiscount15 + $packingCharge) - $couponDiscount);
            $isLuckySpinEligible = ($normalPurchaseTotal >= 5000);

            if (!$isLuckySpinEligible) {
                // If normal purchase is less than 5000, remove lucky spin gifts and reset discount
                $items = array_values(array_filter($items, function($it) {
                    return empty($it['is_lucky_spin_gift']);
                }));
                $luckySpinPrize = null;
                $luckySpinDiscount = 0;
            } elseif (!empty($luckySpinPrize)) {
                if ($luckySpinPrize === '5% Discount' || str_contains(strtolower($luckySpinPrize), '5%')) {
                    $luckySpinDiscount = round($finalTotal * 0.05, 2);
                    $finalTotal = max(0, $finalTotal - $luckySpinDiscount);
                } elseif (str_contains(strtolower($luckySpinPrize), '25 raider')) {
                    // Check if already injected
                    $hasGift = false;
                    foreach ($items as $it) {
                        if (!empty($it['is_lucky_spin_gift'])) {
                            $hasGift = true;
                            break;
                        }
                    }
                    if (!$hasGift) {
                        $items[] = [
                            'product_id' => 1903,
                            'product_name' => '🎁 25 Raider (Free Gift)',
                            'content' => '1 Box',
                            'rate' => 0,
                            'original_price' => 220,
                            'price' => 0,
                            'quantity' => 1,
                            'total' => 0,
                            'is_lucky_spin_gift' => true,
                            'is_free_gift' => true
                        ];
                    }
                } elseif (str_contains(strtolower($luckySpinPrize), '30 shot')) {
                    $hasGift = false;
                    foreach ($items as $it) {
                        if (!empty($it['is_lucky_spin_gift'])) {
                            $hasGift = true;
                            break;
                        }
                    }
                    if (!$hasGift) {
                        $items[] = [
                            'product_id' => 1905,
                            'product_name' => '🎁 30 Shot Regular (Free Gift)',
                            'content' => '1 Box',
                            'rate' => 0,
                            'original_price' => 390,
                            'price' => 0,
                            'quantity' => 1,
                            'total' => 0,
                            'is_lucky_spin_gift' => true,
                            'is_free_gift' => true
                        ];
                    }
                } elseif (str_contains(strtolower($luckySpinPrize), 'tin shower') || str_contains(strtolower($luckySpinPrize), 'shower')) {
                    $hasGift = false;
                    foreach ($items as $it) {
                        if (!empty($it['is_lucky_spin_gift'])) {
                            $hasGift = true;
                            break;
                        }
                    }
                    if (!$hasGift) {
                        $items[] = [
                            'product_id' => 1862,
                            'product_name' => '🎁 6 Inch Tin Shower (Free Gift)',
                            'content' => '1 Pcs',
                            'rate' => 0,
                            'original_price' => 200,
                            'price' => 0,
                            'quantity' => 1,
                            'total' => 0,
                            'is_lucky_spin_gift' => true,
                            'is_free_gift' => true
                        ];
                    }
                }
            }

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
            $orderData['subtotal'] = $calculatedTotal;
            $orderData['discount_70_percent'] = $discount70;
            $orderData['amount_after_70_discount'] = $afterDiscount70 + $comboSubtotal;
            $orderData['special_discount_15_percent'] = $discount15;
            $orderData['amount_after_15_discount'] = $afterDiscount15 + $comboSubtotal;
            $orderData['packing_charge_5_percent'] = $packingCharge;
            $orderData['coupon_code'] = $request->input('coupon_code');
            $orderData['coupon_discount'] = $couponDiscount;
            $orderData['lucky_spin_prize'] = $luckySpinPrize;
            $orderData['lucky_spin_discount'] = $luckySpinDiscount;
            $orderData['final_amount'] = $mailTotal;
            $orderData['final_amount_after_coupon'] = $mailTotal;
            $orderData['status'] = 'pending';
            $orderData['payment_status'] = 'pending';

            // Assign order to user: prioritize authenticated user, or find/create user by mobile number
            $userId = auth()->id();
            if (!$userId) {
                try {
                    $user = \App\Models\User::firstOrCreate(
                        ['phone' => $orderData['customer_mobile']],
                        [
                            'name' => $orderData['customer_name'],
                            'email' => $orderData['customer_email'] ?: null,
                            'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(12)),
                            'is_active' => true,
                        ]
                    );
                    if ($user->name === 'User-' . $user->phone || empty($user->name) || preg_match('/^User-\d+$/', $user->name)) {
                        $user->update([
                            'name' => $orderData['customer_name'],
                            'email' => $orderData['customer_email'] ?: $user->email,
                        ]);
                    }
                    \Illuminate\Support\Facades\Auth::login($user);
                    $userId = $user->id;
                } catch (\Throwable $userEx) {
                    \Log::warning('Smart checkout user auto-login failed: ' . $userEx->getMessage());
                    $userId = \App\Models\User::orderBy('id')->value('id') ?? 1;
                }
            } else {
                $user = auth()->user();
                if ($user && ($user->name === 'User-' . $user->phone || empty($user->name) || preg_match('/^User-\d+$/', $user->name))) {
                    $user->update(['name' => $orderData['customer_name']]);
                }
            }

            $orderData['user_id'] = $userId ?? (\App\Models\User::orderBy('id')->value('id') ?? 1);

            // Safety check: if columns don't exist in orders table, omit them
            if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'lucky_spin_prize')) {
                unset($orderData['lucky_spin_prize']);
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'lucky_spin_discount')) {
                unset($orderData['lucky_spin_discount']);
            }

            // Create order
            $order = Order::create($orderData);

            // Ordered count will be dynamically calculated when order is confirmed by Admin

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

            // WhatsApp Integration & PDF Bill URL generation
            $whatsappUrl = '';
            try {
                $activeSmsService = $smsService ?? app(\App\Services\SMSService::class);
                $waData = [
                    'customer_name' => $order->customer_name ?: (auth()->user()->name ?? 'Customer'),
                    'order_value' => '₹' . number_format($order->total_amount ?: $order->total, 2),
                    'order_id' => (string)$order->id
                ];
                $customerPhone = $order->customer_mobile ?: (auth()->user()->phone ?? '');
                $activeSmsService->sendWhatsApp($customerPhone, '', 'order_confirmation', $waData);
                $activeSmsService->sendWhatsAppAdmin($customerPhone, '', 'order_confirmation', $waData);

                if (class_exists(\App\Services\WhatsAppService::class)) {
                    $whatsappService = app(\App\Services\WhatsAppService::class);
                    $whatsappUrl = $whatsappService->generateOrderWhatsAppUrl($order);
                }
            } catch (\Throwable $waEx) {
                Log::error('Smart checkout WhatsApp Exception: ' . $waEx->getMessage());
            }

            $redirectUrl = route('user.orders.show', $order->id);
            if (!auth()->check()) {
                $redirectUrl = route('shop') . '?order_success=' . $order->id;
            }

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'whatsapp_url' => $whatsappUrl,
                'pdf_url' => route('user.orders.invoice_pdf', $order->id),
                'redirect_url' => $redirectUrl
            ]);

        } catch (\Throwable $e) {
            Log::error('Smart checkout error: ' . $e->getMessage(), [
                'exception' => $e,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'message' => config('app.debug') 
                    ? 'Error placing order: ' . $e->getMessage() 
                    : (str_contains($e->getMessage(), 'SQLSTATE') ? 'Database error while placing order. Please try again.' : $e->getMessage()),
                'error_detail' => $e->getMessage()
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