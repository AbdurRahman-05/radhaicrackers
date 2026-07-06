<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Coupon;
use Illuminate\Support\Facades\Log;

class SmartCouponSystem extends Component
{
    public $code = '';
    public $orderAmount = 0;
    public $appliedCoupon = null;
    public $discountAmount = 0;
    public $newTotal = 0;
    public $error = '';
    public $success = '';
    public $availableCoupons = [];
    public $isLoading = false;
    public $finalAmountAfterCoupon = 0;

    protected $listeners = [
        'orderAmountUpdated' => 'updateOrderAmount',
        'clearCoupon' => 'clearAppliedCoupon'
    ];

    public function mount($orderAmount = 0)
    {
        $this->orderAmount = $orderAmount;
        $this->newTotal = $orderAmount;
        $this->loadAvailableCoupons();
    }

    public function updateOrderAmount($amount)
    {
        $this->orderAmount = $amount;
        // Unified discount calculation if coupon is applied
        if ($this->appliedCoupon) {
            $this->recalculateDiscount();
        } else {
            $this->newTotal = $amount;
            $this->finalAmountAfterCoupon = $amount;
        }
    }

    public function applyCoupon()
    {
        $this->reset(['error', 'success']);
        $this->isLoading = true;

        if (empty($this->code)) {
            $this->error = 'Please enter a coupon code.';
            $this->isLoading = false;
            return;
        }

        try {
            $coupon = Coupon::whereRaw('LOWER(code) = ?', [strtolower($this->code)])->first();

            if (!$coupon) {
                $this->error = 'Coupon not found.';
                $this->dispatch('coupon-removed');
                $this->isLoading = false;
                return;
            }

            if (!$coupon->isValid()) {
                $this->error = 'Coupon is not valid (expired, inactive, or usage limit reached).';
                $this->dispatch('coupon-removed');
                $this->isLoading = false;
                return;
            }

            if ($this->orderAmount < $coupon->minimum_order_amount) {
                $this->error = "Order must be at least ₹{$coupon->minimum_order_amount} to use this coupon.";
                $this->dispatch('coupon-removed');
                $this->isLoading = false;
                return;
            }

            // Unified discount calculation
            $discount70 = round($this->orderAmount * 0.70, 2);
            $after70 = $this->orderAmount - $discount70;
            $discount15 = round($after70 * 0.15, 2);
            $after15 = $after70 - $discount15;
            $packing = round($after15 * 0.05, 2);
            $netAmount = $after15 + $packing;

            // Apply coupon to netAmount
            if ($coupon->type === 'fixed_amount') {
                $discountAmount = min($coupon->value, $netAmount);
            } else {
                $discountAmount = $coupon->calculateDiscount($netAmount);
            }
            if ($discountAmount <= 0) {
                $this->error = 'Coupon does not apply to this order.';
                $this->dispatch('coupon-removed');
                $this->isLoading = false;
                return;
            }

            $this->finalAmountAfterCoupon = $netAmount - $discountAmount;
            $this->appliedCoupon = $coupon;
            $this->discountAmount = $discountAmount;
            $this->newTotal = $this->finalAmountAfterCoupon;
            $this->success = "Coupon applied! Discount: ₹{$discountAmount}";

            $this->dispatch('coupon-applied', [
                'coupon_code' => $coupon->code,
                'discount_amount' => $discountAmount,
                'new_total' => $this->finalAmountAfterCoupon
            ]);

        } catch (\Exception $e) {
            Log::error('Coupon application error: ' . $e->getMessage());
            $this->error = 'Error applying coupon. Please try again.';
            $this->dispatch('coupon-removed');
        }

        $this->isLoading = false;
    }

    public function removeCoupon()
    {
        $this->reset(['appliedCoupon', 'discountAmount', 'success', 'error']);
        $this->newTotal = $this->orderAmount;
        $this->code = '';

        $this->dispatch('coupon-removed');
    }

    public function clearAppliedCoupon()
    {
        $this->removeCoupon();
    }

    public function recalculateDiscount()
    {
        if (!$this->appliedCoupon) {
            return;
        }
        // Unified discount calculation
        $discount70 = round($this->orderAmount * 0.70, 2);
        $after70 = $this->orderAmount - $discount70;
        $discount15 = round($after70 * 0.15, 2);
        $after15 = $after70 - $discount15;
        $packing = round($after15 * 0.05, 2);
        $netAmount = $after15 + $packing;
        if ($this->appliedCoupon->type === 'fixed_amount') {
            $discountAmount = min($this->appliedCoupon->value, $netAmount);
        } else {
            $discountAmount = $this->appliedCoupon->calculateDiscount($netAmount);
        }
        $this->finalAmountAfterCoupon = $netAmount - $discountAmount;
        $this->discountAmount = $discountAmount;
        $this->newTotal = $this->finalAmountAfterCoupon;
        $this->dispatch('coupon-updated', [
            'coupon_code' => $this->appliedCoupon->code,
            'discount_amount' => $discountAmount,
            'new_total' => $this->finalAmountAfterCoupon
        ]);
    }

    public function loadAvailableCoupons()
    {
        try {
            $this->availableCoupons = Coupon::where('is_active', true)
                ->where('expires_at', '>', now())
                ->where('usage_limit', '>', 0)
                ->orderBy('discount_value', 'desc')
                ->limit(5)
                ->get(['code', 'description', 'discount_type', 'discount_value', 'minimum_order_amount']);
        } catch (\Exception $e) {
            Log::error('Error loading available coupons: ' . $e->getMessage());
        }
    }

    public function applyQuickCoupon($code)
    {
        $this->code = $code;
        $this->applyCoupon();
    }

    public function render()
    {
        return view('livewire.smart-coupon-system');
    }
} 