<?php

namespace App\Livewire;

use App\Services\CouponService;
use Livewire\Component;

class CouponApply extends Component
{
    public $couponCode = '';
    public $orderAmount = 0;
    public $orderItems = [];
    public $appliedCoupon = null;
    public $couponMessage = '';
    public $couponError = '';
    public $discountAmount = 0;
    public $bonusItems = [];
    public $debugCoupon = null;

    protected $rules = [
        'couponCode' => 'required|string|max:50',
    ];

    public function mount($orderAmount = 0, $orderItems = [])
    {
        $this->orderAmount = $orderAmount;
        $this->orderItems = $orderItems;
    }

    public function applyCoupon()
    {
        $this->reset(['couponMessage', 'couponError', 'appliedCoupon', 'discountAmount', 'bonusItems', 'debugCoupon']);

        $this->validate();

        $couponService = app(CouponService::class);
        $userId = auth()->id();

        $result = $couponService->validateCoupon(
            $this->couponCode,
            $userId,
            $this->orderAmount,
            $this->orderItems
        );

        if ($result['valid']) {
            $this->appliedCoupon = $result['coupon'];
            $summary = $couponService->getCouponSummary($this->appliedCoupon, $this->orderAmount);
            
            $this->discountAmount = $summary['discount_amount'];
            $this->bonusItems = $summary['bonus_items'];
            $this->couponMessage = "Coupon applied successfully! {$summary['display_text']}";
            
            // Emit event to parent component
            $this->dispatch('coupon-applied', [
                'coupon' => $this->appliedCoupon,
                'discount_amount' => $this->discountAmount,
                'coupon_code' => $this->appliedCoupon->code,
                'bonus_items' => $this->bonusItems,
                'new_total' => $this->orderAmount - $this->discountAmount
            ]);
        } else {
            $this->couponError = $result['message'];
            if (isset($result['coupon'])) {
                $coupon = $result['coupon'];
                $this->debugCoupon = [
                    'code' => $coupon->code ?? null,
                    'is_active' => $coupon->is_active ?? null,
                    'starts_at' => $coupon->starts_at ?? null,
                    'expires_at' => $coupon->expires_at ?? null,
                    'usage_limit' => $coupon->usage_limit ?? null,
                    'used_count' => $coupon->used_count ?? null,
                ];
            }
        }
    }

    public function removeCoupon()
    {
        $this->reset(['couponCode', 'couponMessage', 'couponError', 'appliedCoupon', 'discountAmount', 'bonusItems']);
        
        // Emit event to parent component
        $this->dispatch('coupon-removed');
    }

    public function render()
    {
        return view('livewire.coupon-apply', [
            'debugCoupon' => $this->debugCoupon,
        ]);
    }
} 