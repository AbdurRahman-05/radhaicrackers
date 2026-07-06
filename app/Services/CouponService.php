<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CouponService
{
    public function validateCoupon($code, $userId, $orderAmount = 0, $orderItems = [])
    {
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon) {
            \Log::warning('Coupon validation failed: not found', ['code' => $code]);
            return ['valid' => false, 'message' => 'Invalid coupon code.'];
        }

        // Debug log for troubleshooting
        \Log::info('Coupon validation', [
            'code' => $coupon->code,
            'is_active' => $coupon->is_active,
            'starts_at' => $coupon->starts_at,
            'expires_at' => $coupon->expires_at,
            'now' => now(),
        ]);

        if (!$coupon->is_active) {
            \Log::warning('Coupon validation failed: not active', ['code' => $coupon->code]);
            return ['valid' => false, 'message' => 'This coupon is not active.', 'coupon' => $coupon];
        }
        if ($coupon->starts_at && \Carbon\Carbon::parse($coupon->starts_at)->isFuture()) {
            \Log::warning('Coupon validation failed: not yet active', ['code' => $coupon->code, 'starts_at' => $coupon->starts_at]);
            return ['valid' => false, 'message' => 'This coupon is not yet active.', 'coupon' => $coupon];
        }
        if ($coupon->expires_at && \Carbon\Carbon::parse($coupon->expires_at)->isPast()) {
            \Log::warning('Coupon validation failed: expired', ['code' => $coupon->code, 'expires_at' => $coupon->expires_at]);
            return ['valid' => false, 'message' => 'This coupon has expired.', 'coupon' => $coupon];
        }
        // Fix: Only check usage limit if it's greater than 0
        if ($coupon->usage_limit && $coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit) {
            \Log::warning('Coupon validation failed: usage limit reached', ['code' => $coupon->code, 'used_count' => $coupon->used_count, 'usage_limit' => $coupon->usage_limit]);
            return ['valid' => false, 'message' => 'This coupon has reached its usage limit.', 'coupon' => $coupon];
        }

        if (!$coupon->canBeUsedByUser($userId)) {
            if ($coupon->user_limit) {
                $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
                                            ->where('user_id', $userId)
                                            ->count();
                if ($userUsageCount >= $coupon->user_limit) {
                    return ['valid' => false, 'message' => 'You have already used this coupon the maximum number of times.', 'coupon' => $coupon];
                }
            }
        }

        if ($orderAmount > 0 && $coupon->minimum_order_amount > $orderAmount) {
            return [
                'valid' => false, 
                'message' => "Minimum order amount required: ₹{$coupon->minimum_order_amount}",
                'coupon' => $coupon
            ];
        }

        // Check if coupon applies to order items
        if (!empty($orderItems) && !$this->couponAppliesToItems($coupon, $orderItems)) {
            return ['valid' => false, 'message' => 'This coupon does not apply to the items in your cart.', 'coupon' => $coupon];
        }

        return ['valid' => true, 'coupon' => $coupon];
    }

    public function applyCouponToOrder($coupon, $order, $orderItems)
    {
        return DB::transaction(function () use ($coupon, $order, $orderItems) {
            $discountAmount = 0;
            $bonusItems = [];

            // Calculate discount
            if (in_array($coupon->type, [Coupon::TYPE_PERCENTAGE, Coupon::TYPE_FIXED_AMOUNT])) {
                $discountAmount = $coupon->calculateDiscount($order->total);
                
                // Update order total
                $order->update([
                    'total' => $order->total - $discountAmount
                ]);
            }

            // Add bonus items
            if ($coupon->type === Coupon::TYPE_BONUS_ITEMS && $coupon->bonus_product_id) {
                $bonusProduct = $coupon->bonusProduct;
                if ($bonusProduct && $bonusProduct->is_active) {
                    // Add bonus item to order
                    OrderItem::create([
                        'order_id' => $order->id,
                        'stock_id' => $bonusProduct->id,
                        'quantity' => $coupon->bonus_quantity,
                        'price' => 0, // Free bonus item
                        'total' => 0,
                    ]);

                    $bonusItems[] = [
                        'product_id' => $bonusProduct->id,
                        'product_name' => $bonusProduct->item_name,
                        'quantity' => $coupon->bonus_quantity,
                    ];
                }
            }

            // Record coupon usage
            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'discount_amount' => $discountAmount,
                'bonus_items_added' => $bonusItems,
                'used_at' => now(),
            ]);

            // Increment coupon usage count
            $coupon->incrementUsage();
            
            // Refresh the model to get the updated used_count
            $coupon->refresh();

            // Auto-inactivate and expire if usage limit reached (only if limit > 0)
            if ($coupon->usage_limit && $coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit) {
                $coupon->is_active = false;
                $coupon->expires_at = now();
                $coupon->save();
                
                \Log::info('Coupon auto-expired due to usage limit reached', [
                    'coupon_code' => $coupon->code,
                    'usage_limit' => $coupon->usage_limit,
                    'used_count' => $coupon->used_count
                ]);
            }

            return [
                'discount_amount' => $discountAmount,
                'bonus_items' => $bonusItems,
                'new_total' => $order->total,
            ];
        });
    }

    private function couponAppliesToItems($coupon, $orderItems)
    {
        // If no category/product restrictions, coupon applies to all items
        if (empty($coupon->applies_to_categories) && empty($coupon->excluded_products)) {
            return true;
        }

        foreach ($orderItems as $item) {
            $stock = $item['stock'] ?? Stock::find($item['stock_id']);
            
            if (!$stock) continue;

            // Check if product is excluded
            if (!empty($coupon->excluded_products) && in_array($stock->id, $coupon->excluded_products)) {
                return false;
            }

            // Check if coupon applies to this category
            if (!empty($coupon->applies_to_categories) && !in_array($stock->category, $coupon->applies_to_categories)) {
                return false;
            }
        }

        return true;
    }

    public function getCouponSummary($coupon, $orderAmount)
    {
        $summary = [
            'name' => $coupon->name,
            'description' => $coupon->description,
            'type' => $coupon->type,
            'discount_amount' => 0,
            'bonus_items' => [],
        ];

        switch ($coupon->type) {
            case Coupon::TYPE_PERCENTAGE:
                $summary['discount_amount'] = $coupon->calculateDiscount($orderAmount);
                $summary['display_text'] = "{$coupon->value}% off";
                if ($coupon->maximum_discount) {
                    $summary['display_text'] .= " (max ₹{$coupon->maximum_discount})";
                }
                break;

            case Coupon::TYPE_FIXED_AMOUNT:
                $summary['discount_amount'] = $coupon->calculateDiscount($orderAmount);
                $summary['display_text'] = "₹{$coupon->value} off";
                break;

            case Coupon::TYPE_BONUS_ITEMS:
                if ($coupon->bonusProduct) {
                    $summary['bonus_items'][] = [
                        'name' => $coupon->bonusProduct->item_name,
                        'quantity' => $coupon->bonus_quantity,
                    ];
                    $summary['display_text'] = "Get {$coupon->bonus_quantity}x {$coupon->bonusProduct->item_name} FREE";
                }
                break;
        }

        return $summary;
    }
} 