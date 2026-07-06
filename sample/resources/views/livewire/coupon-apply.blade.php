<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i class="fas fa-ticket-alt text-orange-500 mr-2"></i>
        Apply Coupon Code
    </h3>

    @if(!$appliedCoupon)
        <div class="space-y-4">
            <div>
                <label for="couponCode" class="block text-sm font-medium text-gray-700 mb-2">
                    Enter Coupon Code
                </label>
                <div class="flex">
                    <input type="text" id="couponCode" wire:model="couponCode" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-orange-500 @error('couponCode') border-red-500 @enderror"
                           placeholder="e.g., SUMMER20">
                    <button type="button" wire:click="applyCoupon" 
                            class="px-4 py-2 bg-orange-600 text-white rounded-r-md hover:bg-orange-700 transition duration-200">
                        <i class="fas fa-check mr-1"></i>Apply
                    </button>
                </div>
                @error('couponCode')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @if($couponError)
                <div class="bg-red-50 border border-red-200 rounded-md p-3">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                        <p class="text-red-700 text-sm ml-2">{{ $couponError }}</p>
                    </div>
                    @if(isset($debugCoupon))
                        <div class="mt-2 text-xs text-gray-500">
                            <b>Debug Info:</b><br>
                            Code: {{ $debugCoupon['code'] ?? '' }}<br>
                            Active: {{ $debugCoupon['is_active'] ?? '' }}<br>
                            Starts At: {{ $debugCoupon['starts_at'] ?? '' }}<br>
                            Expires At: {{ $debugCoupon['expires_at'] ?? '' }}<br>
                            Usage Limit: {{ $debugCoupon['usage_limit'] ?? '' }}<br>
                            Used Count: {{ $debugCoupon['used_count'] ?? '' }}<br>
                </div>
            @endif
                </div>
            @endif
        </div>
    @else
        <div class="bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <h4 class="font-medium text-green-900">{{ $appliedCoupon->name }}</h4>
                    </div>
                    <div class="space-y-1 text-sm">
                        <p class="text-green-700">
                            <span class="font-mono bg-green-100 px-2 py-1 rounded">{{ $appliedCoupon->code }}</span>
                        </p>
                                <p class="text-green-700">
                            @if($appliedCoupon->type === 'percentage')
                                    {{ $appliedCoupon->value }}% off
                                    @if($appliedCoupon->maximum_discount)
                                        (max ₹{{ $appliedCoupon->maximum_discount }})
                                @endif
                            @elseif($appliedCoupon->type === 'fixed_amount')
                                    ₹{{ $appliedCoupon->value }} off
                            @endif
                                </p>
                                    <p class="text-green-700 font-medium">
                            Discount: ₹{{ number_format($discountAmount, 2) }}
                        </p>
                        <p class="text-green-700 font-semibold">
                            New Total: ₹{{ number_format($orderAmount - $discountAmount, 2) }}
                        </p>
                        @if($appliedCoupon->applies_to_categories && count($appliedCoupon->applies_to_categories))
                            <p class="text-xs text-gray-500">Applies to: {{ implode(', ', $appliedCoupon->applies_to_categories) }}</p>
                        @else
                            <p class="text-xs text-gray-500">Applies to: All Products</p>
                                @endif
                        @if($appliedCoupon->excluded_products && count($appliedCoupon->excluded_products))
                            <p class="text-xs text-gray-500">Excludes: {{ implode(', ', $appliedCoupon->excluded_products) }}</p>
                                @endif
                        @if($appliedCoupon->description)
                            <p class="text-green-600 text-xs mt-2">{{ $appliedCoupon->description }}</p>
                        @endif
                    </div>
                </div>
                <button type="button" wire:click="removeCoupon" 
                        class="text-red-600 hover:text-red-800 transition duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="mt-4 text-xs text-gray-500">
        <p><i class="fas fa-info-circle mr-1"></i>Only one coupon can be used per order.</p>
        <p><i class="fas fa-info-circle mr-1"></i>Leave restrictions empty to apply to all products.</p>
    </div>
</div> 