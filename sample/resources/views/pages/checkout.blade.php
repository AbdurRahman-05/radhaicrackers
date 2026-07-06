@extends('layouts.app')

@section('title', 'Checkout - Radhe Crackers')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6 text-center">Verify / Checkout Page</h1>
    @if(session('message'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
    @if(session('success'))
        <div class="mb-4 p-3 bg-blue-100 border border-blue-400 text-blue-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    <div id="debug-section" style="background:#f3f3f3;border:1px solid #ccc;padding:10px;margin-bottom:16px;font-size:13px;">
        <b>Debug Info:</b><br>
        <div>Hidden input value: <span id="debug-items-value"></span></div>
        <div>localStorage.cartItems: <span id="debug-cart-items"></span></div>
        <button type="button" onclick="updateDebugInfo()" style="margin-top:4px;">Refresh Debug Info</button>
    </div>
    <form method="POST" action="{{ route('checkout.submit') }}" class="space-y-6 bg-white p-6 rounded-lg shadow" id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Name</label>
                <input type="text" name="customer_name" class="w-full border rounded px-3 py-2" required value="{{ old('customer_name') }}">
            </div>
            <div>
                <label class="block font-semibold mb-1">Mobile</label>
                <input type="text" name="customer_mobile" class="w-full border rounded px-3 py-2" required maxlength="10" value="{{ old('customer_mobile') }}">
            </div>
            <div>
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="customer_email" class="w-full border rounded px-3 py-2" value="{{ old('customer_email') }}">
            </div>
            <div>
                <label class="block font-semibold mb-1">State</label>
                <select name="customer_state" class="w-full border rounded px-3 py-2" required>
                    <option value="">Choose State</option>
                    @foreach($states as $state)
                        <option value="{{ $state }}" {{ old('customer_state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">District</label>
                <input type="text" name="customer_district" class="w-full border rounded px-3 py-2" required value="{{ old('customer_district') }}">
            </div>
            <div>
                <label class="block font-semibold mb-1">City</label>
                <input type="text" name="customer_city" class="w-full border rounded px-3 py-2" required value="{{ old('customer_city') }}">
            </div>
            <div>
                <label class="block font-semibold mb-1">Delivery Point</label>
                <input type="text" name="delivery_point" class="w-full border rounded px-3 py-2" required value="{{ old('delivery_point') }}">
            </div>
            <div>
                <label class="block font-semibold mb-1">Pin Code</label>
                <input type="text" name="pin_code" class="w-full border rounded px-3 py-2" required maxlength="6" value="{{ old('pin_code') }}">
            </div>
        </div>
        <div class="mt-4">
            <label class="block font-semibold mb-1">Verify Code (OTP)</label>
            <input type="text" name="verify_code" class="w-full border rounded px-3 py-2" required maxlength="6" value="{{ old('verify_code') }}">
        </div>
        @php
            $cartItems = json_decode(request()->input('items', '[]'), true);
        @endphp

        <div class="mt-6">
            @livewire('coupon-apply', [
                'orderAmount' => $final_total,
                'orderItems' => $cartItems
            ])
        </div>

        <div class="mt-6">
            <h2 class="text-lg font-bold mb-2">Payment Details</h2>
            <div class="space-y-1">
                <div class="flex justify-between"><span>Order Value</span><span id="order-value">{{ number_format($total, 2) }}</span></div>
                <div class="flex justify-between"><span>70% Discount</span><span id="discount-70">-{{ number_format($discount_70, 2) }}</span></div>
                <div class="flex justify-between"><span>15% Discount</span><span id="discount-15">-{{ number_format($discount_15, 2) }}</span></div>
                <div class="flex justify-between" id="coupon-discount-row">
                    <span>Coupon Discount</span>
                    <span id="checkout-coupon-discount">-0.00</span>
                </div>
                <div class="flex justify-between"><span>5% Packing Charge</span><span id="packing-charge">{{ number_format($packing_charge, 2) }}</span></div>
               
                <div class="flex justify-between font-bold"><span>Total</span><span id="checkout-total">{{ number_format($final_total, 2) }}</span></div>
            </div>
            <input type="hidden" name="total" id="hidden-total" value="{{ $final_total }}">
            <input type="hidden" name="items" id="order-items-json">
            <input type="hidden" name="clear_cart" value="true">
        </div>
        <input type="hidden" name="coupon_code" id="hidden-coupon-code" value="">
        <input type="hidden" name="coupon_discount" id="hidden-coupon-discount" value="">
        <div class="mt-6 text-center">
            <button type="submit" class="hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg w-full text-lg transition" style="background-color: #1E093B;" id="submit-btn">
                <span id="submit-text">Confirm Payment</span>
                <span id="submit-loading" style="display:none;">Processing...</span>
            </button>
        </div>
    </form>
</div>
@endsection

<script>
function updateDebugInfo() {
    document.getElementById('debug-items-value').textContent = document.getElementById('order-items-json').value;
    document.getElementById('debug-cart-items').textContent = localStorage.getItem('cartItems');
}

// Remove all test/demo cart data code. Only real cart data will be used.
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitLoading = document.getElementById('submit-loading');
    
    checkoutForm.addEventListener('submit', function(e) {
        var cartItems = JSON.parse(localStorage.getItem('cartItems') || '[]');
        if (cartItems.length === 0) {
            e.preventDefault();
            alert('Your cart is empty. Please add items to your cart before checkout.');
            return false;
        }
        
        // Prevent double submission
        submitBtn.disabled = true;
        submitText.style.display = 'none';
        submitLoading.style.display = 'inline';
        
        var mappedItems = cartItems.map(function(item) {
            return {
                product_name: item.product_name || item.name || item.item_name || '',
                content: item.content || item.packaging || item.content || '',
                rate: item.rate || item.price || 0,
                quantity: item.quantity || item.qty || 0,
                total: item.total || (item.price && item.qty ? item.price * item.qty : 0)
            };
        });
        document.getElementById('order-items-json').value = JSON.stringify(mappedItems);
        
        // Clear cart after successful submission
        setTimeout(function() {
            localStorage.removeItem('cartItems');
        }, 1000);
    });
});

document.addEventListener('livewire:load', function () {
    Livewire.on('coupon-applied', data => {
        // Use the absolute value for display and calculation
        let discount = data.discount_amount !== undefined ? Math.abs(parseFloat(data.discount_amount)) : 0;
        document.getElementById('checkout-coupon-discount').textContent = '-' + discount.toFixed(2);
        document.getElementById('hidden-coupon-discount').value = discount;

        let orderValue = parseFloat(document.getElementById('order-value').textContent.replace(/,/g, '')) || 0;
        let discount70 = Math.abs(parseFloat(document.getElementById('discount-70').textContent.replace(/,/g, ''))) || 0;
        let discount15 = Math.abs(parseFloat(document.getElementById('discount-15').textContent.replace(/,/g, ''))) || 0;
        let packing = parseFloat(document.getElementById('packing-charge').textContent.replace(/,/g, '')) || 0;

        // Calculate new total: orderValue - discount70 - discount15 - discount + packing
        let newTotal = orderValue - discount70 - discount15 - discount + packing;
        document.getElementById('checkout-total').textContent = newTotal.toFixed(2);
        document.getElementById('hidden-total').value = newTotal.toFixed(2);
    });
    Livewire.on('coupon-removed', () => {
        document.getElementById('checkout-total').textContent = '{{ number_format($final_total, 2) }}';
        document.getElementById('hidden-total').value = '{{ $final_total }}';
        document.getElementById('hidden-coupon-code').value = '';
        document.getElementById('hidden-coupon-discount').value = '';
        document.getElementById('coupon-discount-row').style.display = 'none';
        document.getElementById('checkout-coupon-discount').textContent = '-0.00';
    });
});
</script>