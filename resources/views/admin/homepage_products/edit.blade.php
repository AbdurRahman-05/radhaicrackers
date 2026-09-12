@extends('layouts.admin')

@section('page-title', 'Edit Home Page Product')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Home Page Product</h1>
            <p class="text-sm text-gray-500 mt-1">Update details, image, or home page placement for "{{ $product->item_name }}".</p>
        </div>
        <a href="{{ route('admin.homepage_products.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5">
            &larr; Back to Products
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg text-red-800 text-sm">
            <p class="font-bold mb-1">Please check the errors below:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- QUICK SELECT FROM INVENTORY (OPTIONAL AUTOFILL) -->
    <div class="mb-6 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 rounded-xl p-5 text-white shadow-md">
        <div class="flex items-center justify-between flex-wrap gap-2 mb-2">
            <div class="flex items-center gap-2">
                <span class="text-xl">✨</span>
                <h2 class="text-base font-bold">Sync from Inventory (Optional)</h2>
            </div>
            <!-- Quick Filter Toggle -->
            <div class="flex items-center bg-black/20 p-1 rounded-lg text-xs">
                <button type="button" id="filterActiveOnlyBtn" class="px-2.5 py-1 rounded-md font-bold bg-white text-amber-900 shadow-sm transition">
                    🌟 2026 Active Only ({{ $stocks->where('is_active', true)->count() }})
                </button>
                <button type="button" id="filterAllBtn" class="px-2.5 py-1 rounded-md font-medium text-white hover:text-amber-100 transition">
                    📦 All Products ({{ $stocks->count() }})
                </button>
            </div>
        </div>
        <p class="text-xs text-amber-100 mb-2">Select a product to overwrite this item's details with current inventory values:</p>
        <select id="quickStockSelect" class="w-full bg-white text-gray-900 border-0 rounded-lg p-3 text-sm font-medium shadow-sm focus:ring-2 focus:ring-white">
            <option value="">-- Click here to select a product to overwrite --</option>
            
            <optgroup id="optgroupActive" label="🌟 CURRENT YEAR (2026) ACTIVE PRODUCTS ({{ $stocks->where('is_active', true)->count() }})">
                @foreach($stocks->where('is_active', true) as $stk)
                    <option value="{{ $stk['id'] }}" 
                            data-name="{{ $stk['name'] }}"
                            data-category="{{ $stk['category_id'] ?: $stk['category'] }}"
                            data-description="{{ $stk['description'] }}"
                            data-price="{{ $stk['price'] }}"
                            data-original-price="{{ $stk['original_price'] }}"
                            data-discount="{{ $stk['discount_percentage'] }}"
                            data-special-discount="{{ $stk['special_discount_percentage'] }}"
                            data-quantity="{{ $stk['quantity'] }}"
                            data-image="{{ $stk['image'] }}"
                            data-image-url="{{ $stk['image_url'] }}"
                            data-youtube="{{ $stk['youtube_url'] }}"
                            data-is-active="1">
                        ⭐ [2026 ACTIVE] {{ $stk['name'] }} &mdash; ₹{{ number_format($stk['price'], 2) }} ({{ $stk['category'] ?: 'General' }})
                    </option>
                @endforeach
            </optgroup>

            <optgroup id="optgroupInactive" label="📦 Other / All Inventory Products ({{ $stocks->where('is_active', false)->count() }})">
                @foreach($stocks->where('is_active', false) as $stk)
                    <option value="{{ $stk['id'] }}" 
                            data-name="{{ $stk['name'] }}"
                            data-category="{{ $stk['category_id'] ?: $stk['category'] }}"
                            data-description="{{ $stk['description'] }}"
                            data-price="{{ $stk['price'] }}"
                            data-original-price="{{ $stk['original_price'] }}"
                            data-discount="{{ $stk['discount_percentage'] }}"
                            data-special-discount="{{ $stk['special_discount_percentage'] }}"
                            data-quantity="{{ $stk['quantity'] }}"
                            data-image="{{ $stk['image'] }}"
                            data-image-url="{{ $stk['image_url'] }}"
                            data-youtube="{{ $stk['youtube_url'] }}"
                            data-is-active="0">
                        {{ $stk['name'] }} &mdash; ₹{{ number_format($stk['price'], 2) }} ({{ $stk['category'] ?: 'General' }})
                    </option>
                @endforeach
            </optgroup>
        </select>
    </div>

    <!-- MAIN FORM -->
    <form action="{{ route('admin.homepage_products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Hidden inputs for existing image and selected image path -->
        <input type="hidden" name="existing_image" id="existing_image_input" value="{{ old('existing_image', $product->image) }}">
        <input type="hidden" name="selected_image" id="selected_image_input" value="{{ old('selected_image') }}">

        <!-- Section 1: Basic Info -->
        <div>
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">1. Product Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="item_name" class="block text-xs font-bold text-gray-700 uppercase mb-1">Product Name *</label>
                    <input type="text" id="item_name" name="item_name" value="{{ old('item_name', $product->item_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 @error('item_name') border-red-500 @enderror" required>
                </div>
                <div>
                    <label for="category" class="block text-xs font-bold text-gray-700 uppercase mb-1">Category</label>
                    <select id="category" name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $id => $name)
                            <option value="{{ $id }}" {{ old('category', $product->category) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label for="description" class="block text-xs font-bold text-gray-700 uppercase mb-1">Description</label>
                <textarea id="description" name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <!-- Section 2: Pricing -->
        <div class="border-t border-gray-100 pt-5">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">2. Pricing & Discounts</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label for="original_price" class="block text-xs font-bold text-gray-700 uppercase mb-1">Original Price (₹)</label>
                    <input type="number" step="0.01" id="original_price" name="original_price" value="{{ old('original_price', $product->original_price) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500" placeholder="0.00">
                </div>
                <div>
                    <label for="discount_percentage" class="block text-xs font-bold text-gray-700 uppercase mb-1">Main Disc (%)</label>
                    <input type="number" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage', $product->discount_percentage ?? 70) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="special_discount_percentage" class="block text-xs font-bold text-gray-700 uppercase mb-1">Special Disc (%)</label>
                    <input type="number" id="special_discount_percentage" name="special_discount_percentage" value="{{ old('special_discount_percentage', $product->special_discount_percentage ?? 15) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="price" class="block text-xs font-bold text-amber-700 uppercase mb-1">Final Price (₹) *</label>
                    <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price) }}" class="w-full px-3 py-2 border-2 border-amber-500 rounded-lg text-sm font-bold text-amber-900 bg-amber-50/50 focus:ring-2 focus:ring-amber-500" required>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="quantity" class="block text-xs font-bold text-gray-700 uppercase mb-1">Stock Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="youtube_url" class="block text-xs font-bold text-gray-700 uppercase mb-1">YouTube Video URL</label>
                    <input type="text" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $product->youtube_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        <!-- Section 3: Product Image -->
        <div class="border-t border-gray-100 pt-5">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">3. Product Image</h3>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <!-- Image Preview Box -->
                <div class="w-32 h-32 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0 relative">
                    <img id="imagePreview" src="{{ $product->image_url }}" alt="Preview" class="w-full h-full object-contain p-2">
                    <span id="previewTag" class="absolute bottom-1 right-1 bg-gray-800 text-white text-[10px] px-1.5 py-0.5 rounded opacity-80">Current</span>
                </div>

                <div class="flex-1 space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Replace Image File (Optional)</label>
                        <input type="file" id="imageFileInput" name="image" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-800 cursor-pointer">
                        <p class="text-[11px] text-gray-500 mt-1">Leave empty to keep the current image, or select a new 2026 image below.</p>
                    </div>
                </div>
            </div>

            <!-- 2026 Current Year Images Picker -->
            <div class="mt-4 pt-3 border-t border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-gray-700 uppercase">🌟 Or Select from Current 2026 Product Images Gallery:</span>
                    <span class="text-[11px] text-amber-700 font-medium">Click image to apply instantly</span>
                </div>
                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 max-h-48 overflow-y-auto p-1 scrollbar-thin">
                    @foreach($availableImages as $img)
                        <div class="gallery-select-card border rounded-lg p-1.5 cursor-pointer bg-white hover:border-amber-400 hover:shadow-sm transition text-center"
                             data-path="{{ $img['path'] }}"
                             data-url="{{ $img['url'] }}"
                             data-title="{{ $img['title'] }}"
                             onclick="selectProductGalleryImage(this)">
                            <div class="h-14 flex items-center justify-center bg-gray-50 rounded p-1 mb-1">
                                <img src="{{ $img['url'] }}" alt="{{ $img['title'] }}" class="max-h-full max-w-full object-contain">
                            </div>
                            <span class="text-[10px] font-bold text-gray-700 block truncate" title="{{ $img['title'] }}">{{ $img['title'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Section 4: Home Page Placement & Status -->
        <div class="border-t border-gray-100 pt-5 bg-amber-50/50 -mx-6 -mb-6 p-6 rounded-b-xl">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">4. Home Page Display Settings</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <!-- Popular Checkbox -->
                <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 cursor-pointer hover:border-amber-400 transition">
                    <input type="checkbox" id="is_popular" name="is_popular" value="1" {{ old('is_popular', $product->is_popular) ? 'checked' : '' }} class="h-5 w-5 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                    <div>
                        <span class="text-sm font-bold text-gray-800 flex items-center gap-1">
                            <span class="text-yellow-500">⭐</span> Popular Product
                        </span>
                        <span class="block text-[11px] text-gray-500">Display in Popular Products section</span>
                    </div>
                </label>

                <!-- Latest Checkbox -->
                <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 cursor-pointer hover:border-blue-400 transition">
                    <input type="checkbox" id="is_latest" name="is_latest" value="1" {{ old('is_latest', $product->is_latest) ? 'checked' : '' }} class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <div>
                        <span class="text-sm font-bold text-gray-800 flex items-center gap-1">
                            <span class="text-blue-500">🆕</span> Latest Product
                        </span>
                        <span class="block text-[11px] text-gray-500">Display in Latest Products section</span>
                    </div>
                </label>

                <!-- Active Checkbox -->
                <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 cursor-pointer hover:border-green-400 transition">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <div>
                        <span class="text-sm font-bold text-gray-800 flex items-center gap-1">
                            <span class="text-green-600">●</span> Active Status
                        </span>
                        <span class="block text-[11px] text-gray-500">Visible for purchase online</span>
                    </div>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-amber-200">
                <a href="{{ route('admin.homepage_products.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium transition">
                    Cancel
                </a>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-2.5 rounded-lg shadow transition text-sm flex items-center gap-1.5">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function selectProductGalleryImage(element) {
    document.querySelectorAll('.gallery-select-card').forEach(card => {
        card.classList.remove('border-amber-500', 'ring-2', 'ring-amber-500', 'bg-amber-50');
        card.classList.add('border-gray-200', 'bg-white');
    });

    element.classList.remove('border-gray-200', 'bg-white');
    element.classList.add('border-amber-500', 'ring-2', 'ring-amber-500', 'bg-amber-50');

    const path = element.dataset.path;
    const url = element.dataset.url;

    document.getElementById('selected_image_input').value = path;
    document.getElementById('existing_image_input').value = '';
    const imgPreview = document.getElementById('imagePreview');
    imgPreview.src = url;
    const tag = document.getElementById('previewTag');
    tag.textContent = '2026 Image';
    tag.className = 'absolute bottom-1 right-1 bg-amber-700 text-white text-[10px] px-1.5 py-0.5 rounded opacity-90';
}

document.addEventListener('DOMContentLoaded', function() {
    const quickSelect = document.getElementById('quickStockSelect');
    const nameInput = document.getElementById('item_name');
    const categorySelect = document.getElementById('category');
    const descInput = document.getElementById('description');
    const originalPriceInput = document.getElementById('original_price');
    const discountInput = document.getElementById('discount_percentage');
    const specialDiscountInput = document.getElementById('special_discount_percentage');
    const finalPriceInput = document.getElementById('price');
    const quantityInput = document.getElementById('quantity');
    const youtubeInput = document.getElementById('youtube_url');
    const existingImageInput = document.getElementById('existing_image_input');
    const imagePreview = document.getElementById('imagePreview');
    const previewTag = document.getElementById('previewTag');
    const fileInput = document.getElementById('imageFileInput');

    // Filter toggles
    const filterActiveOnlyBtn = document.getElementById('filterActiveOnlyBtn');
    const filterAllBtn = document.getElementById('filterAllBtn');
    const optgroupInactive = document.getElementById('optgroupInactive');

    if (filterActiveOnlyBtn && filterAllBtn && optgroupInactive) {
        filterActiveOnlyBtn.addEventListener('click', function() {
            optgroupInactive.style.display = 'none';
            filterActiveOnlyBtn.className = 'px-2.5 py-1 rounded-md font-bold bg-white text-amber-900 shadow-sm transition';
            filterAllBtn.className = 'px-2.5 py-1 rounded-md font-medium text-white hover:text-amber-100 transition';
        });

        filterAllBtn.addEventListener('click', function() {
            optgroupInactive.style.display = '';
            filterAllBtn.className = 'px-2.5 py-1 rounded-md font-bold bg-white text-amber-900 shadow-sm transition';
            filterActiveOnlyBtn.className = 'px-2.5 py-1 rounded-md font-medium text-white hover:text-amber-100 transition';
        });
    }

    // Quick Autofill from inventory
    quickSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (!selected || !selected.value) return;

        nameInput.value = selected.dataset.name || '';
        if (selected.dataset.category) {
            categorySelect.value = selected.dataset.category;
        }
        descInput.value = selected.dataset.description || '';
        originalPriceInput.value = selected.dataset.originalPrice || '';
        discountInput.value = selected.dataset.discount || 70;
        specialDiscountInput.value = selected.dataset.specialDiscount || 15;
        finalPriceInput.value = selected.dataset.price || '';
        quantityInput.value = selected.dataset.quantity || 10;
        youtubeInput.value = selected.dataset.youtube || '';

        if (selected.dataset.image) {
            existingImageInput.value = selected.dataset.image;
        }
        if (selected.dataset.imageUrl) {
            imagePreview.src = selected.dataset.imageUrl;
            previewTag.textContent = selected.dataset.isActive === '1' ? '2026 Stock Image' : 'Inventory Image';
            previewTag.className = 'absolute bottom-1 right-1 bg-green-700 text-white text-[10px] px-1.5 py-0.5 rounded opacity-90';
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewTag.textContent = 'New Upload';
                previewTag.className = 'absolute bottom-1 right-1 bg-blue-700 text-white text-[10px] px-1.5 py-0.5 rounded opacity-90';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    function updatePrice() {
        const orig = parseFloat(originalPriceInput.value) || 0;
        const d1 = parseFloat(discountInput.value) || 0;
        const d2 = parseFloat(specialDiscountInput.value) || 0;
        if (orig > 0) {
            const afterD1 = orig - (orig * (d1 / 100));
            const afterD2 = afterD1 - (afterD1 * (d2 / 100));
            finalPriceInput.value = afterD2.toFixed(2);
        }
    }

    originalPriceInput.addEventListener('input', updatePrice);
    discountInput.addEventListener('input', updatePrice);
    specialDiscountInput.addEventListener('input', updatePrice);
});
</script>
@endsection