<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Validate a coupon code
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'order_amount' => 'nullable|numeric|min:0',
            'order_items' => 'nullable|array',
        ]);

        $userId = auth()->id();
        $result = $this->couponService->validateCoupon(
            $request->code,
            $userId,
            $request->order_amount ?? 0,
            $request->order_items ?? []
        );

        if ($result['valid']) {
            $summary = $this->couponService->getCouponSummary($result['coupon'], $request->order_amount ?? 0);
            
            return response()->json([
                'success' => true,
                'message' => 'Coupon is valid',
                'coupon' => [
                    'id' => $result['coupon']->id,
                    'name' => $result['coupon']->name,
                    'code' => $result['coupon']->code,
                    'type' => $result['coupon']->type,
                    'value' => $result['coupon']->value,
                    'description' => $result['coupon']->description,
                    'discount_amount' => $summary['discount_amount'],
                    'bonus_items' => $summary['bonus_items'],
                    'display_text' => $summary['display_text'],
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 400);
    }

    /**
     * Get available coupons for a user
     */
    public function available(Request $request): JsonResponse
    {
        $request->validate([
            'order_amount' => 'nullable|numeric|min:0',
            'category' => 'nullable|string',
        ]);

        $userId = auth()->id();
        $orderAmount = $request->order_amount ?? 0;
        $category = $request->category;

        $coupons = \App\Models\Coupon::valid()
            ->where('is_active', true)
            ->where(function ($query) use ($orderAmount) {
                $query->whereNull('minimum_order_amount')
                      ->orWhere('minimum_order_amount', '<=', $orderAmount);
            })
            ->where(function ($query) use ($category) {
                if ($category) {
                    $query->whereNull('applies_to_categories')
                          ->orWhereJsonContains('applies_to_categories', $category);
                }
            })
            ->get()
            ->filter(function ($coupon) use ($userId) {
                return $coupon->canBeUsedByUser($userId);
            })
            ->map(function ($coupon) use ($orderAmount) {
                $summary = $this->couponService->getCouponSummary($coupon, $orderAmount);
                return [
                    'id' => $coupon->id,
                    'name' => $coupon->name,
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'description' => $coupon->description,
                    'discount_amount' => $summary['discount_amount'],
                    'bonus_items' => $summary['bonus_items'],
                    'display_text' => $summary['display_text'],
                    'minimum_order_amount' => $coupon->minimum_order_amount,
                    'expires_at' => $coupon->expires_at?->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'coupons' => $coupons
        ]);
    }
} 