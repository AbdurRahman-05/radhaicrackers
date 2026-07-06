<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Coupon;

class CouponApply extends Component
{
    public $code;
    public $error;
    public $discount = 0;
    public $new_total = 0;
    public $coupon_code = null;
    public $orderAmount;
    public $success = null;

    public function mount($orderAmount)
    {
        $this->orderAmount = $orderAmount;
        $this->new_total = $orderAmount;
    }

    public function applyCoupon()
    {
        $this->reset(['success', 'error', 'discount', 'coupon_code', 'new_total']);
        $coupon = Coupon::whereRaw('LOWER(code) = ?', [strtolower($this->code)])->first();

        if (!$coupon) {
            $this->error = 'Coupon not found.';
            $this->dispatch('coupon-removed');
            return;
        }

        if (!$coupon->isValid()) {
            $this->error = 'Coupon is not valid (expired, inactive, or usage limit reached).';
            $this->dispatch('coupon-removed');
            return;
        }

        if ($this->orderAmount < $coupon->minimum_order_amount) {
            $this->error = 'Order does not meet minimum amount.';
            $this->dispatch('coupon-removed');
            return;
        }

        $discount = $coupon->calculateDiscount($this->orderAmount);
        if ($discount <= 0) {
            $this->error = 'Coupon does not apply to this order.';
            $this->dispatch('coupon-removed');
            return;
        }

        $this->discount = $discount;
        $this->new_total = $this->orderAmount - $discount;
        $this->coupon_code = $coupon->code;
        $this->success = 'Coupon applied!';

        $this->dispatch('coupon-applied', [
            'coupon_code' => $coupon->code,
            'discount_amount' => $discount,
            'new_total' => $this->new_total
        ]);
    }

    public function removeCoupon()
    {
        $this->code = null;
        $this->discount = 0;
        $this->new_total = 0;
        $this->coupon_code = null;
        session()->forget('coupon');
        $this->dispatch('coupon-removed');
    }

    public function render()
    {
        return view('livewire.coupon-apply');
    }
}
