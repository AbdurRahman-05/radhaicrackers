<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
        </svg>
        Smart Discounts
    </h2>
    
    <!-- Applied Coupon Display -->
    @if($appliedCoupon)
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium text-green-800">Coupon Applied: {{ $appliedCoupon->code }}</span>
                    </div>
                    <p class="text-sm text-green-700 mt-1">{{ $appliedCoupon->description }}</p>
                    <p class="text-sm text-green-700">
                        Discount: ₹
                        @if($appliedCoupon->type === 'percentage')
                            {{ number_format($discountAmount, 2) }} ({{ $appliedCoupon->value }}% Off)
                        @elseif($appliedCoupon->type === 'fixed_amount')
                            {{ number_format($discountAmount, 2) }} (Fixed Amount)
                        @else
                            {{ number_format($discountAmount, 2) }}
                        @endif
                    </p>
                </div>
                <button wire:click="removeCoupon" class="text-red-600 hover:text-red-800 text-sm font-medium">
                    Remove
                </button>
            </div>
        </div>
    @endif
    
    <!-- Error Message -->
    @if($error)
        <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ $error }}
        </div>
    @endif
    
    <!-- Success Message -->
    @if($success)
        <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ $success }}
        </div>
    @endif
    
    <!-- Coupon Input -->
    <div class="flex space-x-2 mb-4">
        <input 
            type="text" 
            wire:model="code" 
            wire:keydown.enter="applyCoupon"
            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
            placeholder="Enter coupon code"
            @if($isLoading) disabled @endif
        >
        <button 
            type="button" 
            wire:click="applyCoupon" 
            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            @if($isLoading) disabled @endif
        >
            @if($isLoading)
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Applying...
            @else
                Apply
            @endif
        </button>
    </div>
    
    <!-- Available Coupons -->
    @if($availableCoupons->count() > 0)
        <div class="mt-6">
            <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Available Coupons
            </h3>
            <div class="space-y-2">
                @foreach($availableCoupons as $coupon)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-900">{{ $coupon->code }}</span>
                                @if($coupon->discount_type === 'percentage')
                                    <span class="ml-2 px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">
                                        {{ $coupon->discount_value }}% OFF
                                    </span>
                                @else
                                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                        ₹{{ $coupon->discount_value }} OFF
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $coupon->description }}</p>
                            <p class="text-xs text-gray-500 mt-1">Min. order: ₹{{ number_format($coupon->minimum_order_amount, 2) }}</p>
                        </div>
                        <button 
                            wire:click="applyQuickCoupon('{{ $coupon->code }}')"
                            class="ml-3 px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-md transition duration-200"
                            @if($isLoading) disabled @endif
                        >
                            Use
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    <!-- Coupon Information -->
    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-start">
            <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">How to use coupons:</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Enter the coupon code and click "Apply"</li>
                    <li>Only one coupon can be used per order</li>
                    <li>Coupons have minimum order requirements</li>
                    <li>Some coupons may have usage limits</li>
                </ul>
            </div>
        </div>
    </div>
</div> 