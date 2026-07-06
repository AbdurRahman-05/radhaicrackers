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
                        <select id="category" name="category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('category') border-red-500 @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                        <!-- Discount Percentage -->
                        <div>
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                Discount (%)
                            </label>
                            <input type="number" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('discount_percentage') border-red-500 @enderror"
                                   placeholder="0">
                            @error('discount_percentage')
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
// Simple JavaScript for price calculation
document.addEventListener('DOMContentLoaded', function() {
    const originalPriceInput = document.getElementById('original_price');
    const discountInput = document.getElementById('discount_percentage');
    const finalPriceInput = document.getElementById('price');

    function calculateDiscount() {
        const originalPrice = parseFloat(originalPriceInput.value) || 0;
        const finalPrice = parseFloat(finalPriceInput.value) || 0;
        
        if (originalPrice > 0 && finalPrice > 0) {
            const discount = Math.round(((originalPrice - finalPrice) / originalPrice) * 100);
            discountInput.value = discount;
        }
    }

    function calculatePrice() {
        const originalPrice = parseFloat(originalPriceInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        
        if (originalPrice > 0 && discount > 0) {
            const finalPrice = Math.round(originalPrice * (1 - (discount / 100)) * 100) / 100;
            finalPriceInput.value = finalPrice;
        }
    }

    originalPriceInput.addEventListener('change', calculateDiscount);
    discountInput.addEventListener('change', calculatePrice);
});
</script>
@endsection 