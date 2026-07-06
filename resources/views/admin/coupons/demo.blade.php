@extends('layouts.admin')

@section('title', 'Coupon System Demo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Coupon System Demo</h1>
            <p class="text-gray-600 mt-2">Test the coupon system functionality with sample data</p>
        </div>

        <!-- Demo Coupons -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Available Demo Coupons</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $demoCoupons = [
                        ['code' => 'WELCOME20', 'name' => 'Welcome Discount', 'type' => 'percentage', 'value' => '20%', 'min_order' => '₹500'],
                        ['code' => 'FLAT100', 'name' => 'Flat ₹100 Off', 'type' => 'fixed', 'value' => '₹100', 'min_order' => '₹1000'],
                        ['code' => 'BONUSGIFT', 'name' => 'Free Bonus Item', 'type' => 'bonus', 'value' => '1 Free Item', 'min_order' => '₹800'],
                        ['code' => 'SUMMER50', 'name' => 'Summer Sale', 'type' => 'percentage', 'value' => '50%', 'min_order' => '₹2000'],
                        ['code' => 'NEWUSER', 'name' => 'New User Special', 'type' => 'fixed', 'value' => '₹200', 'min_order' => '₹1500'],
                    ];
                @endphp

                @foreach($demoCoupons as $coupon)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $coupon['code'] }}</span>
                        <span class="text-xs text-gray-500">{{ $coupon['type'] }}</span>
                    </div>
                    <h3 class="font-medium text-gray-900">{{ $coupon['name'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $coupon['value'] }} off</p>
                    <p class="text-xs text-gray-500 mt-1">Min order: {{ $coupon['min_order'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Coupon Tester -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Coupon Tester</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Input Form -->
                <div>
                    <form id="couponTestForm" class="space-y-4">
                        <div>
                            <label for="couponCode" class="block text-sm font-medium text-gray-700 mb-2">
                                Coupon Code
                            </label>
                            <input type="text" id="couponCode" name="coupon_code" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                                   placeholder="Enter coupon code">
                        </div>

                        <div>
                            <label for="orderAmount" class="block text-sm font-medium text-gray-700 mb-2">
                                Order Amount (₹)
                            </label>
                            <input type="number" id="orderAmount" name="order_amount" value="1500" min="0" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>

                        <div>
                            <label for="productCategory" class="block text-sm font-medium text-gray-700 mb-2">
                                Product Category (Optional)
                            </label>
                            <select id="productCategory" name="category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                                <option value="">All Categories</option>
                                <option value="Summer Collection">Summer Collection</option>
                                <option value="Festival">Festival</option>
                                <option value="Wedding">Wedding</option>
                            </select>
                        </div>

                        <button type="submit" 
                                class="w-full bg-orange-600 text-white font-bold py-2 px-4 rounded-md hover:bg-orange-700 transition duration-200">
                            <i class="fas fa-ticket-alt mr-2"></i>Test Coupon
                        </button>
                    </form>
                </div>

                <!-- Results -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Test Results</h3>
                    <div id="testResults" class="space-y-3">
                        <div class="text-gray-500 text-center py-8">
                            <i class="fas fa-ticket-alt text-4xl text-gray-300 mb-2"></i>
                            <p>Enter a coupon code to test</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Coupons -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Available Coupons for Current Order</h2>
            <div id="availableCoupons" class="space-y-3">
                <div class="text-gray-500 text-center py-8">
                    <i class="fas fa-spinner fa-spin text-4xl text-gray-300 mb-2"></i>
                    <p>Loading available coupons...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/coupon-utils.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const couponUtils = new CouponUtils();
    const form = document.getElementById('couponTestForm');
    const resultsDiv = document.getElementById('testResults');
    const availableCouponsDiv = document.getElementById('availableCoupons');
    const orderAmountInput = document.getElementById('orderAmount');
    const categorySelect = document.getElementById('productCategory');

    // Load available coupons
    async function loadAvailableCoupons() {
        const orderAmount = parseFloat(orderAmountInput.value) || 0;
        const category = categorySelect.value;
        
        const coupons = await couponUtils.getAvailableCoupons(orderAmount, category);
        
        if (coupons.length > 0) {
            availableCouponsDiv.innerHTML = coupons.map(coupon => `
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-mono text-sm bg-green-100 text-green-800 px-2 py-1 rounded">${coupon.code}</span>
                        <span class="text-xs text-gray-500">${coupon.type}</span>
                    </div>
                    <h3 class="font-medium text-gray-900">${coupon.name}</h3>
                    <p class="text-sm text-gray-600">${coupon.display_text}</p>
                    ${coupon.description ? `<p class="text-xs text-gray-500 mt-1">${coupon.description}</p>` : ''}
                    ${coupon.minimum_order_amount > 0 ? `<p class="text-xs text-gray-500">Min order: ₹${coupon.minimum_order_amount}</p>` : ''}
                    ${coupon.expires_at ? `<p class="text-xs text-gray-500">Expires: ${new Date(coupon.expires_at).toLocaleDateString()}</p>` : ''}
                </div>
            `).join('');
        } else {
            availableCouponsDiv.innerHTML = `
                <div class="text-gray-500 text-center py-8">
                    <i class="fas fa-info-circle text-4xl text-gray-300 mb-2"></i>
                    <p>No coupons available for this order</p>
                </div>
            `;
        }
    }

    // Handle form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const code = document.getElementById('couponCode').value.trim();
        const orderAmount = parseFloat(orderAmountInput.value) || 0;
        
        if (!code) {
            couponUtils.showErrorMessage('Please enter a coupon code');
            return;
        }

        // Show loading
        resultsDiv.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin text-2xl text-orange-500"></i>
                <p class="text-gray-600 mt-2">Validating coupon...</p>
            </div>
        `;

        try {
            const result = await couponUtils.validateCoupon(code, orderAmount);
            
            if (result.valid) {
                const applied = couponUtils.applyCouponToOrder(result.coupon, orderAmount);
                
                resultsDiv.innerHTML = `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <h4 class="font-medium text-green-900">Coupon Applied Successfully!</h4>
                        </div>
                        
                        <div class="space-y-2 text-sm">
                            <p><strong>Coupon:</strong> ${result.coupon.name}</p>
                            <p><strong>Code:</strong> <span class="font-mono bg-green-100 px-2 py-1 rounded">${result.coupon.code}</span></p>
                            <p><strong>Type:</strong> ${result.coupon.type}</p>
                            <p><strong>Original Amount:</strong> ₹${orderAmount.toFixed(2)}</p>
                            <p><strong>Discount:</strong> ₹${applied.discountAmount.toFixed(2)}</p>
                            <p><strong>Final Amount:</strong> ₹${applied.newTotal.toFixed(2)}</p>
                            ${result.coupon.description ? `<p><strong>Description:</strong> ${result.coupon.description}</p>` : ''}
                        </div>
                    </div>
                `;
                
                couponUtils.showSuccessMessage(result.message);
            } else {
                resultsDiv.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                            <h4 class="font-medium text-red-900">Coupon Invalid</h4>
                        </div>
                        <p class="text-red-700">${result.message}</p>
                    </div>
                `;
                
                couponUtils.showErrorMessage(result.message);
            }
        } catch (error) {
            resultsDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <h4 class="font-medium text-red-900">Error</h4>
                    </div>
                    <p class="text-red-700">Network error. Please try again.</p>
                </div>
            `;
        }
    });

    // Reload available coupons when order amount or category changes
    orderAmountInput.addEventListener('change', loadAvailableCoupons);
    categorySelect.addEventListener('change', loadAvailableCoupons);

    // Initial load
    loadAvailableCoupons();
});
</script>
@endsection 