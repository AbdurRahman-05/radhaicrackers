@extends('layouts.admin')

@section('page-title', 'Edit Category Card (2026)')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-gray-900">Edit Category: {{ $category->name }}</h1>
                <span class="bg-amber-100 text-amber-800 text-xs px-2.5 py-0.5 rounded-full font-bold">2026 Current Image</span>
            </div>
            <p class="text-sm text-gray-500 mt-1">Select a 2026 high-resolution image from the gallery below or upload a custom image.</p>
        </div>
        <a href="{{ route('admin.homepage_categories.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5">
            &larr; Back to Categories
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg text-red-800 text-sm">
            <p class="font-bold mb-1">Please fix the errors below:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.homepage_categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Hidden input for selected gallery image path -->
        <input type="hidden" name="selected_image" id="selected_image_input" value="{{ old('selected_image', $category->image) }}">

        <!-- STEP 1: VISUAL 2026 IMAGE PICKER -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span class="text-amber-500">📸</span> Select Current Year (2026) Image
                    </h2>
                    <p class="text-xs text-gray-500">Click any current 2026 product image to instantly assign it to this category card.</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-400">Currently selected:</span>
                    <span id="currentSelectionLabel" class="block text-xs font-bold text-amber-700 truncate max-w-[200px]">{{ basename($category->image) }}</span>
                </div>
            </div>

            <!-- Current Preview Card -->
            <div class="mb-5 p-4 bg-amber-50/50 rounded-xl border border-amber-200 flex items-center gap-4">
                <div class="w-24 h-24 bg-white rounded-lg p-2 border border-gray-200 shadow-sm flex items-center justify-center flex-shrink-0">
                    <img id="activeImagePreview" src="{{ $category->image_url }}" alt="Preview" class="max-h-full max-w-full object-contain">
                </div>
                <div>
                    <span class="bg-green-600 text-white text-[11px] px-2 py-0.5 rounded font-bold">Active Live Image</span>
                    <h3 id="previewTitle" class="font-bold text-gray-900 mt-1 text-sm">{{ $category->name }}</h3>
                    <p id="previewPath" class="text-xs text-gray-500 font-mono mt-0.5 break-all">{{ $category->image }}</p>
                </div>
            </div>

            <!-- Grid of 2026 Images -->
            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Available 2026 Images Gallery (Click to Select):</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-h-96 overflow-y-auto p-1 scrollbar-thin">
                @foreach($availableImages as $img)
                    @php
                        $isSelected = (old('selected_image', $category->image) == $img['path']);
                    @endphp
                    <div class="gallery-card relative border-2 rounded-xl p-3 cursor-pointer transition flex flex-col items-center text-center {{ $isSelected ? 'border-amber-500 bg-amber-50/40 shadow-md ring-2 ring-amber-400' : 'border-gray-200 bg-white hover:border-amber-300 hover:shadow' }}"
                         data-path="{{ $img['path'] }}"
                         data-url="{{ $img['url'] }}"
                         data-title="{{ $img['title'] }}"
                         onclick="selectGalleryImage(this)">
                        
                        <div class="w-full h-24 bg-gray-50 rounded-lg flex items-center justify-center p-1 mb-2">
                            <img src="{{ $img['url'] }}" alt="{{ $img['title'] }}" class="max-h-full max-w-full object-contain">
                        </div>

                        <span class="text-xs font-bold text-gray-800 line-clamp-1 w-full" title="{{ $img['title'] }}">{{ $img['title'] }}</span>
                        <span class="text-[10px] text-gray-400 font-mono mt-0.5">{{ basename($img['path']) }}</span>

                        <div class="check-badge absolute top-2 right-2 {{ $isSelected ? '' : 'hidden' }}">
                            <span class="bg-amber-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs shadow">✓</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Custom Upload Alternative -->
            <div class="mt-5 pt-4 border-t border-gray-200">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Or Upload Custom 2026 Image (Optional)</label>
                <input type="file" id="customFileInput" name="image" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 border border-gray-300 rounded-lg p-1.5">
                <p class="text-[11px] text-gray-500 mt-1">Uploading a new file will override the selected gallery image.</p>
            </div>
        </div>

        <!-- STEP 2: CATEGORY DETAILS -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <span>📝</span> Category Details
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-700 uppercase mb-1">Display Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 font-semibold" required>
                    <p class="text-[11px] text-gray-500 mt-1">Example: SINGLE FLASH, BIJILI CRACKERS, BOMBS</p>
                </div>

                <div>
                    <label for="search_term" class="block text-xs font-bold text-gray-700 uppercase mb-1">Search / Filter Tag</label>
                    <input type="text" id="search_term" name="search_term" value="{{ old('search_term', $category->search_term) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500" placeholder="e.g. SINGLE FLASH">
                    <p class="text-[11px] text-gray-500 mt-1">When customer clicks this card, it opens express-shop filtered by this tag.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label for="product_count" class="block text-xs font-bold text-gray-700 uppercase mb-1">Display Product Count</label>
                    <input type="number" id="product_count" name="product_count" value="{{ old('product_count', $category->product_count) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                    <p class="text-[11px] text-gray-500 mt-1">e.g. 30 Products</p>
                </div>

                <div>
                    <label for="sort_order" class="block text-xs font-bold text-gray-700 uppercase mb-1">Display Order #</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                    <p class="text-[11px] text-gray-500 mt-1">1 = First, 2 = Second, etc.</p>
                </div>

                <div class="flex flex-col justify-center space-y-2 pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="auto_count" value="1" {{ old('auto_count', $category->auto_count) ? 'checked' : '' }} class="h-4 w-4 text-amber-600 rounded">
                        <span class="text-xs font-bold text-gray-700">Auto-count active products</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                        <span class="text-xs font-bold text-gray-700">Active (Visible on Homepage)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.homepage_categories.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium transition">
                Cancel
            </a>
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-2.5 rounded-lg shadow transition text-sm flex items-center gap-1.5">
                <i class="fas fa-save"></i> Save Category Card
            </button>
        </div>
    </form>
</div>

<script>
function selectGalleryImage(element) {
    // Deselect all
    document.querySelectorAll('.gallery-card').forEach(card => {
        card.classList.remove('border-amber-500', 'bg-amber-50/40', 'shadow-md', 'ring-2', 'ring-amber-400');
        card.classList.add('border-gray-200', 'bg-white');
        const badge = card.querySelector('.check-badge');
        if (badge) badge.classList.add('hidden');
    });

    // Select this
    element.classList.remove('border-gray-200', 'bg-white');
    element.classList.add('border-amber-500', 'bg-amber-50/40', 'shadow-md', 'ring-2', 'ring-amber-400');
    const badge = element.querySelector('.check-badge');
    if (badge) badge.classList.remove('hidden');

    // Update hidden input and preview
    const path = element.dataset.path;
    const url = element.dataset.url;
    const title = element.dataset.title;

    document.getElementById('selected_image_input').value = path;
    document.getElementById('activeImagePreview').src = url;
    document.getElementById('currentSelectionLabel').textContent = path.split('/').pop();
    document.getElementById('previewPath').textContent = path;
}

// Preview uploaded file
document.getElementById('customFileInput').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('activeImagePreview').src = e.target.result;
            document.getElementById('currentSelectionLabel').textContent = 'Custom File Uploaded';
            document.getElementById('previewPath').textContent = 'New uploaded image file';
        };
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
@endsection
