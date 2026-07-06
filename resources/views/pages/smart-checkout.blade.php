@extends('layouts.app')

@section('title', 'Smart Checkout - Radhe Crackers')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Smart Checkout</h1>
        <p class="text-gray-600">Complete your order with real-time validation and smart discounts</p>
    </div>

    <!-- Progress Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-center max-w-lg mx-auto px-4">
            <div class="flex items-center flex-shrink-0">
                <div class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center text-sm font-bold">1</div>
                <span class="ml-2 text-sm font-medium text-blue-600 hidden sm:inline-block">Cart Review</span>
            </div>
            <div class="flex-grow border-t-2 border-gray-300 mx-2 sm:mx-4"></div>
            <div class="flex items-center flex-shrink-0">
                <div class="bg-gray-300 text-gray-600 rounded-full h-8 w-8 flex items-center justify-center text-sm font-bold">2</div>
                <span class="ml-2 text-sm font-medium text-gray-500 hidden sm:inline-block">Customer Details</span>
            </div>
            <div class="flex-grow border-t-2 border-gray-300 mx-2 sm:mx-4"></div>
            <div class="flex items-center flex-shrink-0">
                <div class="bg-gray-300 text-gray-600 rounded-full h-8 w-8 flex items-center justify-center text-sm font-bold">3</div>
                <span class="ml-2 text-sm font-medium text-gray-500 hidden sm:inline-block">Payment & Confirm</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Cart & Coupon -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Cart Summary -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    Cart Summary
                </h2>
                
                <div id="cart-items-container" class="space-y-3">
                    <!-- Cart items will be populated here -->
                </div>
                
                <div class="border-t pt-4 mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">Subtotal:</span>
                        <span id="cart-subtotal">₹0.00</span>
                    </div>
                </div>
            </div>

            <!-- Smart Coupon System -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    Smart Discounts
                </h2>
                
                <!-- Coupon Input -->
                <div class="flex space-x-2 mb-4">
                    <input type="text" id="coupon-code" class="flex-1 min-w-0 border border-gray-300 rounded-lg px-3 py-2 sm:px-4 sm:py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm sm:text-base" placeholder="Enter coupon code">
                    <button type="button" id="apply-coupon" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg font-medium transition duration-200 text-sm sm:text-base flex-shrink-0">
                        Apply
                    </button>
                </div>
                
                <!-- Coupon Status -->
                <div id="coupon-status" class="hidden">
                    <!-- Success/Error messages will appear here -->
                </div>
                
                <!-- Available Coupons -->
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Available Coupons:</h3>
                    <div id="available-coupons" class="space-y-2">
                        <!-- Available coupons will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Customer Information Form -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Customer Information
                </h2>
                
                <form id="customer-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="customer_name" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number *</label>
                            <input type="tel" name="customer_mobile" required maxlength="10" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="customer_email" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                            <select name="customer_state" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select State</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Kerala">Kerala</option>
                                <option value="Karnataka">Karnataka</option>
                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                <option value="Telangana">Telangana</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">District *</label>
                            <input type="text" name="customer_district" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                            <input type="text" name="customer_city" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Point *</label>
                            <input type="text" name="delivery_point" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pin Code *</label>
                            <input type="text" name="pin_code" required maxlength="6" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 sticky top-4">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Order Summary
                </h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span>Order Value:</span>
                        <span id="order-value">₹0.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-green-600">
                        <span>70% Discount:</span>
                        <span id="discount-70">-₹0.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-green-600">
                        <span>15% Special Discount:</span>
                        <span id="discount-15">-₹0.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-purple-600">
                        <span>Coupon Discount:</span>
                        <span id="coupon-discount">-₹0.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-orange-600">
                        <span>Packing Charge (5%):</span>
                        <span id="packing-charge">₹0.00</span>
                    </div>
                    <hr class="border-gray-300">
                    <div class="flex justify-between text-lg font-bold text-gray-900">
                        <span>Total Amount:</span>
                        <span id="final-total">₹0.00</span>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-6 space-y-3">
                    <button type="button" id="place-order-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="btn-text">Place Order</span>
                        <span id="btn-loading" class="hidden">Processing...</span>
                    </button>
                    
                    <!--<button type="button" id="save-draft-btn" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">-->
                    <!--    Save as Draft-->
                    <!--</button>-->
                    
                    <a href="{{ route('shop') }}" class="block w-full text-center bg-white border border-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg hover:bg-gray-50 transition duration-200">
                        Continue Shopping
                    </a>
                </div>
                
                <!-- Security Notice -->
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center text-sm text-blue-800">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        Secure checkout with SSL encryption
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for submission -->
<form id="order-submission-form" method="POST" action="{{ route('smart-checkout.submit') }}" class="hidden">
    @csrf
    <input type="hidden" name="items" id="order-items-json">
    <input type="hidden" name="coupon_code" id="order-coupon-code">
    <input type="hidden" name="coupon_discount" id="order-coupon-discount">
    <input type="hidden" name="total" id="order-total">
    <input type="hidden" name="clear_cart" value="true">
</form>

<script>
// Smart Checkout JavaScript
class SmartCheckout {
    constructor() {
        this.cartItems = [];
        this.couponData = null;
        this.orderValue = 0;
        this.finalTotal = 0;
        this.isProcessing = false;
        
        // Clear any previously stored data on page load
        this.clearPreviousSessionData();
        
        this.initializeEventListeners();
        this.loadCart();
        this.updateDisplay();
    }
    
    clearPreviousSessionData() {
        // Clear any previously stored coupon data
        sessionStorage.removeItem('appliedCoupon');
        sessionStorage.removeItem('checkout-draft');
        
        // Reset form to clean state
        const form = document.getElementById('customer-form');
        if (form) {
            form.reset();
        }
        
        // Clear coupon input and status
        const couponInput = document.getElementById('coupon-code');
        const couponStatus = document.getElementById('coupon-status');
        if (couponInput) {
            couponInput.value = '';
            couponInput.disabled = false;
        }
        if (couponStatus) {
            couponStatus.classList.add('hidden');
        }
        
        // Reset apply button
        const applyBtn = document.getElementById('apply-coupon');
        if (applyBtn) {
            applyBtn.style.display = 'block';
            applyBtn.disabled = false;
        }
    }
    
    initializeEventListeners() {
        const applyBtn = document.getElementById('apply-coupon');
        const couponInput = document.getElementById('coupon-code');
        
        // Coupon application
        applyBtn.addEventListener('click', () => {
            if (!this.couponData) {
                this.applyCoupon();
            }
        });
        
        couponInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !this.couponData) {
                this.applyCoupon();
            }
        });
        
        // Disable coupon input if already applied
        if (this.couponData) {
            couponInput.disabled = true;
            applyBtn.style.display = 'none';
        }
        
        // Form submission
        document.getElementById('place-order-btn').addEventListener('click', () => this.submitOrder());
        // document.getElementById('save-draft-btn').addEventListener('click', () => this.saveDraft());
        
        // Real-time form validation
        document.getElementById('customer-form').addEventListener('input', () => this.validateForm());
        
        // Handle page refresh/back button
        window.addEventListener('beforeunload', () => {
            this.clearPreviousSessionData();
        });
        
        // Handle page visibility change (tab switching)
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                // Refresh CSRF token when page becomes visible
                this.refreshCSRFToken();
            }
        });
    }
    
    loadCart() {
        const cartData = localStorage.getItem('cartItems');
        if (cartData) {
            this.cartItems = JSON.parse(cartData);
            this.calculateTotals();
        }
    }
    
    calculateTotals() {
        this.orderValue = this.cartItems.reduce((total, item) => {
            // Always use original_price for order value calculation
            const originalPrice = (typeof item.original_price !== 'undefined' && item.original_price !== null) ? item.original_price : (item.rate || item.price || 0);
            return total + (originalPrice * (item.quantity || item.qty || 0));
        }, 0);
        
        // Apply discounts
        const discount70 = Math.round(this.orderValue * 0.7 * 100) / 100;
        const afterDiscount70 = this.orderValue - discount70;
        const discount15 = Math.round(afterDiscount70 * 0.15 * 100) / 100;
        const afterDiscount15 = afterDiscount70 - discount15;
        const packingCharge = Math.round(afterDiscount15 * 0.05 * 100) / 100;
        
        let finalTotal = afterDiscount15 + packingCharge;
        
        // Apply coupon discount if available
        if (this.couponData) {
            finalTotal -= this.couponData.discount_amount;
        }
        
        this.finalTotal = Math.round(finalTotal * 100) / 100;
        
        this.updateDisplay();
    }
    
    updateDisplay() {
        // Update cart items
        const container = document.getElementById('cart-items-container');
        if (this.cartItems.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-center py-4">Your cart is empty</p>';
            return;
        }
        
        let html = '';
        this.cartItems.forEach((item, index) => {
            const name = item.product_name || item.name || item.item_name || 'Product';
            const qty = item.quantity || item.qty || 0;
            const originalPrice = (typeof item.original_price !== 'undefined' && item.original_price !== null) ? item.original_price : (item.rate || item.price || 0);
            const total = originalPrice * qty;
            
            html += `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${name}</h4>
                        <p class="text-sm text-gray-600">Qty: ${qty} × ₹${originalPrice.toFixed(2)}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">₹${total.toFixed(2)}</p>
                        <button onclick="smartCheckout.removeItem(${index})" class="text-red-600 text-sm hover:text-red-800">Remove</button>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
        
        // Update summary
        const discount70 = Math.round(this.orderValue * 0.7 * 100) / 100;
        const afterDiscount70 = this.orderValue - discount70;
        const discount15 = Math.round(afterDiscount70 * 0.15 * 100) / 100;
        const afterDiscount15 = afterDiscount70 - discount15;
        const packingCharge = Math.round(afterDiscount15 * 0.05 * 100) / 100;
        
        document.getElementById('order-value').textContent = `₹${this.orderValue.toFixed(2)}`;
        document.getElementById('discount-70').textContent = `-₹${discount70.toFixed(2)}`;
        document.getElementById('discount-15').textContent = `-₹${discount15.toFixed(2)}`;
        document.getElementById('coupon-discount').textContent = `-₹${this.couponData ? this.couponData.discount_amount.toFixed(2) : '0.00'}`;
        document.getElementById('packing-charge').textContent = `₹${packingCharge.toFixed(2)}`;
        document.getElementById('final-total').textContent = `₹${this.finalTotal.toFixed(2)}`;
        document.getElementById('cart-subtotal').textContent = `₹${this.orderValue.toFixed(2)}`;
    }
    
    async applyCoupon() {
        const code = document.getElementById('coupon-code').value.trim();
        const applyBtn = document.getElementById('apply-coupon');
        const couponInput = document.getElementById('coupon-code');
        
        if (!code) {
            this.showCouponStatus('Please enter a coupon code', 'error');
            return;
        }

        // Calculate the current order total before applying coupon
        const currentTotal = this.finalTotal;
        if (currentTotal <= 0) {
            this.showCouponStatus('Please add items to your cart before applying a coupon', 'error');
            return;
        }
        
        // Disable input and button while processing
        couponInput.disabled = true;
        applyBtn.disabled = true;
        
        try {
            // Get fresh CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token not found. Please refresh the page and try again.');
            }
            
            const response = await fetch('/api/coupons/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    code: code,
                    order_amount: currentTotal
                })
            });
            
            if (!response.ok) {
                if (response.status === 419) {
                    throw new Error('Session expired. Please refresh the page and try again.');
                } else if (response.status === 422) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Invalid coupon code.');
                } else {
                    throw new Error(`Server error (${response.status}). Please try again.`);
                }
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.couponData = {
                    code: data.coupon.code,
                    discount_amount: data.discount_amount,
                    new_total: data.new_total
                };
                
                // Store in session storage
                sessionStorage.setItem('appliedCoupon', JSON.stringify(this.couponData));
                
                this.calculateTotals();
                this.showCouponStatus(`Coupon applied! Discount: ₹${data.discount_amount.toFixed(2)}`, 'success');
                
                // Hide apply button and keep input disabled
                applyBtn.style.display = 'none';
                
                // Add remove coupon button
                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'Remove Coupon';
                removeBtn.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm';
                removeBtn.onclick = () => this.removeCoupon();
                applyBtn.parentNode.appendChild(removeBtn);
            } else {
                this.showCouponStatus(data.message, 'error');
                // Re-enable input and button on error
                couponInput.disabled = false;
                applyBtn.disabled = false;
            }
        } catch (error) {
            this.showCouponStatus('Error applying coupon. Please try again.', 'error');
            // Re-enable input and button on error
            couponInput.disabled = false;
            applyBtn.disabled = false;
        }
    }
    
    removeCoupon() {
        const couponInput = document.getElementById('coupon-code');
        const applyBtn = document.getElementById('apply-coupon');
        
        // Clear coupon data
        this.couponData = null;
        sessionStorage.removeItem('appliedCoupon');
        
        // Reset UI
        couponInput.value = '';
        couponInput.disabled = false;
        applyBtn.style.display = 'block';
        applyBtn.disabled = false;
        
        // Remove the remove button
        const removeBtn = applyBtn.parentNode.querySelector('button:last-child');
        if (removeBtn && removeBtn !== applyBtn) {
            removeBtn.remove();
        }
        
        // Recalculate totals
        this.calculateTotals();
        this.showCouponStatus('Coupon removed', 'success');
    }
    
    showCouponStatus(message, type) {
        const statusDiv = document.getElementById('coupon-status');
        const className = type === 'success' ? 'bg-green-100 border-green-300 text-green-800' : 'bg-red-100 border-red-300 text-red-800';
        
        statusDiv.className = `p-3 rounded-lg border ${className}`;
        statusDiv.innerHTML = message;
        statusDiv.classList.remove('hidden');
        
        setTimeout(() => {
            statusDiv.classList.add('hidden');
        }, 5000);
    }
    
    removeItem(index) {
        this.cartItems.splice(index, 1);
        localStorage.setItem('cartItems', JSON.stringify(this.cartItems));
        this.calculateTotals();
    }
    
    validateForm() {
        const form = document.getElementById('customer-form');
        const submitBtn = document.getElementById('place-order-btn');
        const isValid = form.checkValidity();
        
        submitBtn.disabled = !isValid || this.cartItems.length === 0 || this.isProcessing;
    }
    
    async submitOrder() {
        if (this.isProcessing) return;
        
        const form = document.getElementById('customer-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        if (this.cartItems.length === 0) {
            alert('Your cart is empty. Please add items before placing an order.');
            return;
        }
        
        this.isProcessing = true;
        this.updateButtonState(true);
        
        // Clear coupon data from session storage when order is submitted
        sessionStorage.removeItem('appliedCoupon');
        
        // Prepare form data
        const formData = new FormData(form);
        formData.append('items', JSON.stringify(this.cartItems));
        formData.append('coupon_code', this.couponData ? this.couponData.code : '');
        formData.append('coupon_discount', this.couponData ? this.couponData.discount_amount : 0);
        formData.append('total', this.finalTotal);
        formData.append('clear_cart', 'true');
        
        try {
            // Get fresh CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token not found. Please refresh the page and try again.');
            }
            
            const response = await fetch('{{ route("smart-checkout.submit") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            // Check if response is ok
            if (!response.ok) {
                if (response.status === 419) {
                    throw new Error('Session expired. Please refresh the page and try again.');
                } else if (response.status === 422) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Validation error. Please check your information.');
                } else {
                    throw new Error(`Server error (${response.status}). Please try again.`);
                }
            }
            
            const result = await response.json();
            
            if (result.success) {
                // Clear all session data
                this.clearPreviousSessionData();
                localStorage.removeItem('cartItems');
                
                // Show success message before redirect
                this.showSuccessMessage('Order placed successfully! Redirecting...');
                
                // Redirect to order confirmation
                setTimeout(() => {
                    window.location.href = result.redirect_url;
                }, 1500);
            } else {
                throw new Error(result.message || 'Error placing order. Please try again.');
            }
        } catch (error) {
            console.error('Order submission error:', error);
            this.showErrorMessage(error.message || 'Network error. Please check your connection and try again.');
        } finally {
            this.isProcessing = false;
            this.updateButtonState(false);
        }
    }
    
    // saveDraft() {
    //     const formData = new FormData(document.getElementById('customer-form'));
    //     const draft = {
    //         customer: Object.fromEntries(formData),
    //         cart: this.cartItems,
    //         coupon: this.couponData,
    //         timestamp: new Date().toISOString()
    //     };
        
    //     localStorage.setItem('checkout-draft', JSON.stringify(draft));
    //     alert('Draft saved successfully!');
    // }
    
    updateButtonState(loading) {
        const btn = document.getElementById('place-order-btn');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');
        
        if (loading) {
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
        } else {
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        }
    }
    
    showSuccessMessage(message) {
        this.showMessage(message, 'success');
    }
    
    showErrorMessage(message) {
        this.showMessage(message, 'error');
    }
    
    showMessage(message, type) {
        // Create or update message container
        let messageContainer = document.getElementById('order-message');
        if (!messageContainer) {
            messageContainer = document.createElement('div');
            messageContainer.id = 'order-message';
            messageContainer.className = 'fixed top-4 right-4 z-50 max-w-sm';
            document.body.appendChild(messageContainer);
        }
        
        const className = type === 'success' 
            ? 'bg-green-100 border-green-300 text-green-800' 
            : 'bg-red-100 border-red-300 text-red-800';
        
        messageContainer.innerHTML = `
            <div class="p-4 rounded-lg border ${className} shadow-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        ${type === 'success' 
                            ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                            : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
                        }
                    </svg>
                    <span class="font-medium">${message}</span>
                </div>
            </div>
        `;
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (messageContainer) {
                messageContainer.remove();
            }
        }, 5000);
    }
    
    async refreshCSRFToken() {
        try {
            const response = await fetch('/api/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                if (csrfMeta && data.csrf_token) {
                    csrfMeta.setAttribute('content', data.csrf_token);
                }
            }
        } catch (error) {
            console.warn('Failed to refresh CSRF token:', error);
        }
    }
}

// Initialize smart checkout
const smartCheckout = new SmartCheckout();
</script>
@endsection 