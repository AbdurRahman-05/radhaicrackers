@extends('layouts.admin')

@section('title', 'Add New Stock - Admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New Stock</h1>
                    <p class="text-gray-600 mt-2">Add a new product to your inventory</p>
                </div>
                <a href="{{ route('admin.stocks') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    ← Back to Stocks
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium text-red-800">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Product Information</h2>
            </div>
            
            <form action="{{ route('admin.stocks.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div>
                        <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Name *
                        </label>
                        <input type="text" id="item_name" name="item_name" value="{{ old('item_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('item_name') border-red-500 @enderror"
                               placeholder="Enter product name" required>
                        @error('item_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category *
                        </label>
                        <div style="max-height: 180px; overflow-y: auto;">
                        <select id="category" name="category" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('category') border-red-500 @enderror"
                                    required
                                    size="6">
                            <option value="">Select Category</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        </div>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('description') border-red-500 @enderror"
                              placeholder="Enter product description (optional)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pricing Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pricing Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Original Price -->
                        <div>
                            <label for="original_price" class="block text-sm font-medium text-gray-700 mb-2">
                                Original Price (₹)
                            </label>
                            <input type="number" step="0.01" id="original_price" name="original_price" value="{{ old('original_price') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('original_price') border-red-500 @enderror"
                                   placeholder="0.00">
                            @error('original_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Main Discount Percentage -->
                        <div>
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                Main Discount (%)
                            </label>
                            <input type="number" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage', 70) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('discount_percentage') border-red-500 @enderror"
                                   placeholder="70">
                            @error('discount_percentage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Special Discount Percentage -->
                        <div>
                            <label for="special_discount_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Discount (%)
                            </label>
                            <input type="number" id="special_discount_percentage" name="special_discount_percentage" value="{{ old('special_discount_percentage', 15) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('special_discount_percentage') border-red-500 @enderror"
                                   placeholder="15">
                            @error('special_discount_percentage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Final Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Final Price (₹) *
                            </label>
                            <input type="number" step="0.01" id="price" name="price" value="{{ old('price') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('price') border-red-500 @enderror"
                                   placeholder="0.00" required>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Price Calculation Summary -->
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Price Calculation Summary</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Order Value:</span>
                                <span id="order-value">₹0.00</span>
                            </div>
                            <div class="flex justify-between text-red-600">
                                <span id="main-discount-label">70% Discount:</span>
                                <span id="main-discount-amount">-₹0.00</span>
                            </div>
                            <div class="flex justify-between text-red-600">
                                <span id="special-discount-label">15% Special Discount:</span>
                                <span id="special-discount-amount">-₹0.00</span>
                            </div>
                            <div class="border-t pt-2 font-semibold">
                                <div class="flex justify-between">
                                    <span>Total Amount:</span>
                                    <span id="total-amount">₹0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Initial Quantity *
                            </label>
                            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 0) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('quantity') border-red-500 @enderror"
                                   placeholder="0" required>
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 text-sm text-gray-700">
                                    Active (Available for purchase)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Product Image</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Product Image (Optional)
                            </label>
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('image') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF, WebP</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Youtube URL Input starts here -->
                <div>
                    <label for="youtube_url" class="block text-sm font-medium text-gray-700 mb-2">
                        Youtube URL
                    </label>
                    <input type="text" id="youtube_url" name="youtube_url" value="{{ old('youtube_url') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('youtube_url') border-red-500 @enderror"
                           placeholder="https://www.youtube.com/watch?v=...">
                    <p class="mt-1 text-sm text-gray-500">Optional: Add a YouTube video URL for this product</p>
                    @error('youtube_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Form Actions -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between">
                        <button type="reset" 
                                class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                            Reset Form
                        </button>
                        
                        <div class="flex space-x-4">
                            <a href="{{ route('admin.stocks') }}" 
                               class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add Stock
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Advanced JavaScript for price calculation with multiple discounts
document.addEventListener('DOMContentLoaded', function() {
    const originalPriceInput = document.getElementById('original_price');
    const mainDiscountInput = document.getElementById('discount_percentage');
    const specialDiscountInput = document.getElementById('special_discount_percentage');
    const finalPriceInput = document.getElementById('price');

    // Summary elements
    const orderValueEl = document.getElementById('order-value');
    const mainDiscountLabelEl = document.getElementById('main-discount-label');
    const mainDiscountAmountEl = document.getElementById('main-discount-amount');
    const specialDiscountLabelEl = document.getElementById('special-discount-label');
    const specialDiscountAmountEl = document.getElementById('special-discount-amount');
    const totalAmountEl = document.getElementById('total-amount');

    function updateCalculationSummary() {
        const originalPrice = parseFloat(originalPriceInput.value) || 0;
        const mainDiscount = parseFloat(mainDiscountInput.value) || 0;
        const specialDiscount = parseFloat(specialDiscountInput.value) || 0;

        // Calculate discounts
        const mainDiscountAmount = originalPrice * (mainDiscount / 100);
        const priceAfterMainDiscount = originalPrice - mainDiscountAmount;
        const specialDiscountAmount = priceAfterMainDiscount * (specialDiscount / 100);
        const priceAfterSpecialDiscount = priceAfterMainDiscount - specialDiscountAmount;
        
        // Total amount is the price after special discount (no packing charge)
        const totalAmount = priceAfterSpecialDiscount;

        // Update summary display
        orderValueEl.textContent = `₹${originalPrice.toFixed(2)}`;
        mainDiscountLabelEl.textContent = `${mainDiscount}% Discount:`;
        mainDiscountAmountEl.textContent = `-₹${mainDiscountAmount.toFixed(2)}`;
        specialDiscountLabelEl.textContent = `${specialDiscount}% Special Discount:`;
        specialDiscountAmountEl.textContent = `-₹${specialDiscountAmount.toFixed(2)}`;
        totalAmountEl.textContent = `₹${totalAmount.toFixed(2)}`;

        // Update final price
        finalPriceInput.value = totalAmount.toFixed(2);
    }

    function calculateFromFinalPrice() {
        const finalPrice = parseFloat(finalPriceInput.value) || 0;
        const mainDiscount = parseFloat(mainDiscountInput.value) || 0;
        const specialDiscount = parseFloat(specialDiscountInput.value) || 0;
        
        if (finalPrice > 0 && mainDiscount > 0) {
            // Reverse calculate: final price -> original price
            // finalPrice = originalPrice * (1 - mainDiscount/100) * (1 - specialDiscount/100)
            // originalPrice = finalPrice / ((1 - mainDiscount/100) * (1 - specialDiscount/100))
            const reverseFactor = (1 - mainDiscount/100) * (1 - specialDiscount/100);
            const originalPrice = finalPrice / reverseFactor;
            originalPriceInput.value = originalPrice.toFixed(2);
            updateCalculationSummary();
        }
    }

    // Event listeners
    originalPriceInput.addEventListener('input', updateCalculationSummary);
    mainDiscountInput.addEventListener('input', updateCalculationSummary);
    specialDiscountInput.addEventListener('input', updateCalculationSummary);
    finalPriceInput.addEventListener('input', calculateFromFinalPrice);

    // Initialize calculation on page load
    updateCalculationSummary();
});
</script>
@endsection 