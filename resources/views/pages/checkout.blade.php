@extends('layouts.app')

@section('title', 'Checkout - Radhe Crackers')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6 text-center">Verify / Checkout Page</h1>
    <!-- Debug/Cart Summary Section -->
    <div id="cart-summary" class="mb-4 p-3 bg-gray-100 border border-gray-300 rounded text-sm">
        <strong>Cart Summary:</strong>
        <div id="cart-items-list">Loading cart...</div>
        <div id="cart-total-summary"></div>
    </div>
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
    <form method="GET" action="{{ route('checkout.form') }}" class="mb-4">
        <div class="flex space-x-2">
            <input type="text" name="coupon_code" value="{{ old('coupon_code', $coupon_code ?? '') }}" class="flex-1 border rounded px-3 py-2" placeholder="Enter coupon code">
            <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded">Apply</button>
            @if(!empty($coupon_code))
                <a href="{{ route('checkout.form', array_merge(request()->except('coupon_code'), ['coupon_code' => null])) }}" class="bg-red-500 text-white px-3 py-2 rounded">Remove</a>
            @endif
        </div>
        @if(session('coupon_error'))
            <div class="bg-red-100 border border-red-300 text-red-800 p-3 rounded mt-2">
                {{ session('coupon_error') }}
            </div>
        @elseif(session('coupon_success'))
            <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded mt-2">
                {{ session('coupon_success') }}
            </div>
        @endif
    </form>
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
        <div class="mt-6">
            <h2 class="text-lg font-bold mb-2">Payment Details</h2>
            <div class="space-y-1">
                <div class="flex justify-between"><span>Order Value</span><span id="order-value">{{ number_format($total, 2) }}</span></div>
                <div class="flex justify-between"><span>70% Discount</span><span id="discount-70">-{{ number_format($discount_70, 2) }}</span></div>
                <div class="flex justify-between"><span>15% Discount</span><span id="discount-15">-{{ number_format($discount_15, 2) }}</span></div>
                <div class="flex justify-between"><span>Coupon Discount</span><span id="checkout-coupon-discount">-{{ number_format($coupon_discount ?? 0, 2) }}</span></div>
                <div class="flex justify-between"><span>5% Packing Charge</span><span id="packing-charge">{{ number_format($packing_charge, 2) }}</span></div>
                <div class="flex justify-between font-bold"><span>Total</span><span id="checkout-total">{{ number_format($final_total, 2) }}</span></div>
            </div>
            <input type="hidden" name="coupon_code" value="{{ $coupon_code ?? '' }}">
            <input type="hidden" name="coupon_discount" value="{{ $coupon_discount ?? 0 }}">
            <input type="hidden" name="total" value="{{ $final_total }}">
            <input type="hidden" name="items" id="order-items-json">
            <input type="hidden" name="clear_cart" value="true">
        </div>
        <div class="mt-6 text-center">
            <button type="submit" class="hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg w-full text-lg transition" style="background-color: #1E093B;" id="submit-btn">
                <span id="submit-text">Confirm Payment</span>
                <span id="submit-loading" style="display:none;">Processing...</span>
            </button>
        </div>
    </form>
</div>
@if(empty($items) || $total == 0)
    <div class="bg-red-100 border border-red-300 text-red-800 p-3 rounded mb-4">
        Your cart is empty or total is zero. Please add items to your cart before checkout.
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('checkout-form').querySelector('button[type="submit"]').disabled = true;
    });
    </script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only run if we are missing ?items= in the URL
    if (!window.location.search.includes('items=')) {
        var cartItems = localStorage.getItem('cartItems');
        if (cartItems) {
            var itemsParam = encodeURIComponent(cartItems);
            // Calculate total
            var itemsArr = JSON.parse(cartItems);
            var total = 0;
            itemsArr.forEach(function(item) {
                var qty = item.quantity || item.qty || 0;
                var price = item.rate || item.price || 0;
                var itemTotal = item.total || (price * qty);
                total += itemTotal;
            });
            var totalParam = encodeURIComponent(total);
            // Preserve coupon_code if present
            var couponCode = '';
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('coupon_code')) {
                couponCode = '&coupon_code=' + encodeURIComponent(urlParams.get('coupon_code'));
            }
            // Redirect with items and total
            window.location.href = window.location.pathname + '?items=' + itemsParam + '&total=' + totalParam + couponCode;
        }
    }
});
</script>
<script>
function updateCartSummary() {
    var cartItems = JSON.parse(localStorage.getItem('cartItems') || '[]');
    var listDiv = document.getElementById('cart-items-list');
    var totalDiv = document.getElementById('cart-total-summary');
    var orderValue = 0;
    if (cartItems.length === 0) {
        listDiv.innerHTML = '<span class="text-red-600">Your cart is empty.</span>';
        totalDiv.innerHTML = '';
        return;
    }
    var html = '<ul class="list-disc pl-5">';
    cartItems.forEach(function(item) {
        var name = item.product_name || item.name || item.item_name || 'Product';
        var qty = item.quantity || item.qty || 0;
        var price = (typeof item.original_price !== 'undefined' && item.original_price !== null) ? item.original_price : (item.rate || item.price || 0);
        var total = price * qty;
        orderValue += total;
        html += `<li>${name} &times; ${qty} = ₹${total.toFixed(2)}</li>`;
    });
    html += '</ul>';
    listDiv.innerHTML = html;
    totalDiv.innerHTML = `<b>Order Value: ₹${orderValue.toFixed(2)}</b>`;
}

function injectCartItemsToForm() {
    var cartItems = JSON.parse(localStorage.getItem('cartItems') || '[]');
    document.getElementById('order-items-json').value = JSON.stringify(cartItems);
}

document.addEventListener('DOMContentLoaded', function() {
    updateCartSummary();
    injectCartItemsToForm();
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
        injectCartItemsToForm();
        submitBtn.disabled = true;
        submitText.style.display = 'none';
        submitLoading.style.display = 'inline';
        setTimeout(function() {
            localStorage.removeItem('cartItems');
        }, 1000);
    });
});
</script>
@endsection