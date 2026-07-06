@extends('layouts.admin')

@section('title', 'Edit Coupon')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Coupon: {{ $coupon->name }}</h1>
            <a href="{{ route('admin.coupons') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Coupons
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Basic Information</h3>
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Coupon Code <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <input type="text" id="code" name="code" value="{{ old('code', $coupon->code) }}" class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror" required>
                                <button type="button" onclick="generateCouponCode()" class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition duration-200 ml-2">Generate Code</button>
                            </div>
                            @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Coupon Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $coupon->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $coupon->description) }}</textarea>
                            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Value & Settings</h3>
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Coupon Type <span class="text-red-500">*</span></label>
                            <select id="type" name="type" onchange="updateValueField()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror" required>
                                <option value="">Select Type</option>
                                <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percentage Discount</option>
                                <option value="fixed_amount" {{ old('type', $coupon->type) == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount Discount</option>
                            </select>
                            @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div id="value-field-wrapper">
                            <label for="value" id="value-label" class="block text-sm font-medium text-gray-700 mb-2">Value <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" id="value" name="value" value="{{ old('value', $coupon->value) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10 @error('value') border-red-500 @enderror" required>
                                <span id="value-suffix" class="absolute inset-y-0 right-3 flex items-center text-gray-400 pointer-events-none"></span>
                    </div>
                            @error('value')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="minimum_order_amount" class="block text-sm font-medium text-gray-700 mb-2">Minimum Order Amount</label>
                            <input type="number" id="minimum_order_amount" name="minimum_order_amount" value="{{ old('minimum_order_amount', $coupon->minimum_order_amount) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('minimum_order_amount') border-red-500 @enderror">
                            @error('minimum_order_amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                <div class="mt-8 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Usage Limits</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-2">Total Usage Limit</label>
                            <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('usage_limit') border-red-500 @enderror" placeholder="0 or empty for unlimited">
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Usage: {{ $coupon->usage_display }}</span>
                                        @if($coupon->hasReachedUsageLimit())
                                            <span class="ml-2 text-xs text-red-600 font-medium">(LIMIT REACHED)</span>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @if($coupon->usage_limit && $coupon->usage_limit > 0)
                                            <span class="text-xs text-gray-500">{{ $coupon->usage_limit - $coupon->used_count }} remaining</span>
                                        @else
                                            <span class="text-xs text-green-600">Unlimited</span>
                                        @endif
                                    </div>
                                </div>
                                @if($coupon->hasReachedUsageLimit())
                                    <div class="mt-2 text-xs text-red-600">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        This coupon will be automatically expired when limit is reached
                                    </div>
                                @endif
                            </div>
                            @error('usage_limit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="user_limit" class="block text-sm font-medium text-gray-700 mb-2">Per User Usage Limit</label>
                            <input type="number" id="user_limit" name="user_limit" value="{{ old('user_limit', $coupon->user_limit) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_limit') border-red-500 @enderror" placeholder="0 or empty for unlimited">
                            @error('user_limit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                <div class="mt-8 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Validity Period</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                            <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('starts_at') border-red-500 @enderror">
                            @error('starts_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                            <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('expires_at') border-red-500 @enderror">
                            @error('expires_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Inactive coupons cannot be used by customers</p>
                </div>
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('admin.coupons') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">Cancel</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200"><i class="fas fa-save mr-2"></i>Update Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateValueField() {
    var type = document.getElementById('type').value;
    var label = document.getElementById('value-label');
    var valueInput = document.getElementById('value');
    var suffix = document.getElementById('value-suffix');
    if (type === 'percentage') {
        label.textContent = 'Discount Percentage (%) *';
        valueInput.setAttribute('max', '100');
        valueInput.setAttribute('min', '0');
        valueInput.setAttribute('step', '0.01');
        suffix.textContent = '%';
        valueInput.placeholder = '0-100';
    } else if (type === 'fixed_amount') {
        label.textContent = 'Discount Amount (₹) *';
        valueInput.removeAttribute('max');
        valueInput.setAttribute('min', '0');
        valueInput.setAttribute('step', '0.01');
        suffix.textContent = '₹';
        valueInput.placeholder = 'Enter amount';
    } else if (type === 'bonus_items') {
        label.textContent = 'Bonus Quantity *';
        valueInput.removeAttribute('max');
        valueInput.setAttribute('min', '1');
        valueInput.setAttribute('step', '1');
        suffix.textContent = '';
        valueInput.placeholder = 'Enter quantity';
    } else {
        label.textContent = 'Value *';
            valueInput.removeAttribute('max');
        valueInput.setAttribute('min', '0');
        valueInput.setAttribute('step', '0.01');
        suffix.textContent = '';
        valueInput.placeholder = '';
    }
}
function randomCouponCode(length = 8) {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let code = '';
    for (let i = 0; i < length; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return code;
}
function generateCouponCode() {
    let codeInput = document.getElementById('code');
    let tryCount = 0;
    function tryGenerate() {
        const code = randomCouponCode();
        fetch('/admin/coupons/check-code-unique?code=' + code)
        .then(response => response.json())
        .then(data => {
                if (data.unique) {
                    codeInput.value = code;
                } else if (++tryCount < 5) {
                    tryGenerate();
                } else {
                    alert('Could not generate a unique code. Please try again.');
                }
            })
            .catch(() => {
                codeInput.value = randomCouponCode(); // fallback, not guaranteed unique
            });
    }
    tryGenerate();
}
document.addEventListener('DOMContentLoaded', updateValueField);
</script>
@endsection 