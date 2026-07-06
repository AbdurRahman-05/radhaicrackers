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
                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Basic Information</h3>
                        
                        <!-- Coupon Code -->
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Coupon Code <span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <input type="text" id="code" name="code" value="{{ old('code', $coupon->code) }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                                       placeholder="Enter coupon code">
                                <button type="button" onclick="generateCode()" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition duration-200">
                                    <i class="fas fa-magic"></i>
                                </button>
                            </div>
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Coupon Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Coupon Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $coupon->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                   placeholder="e.g., Summer Sale 20% Off">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Optional description for this coupon">{{ old('description', $coupon->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Coupon Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Coupon Type <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" onchange="toggleTypeFields()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percentage Discount</option>
                                <option value="fixed_amount" {{ old('type', $coupon->type) == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount Discount</option>
                                <option value="bonus_items" {{ old('type', $coupon->type) == 'bonus_items' ? 'selected' : '' }}>Bonus Items</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Coupon Value & Settings -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Value & Settings</h3>
                        
                        <!-- Value Field (Dynamic based on type) -->
                        <div id="value-field">
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                                Value <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="value" name="value" value="{{ old('value', $coupon->value) }}" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('value') border-red-500 @enderror"
                                   placeholder="Enter value">
                            @error('value')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Maximum Discount (for percentage type) -->
                        <div id="max-discount-field" class="hidden">
                            <label for="maximum_discount" class="block text-sm font-medium text-gray-700 mb-2">
                                Maximum Discount Amount
                            </label>
                            <input type="number" id="maximum_discount" name="maximum_discount" value="{{ old('maximum_discount', $coupon->maximum_discount) }}" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('maximum_discount') border-red-500 @enderror"
                                   placeholder="e.g., 500">
                            @error('maximum_discount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bonus Product (for bonus_items type) -->
                        <div id="bonus-product-field" class="hidden">
                            <label for="bonus_product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Bonus Product <span class="text-red-500">*</span>
                            </label>
                            <select id="bonus_product_id" name="bonus_product_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bonus_product_id') border-red-500 @enderror">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('bonus_product_id', $coupon->bonus_product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->item_name }} (₹{{ $product->price }})
                                    </option>
                                @endforeach
                            </select>
                            @error('bonus_product_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bonus Quantity -->
                        <div id="bonus-quantity-field" class="hidden">
                            <label for="bonus_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Bonus Quantity <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="bonus_quantity" name="bonus_quantity" value="{{ old('bonus_quantity', $coupon->bonus_quantity) }}" min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bonus_quantity') border-red-500 @enderror">
                            @error('bonus_quantity')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Minimum Order Amount -->
                        <div>
                            <label for="minimum_order_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Minimum Order Amount
                            </label>
                            <input type="number" id="minimum_order_amount" name="minimum_order_amount" value="{{ old('minimum_order_amount', $coupon->minimum_order_amount) }}" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('minimum_order_amount') border-red-500 @enderror"
                                   placeholder="0 (no minimum)">
                            @error('minimum_order_amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Usage Limits -->
                <div class="mt-8 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Usage Limits</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                Total Usage Limit
                            </label>
                            <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('usage_limit') border-red-500 @enderror"
                                   placeholder="Leave empty for unlimited">
                            <p class="text-xs text-gray-500 mt-1">Currently used: {{ $coupon->used_count }} times</p>
                            @error('usage_limit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="user_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                Per User Usage Limit
                            </label>
                            <input type="number" id="user_limit" name="user_limit" value="{{ old('user_limit', $coupon->user_limit) }}" min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_limit') border-red-500 @enderror"
                                   placeholder="Leave empty for unlimited">
                            @error('user_limit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Validity Period -->
                <div class="mt-8 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Validity Period</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Date
                            </label>
                            <input type="datetime-local" id="starts_at" name="starts_at" 
                                   value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('starts_at') border-red-500 @enderror">
                            @error('starts_at')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Expiry Date
                            </label>
                            <input type="datetime-local" id="expires_at" name="expires_at" 
                                   value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('expires_at') border-red-500 @enderror">
                            @error('expires_at')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Restrictions -->
                <div class="mt-8 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Restrictions (Optional)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Applies to Categories
                            </label>
                            <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                                @foreach($categories as $category)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="applies_to_categories[]" value="{{ $category }}"
                                               {{ in_array($category, old('applies_to_categories', $coupon->applies_to_categories ?? [])) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">{{ $category }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Leave empty to apply to all categories</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Excluded Products
                            </label>
                            <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                                @foreach($products as $product)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="excluded_products[]" value="{{ $product->id }}"
                                               {{ in_array($product->id, old('excluded_products', $coupon->excluded_products ?? [])) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">{{ $product->item_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Select products to exclude from this coupon</p>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mt-8">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Inactive coupons cannot be used by customers</p>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('admin.coupons') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                        <i class="fas fa-save mr-2"></i>Update Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleTypeFields() {
    const type = document.getElementById('type').value;
    const valueField = document.getElementById('value-field');
    const maxDiscountField = document.getElementById('max-discount-field');
    const bonusProductField = document.getElementById('bonus-product-field');
    const bonusQuantityField = document.getElementById('bonus-quantity-field');
    
    // Reset all fields
    valueField.classList.remove('hidden');
    maxDiscountField.classList.add('hidden');
    bonusProductField.classList.add('hidden');
    bonusQuantityField.classList.add('hidden');
    
    // Update value field label and placeholder
    const valueLabel = valueField.querySelector('label');
    const valueInput = valueField.querySelector('input');
    
    switch(type) {
        case 'percentage':
            valueLabel.textContent = 'Discount Percentage *';
            valueInput.placeholder = 'e.g., 20 for 20% off';
            valueInput.max = '100';
            maxDiscountField.classList.remove('hidden');
            break;
        case 'fixed_amount':
            valueLabel.textContent = 'Discount Amount (₹) *';
            valueInput.placeholder = 'e.g., 100 for ₹100 off';
            valueInput.removeAttribute('max');
            break;
        case 'bonus_items':
            valueField.classList.add('hidden');
            bonusProductField.classList.remove('hidden');
            bonusQuantityField.classList.remove('hidden');
            break;
    }
}

function generateCode() {
    fetch('{{ route("admin.coupons.generate-code") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('code').value = data.code;
        })
        .catch(error => {
            console.error('Error generating code:', error);
        });
}

// Initialize fields on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleTypeFields();
});
</script>
@endsection 