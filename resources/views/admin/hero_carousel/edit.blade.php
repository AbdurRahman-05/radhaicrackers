@extends('layouts.admin')

@section('page-title', 'Edit Hero Carousel Banner')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Hero Carousel Slide</h2>
            <p class="text-sm text-gray-500 mt-1">Update slide image, texts, target link, and order.</p>
        </div>
        <a href="{{ route('admin.hero_carousel.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
            &larr; Back to Slides
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg text-red-800 text-sm">
            <p class="font-bold mb-1">Please fix the following issues:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.hero_carousel.update', $slide->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Banner Image Section -->
        <div class="border-b border-gray-100 pb-6">
            <h3 class="text-base font-bold text-gray-900 mb-2 flex items-center gap-2">
                <i class="fas fa-image text-amber-600"></i> Step 1: Banner Image
            </h3>
            
            <!-- Live Preview -->
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Current Banner & Live Preview</label>
                <div class="relative w-full h-48 md:h-64 rounded-xl overflow-hidden bg-gray-900 border border-gray-300 shadow-inner flex items-center justify-center">
                    <img id="previewImage" src="{{ $slide->image_url }}" alt="Preview" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-center p-4 text-white">
                        <span id="previewTitle" class="text-xl md:text-3xl font-extrabold drop-shadow">{{ $slide->title ?: 'Title Preview' }}</span>
                        <span id="previewSubtitle" class="text-xs md:text-sm mt-1 text-gray-200 max-w-md drop-shadow">{{ $slide->subtitle ?: 'Subtitle preview goes here' }}</span>
                        <span id="previewButton" class="mt-3 inline-block bg-gradient-to-r from-amber-500 to-yellow-500 text-black text-xs font-bold px-4 py-1.5 rounded-full shadow">{{ $slide->button_text ?: 'Order Now' }}</span>
                    </div>
                </div>
            </div>

            <!-- Choose Option: Upload or Preset -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <!-- Upload File -->
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <label class="block text-sm font-bold text-gray-800 mb-1">
                        <i class="fas fa-upload text-blue-600 mr-1"></i> Replace With New Image File
                    </label>
                    <p class="text-xs text-gray-500 mb-3">Leave empty to keep existing image</p>
                    <input type="file" name="image_file" id="imageFileInput" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer">
                </div>

                <!-- Choose Preset -->
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <label class="block text-sm font-bold text-gray-800 mb-1">
                        <i class="fas fa-th-large text-green-600 mr-1"></i> Or Switch to Preset Banner
                    </label>
                    <p class="text-xs text-gray-500 mb-3">Select existing banner from server</p>
                    <select name="preset_image" id="presetSelect" class="w-full bg-white border border-gray-300 rounded-lg p-2 text-xs font-medium focus:ring-2 focus:ring-amber-500">
                        <option value="">-- Keep Current Image --</option>
                        @foreach($presetImages as $img)
                            <option value="{{ $img }}" {{ $slide->image == $img ? 'selected' : '' }}>{{ basename($img) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Banner Content & Details -->
        <div class="space-y-4">
            <h3 class="text-base font-bold text-gray-900 mb-1 flex items-center gap-2">
                <i class="fas fa-font text-amber-600"></i> Step 2: Banner Text & Navigation
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="title" class="block text-xs font-bold text-gray-700 uppercase mb-1">Slide Title (Optional)</label>
                    <input type="text" name="title" id="titleInput" value="{{ old('title', $slide->title) }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-amber-500" placeholder="e.g., Festival of Lights Celebration">
                </div>

                <div>
                    <label for="button_text" class="block text-xs font-bold text-gray-700 uppercase mb-1">Button Text</label>
                    <input type="text" name="button_text" id="buttonTextInput" value="{{ old('button_text', $slide->button_text) }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-amber-500" placeholder="e.g., Order Now, Shop Now">
                </div>
            </div>

            <div>
                <label for="subtitle" class="block text-xs font-bold text-gray-700 uppercase mb-1">Subtitle / Description (Optional)</label>
                <input type="text" name="subtitle" id="subtitleInput" value="{{ old('subtitle', $slide->subtitle) }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-amber-500" placeholder="e.g., Best Quality Sivakasi Crackers Direct to Your Doorstep">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="link_url" class="block text-xs font-bold text-gray-700 uppercase mb-1">Target Link URL</label>
                    <input type="text" name="link_url" id="linkUrlInput" value="{{ old('link_url', $slide->link_url) }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-amber-500" placeholder="/quotation or /combos">
                    <div class="flex gap-2 mt-1.5 text-[11px] text-gray-500">
                        <span>Quick links:</span>
                        <button type="button" onclick="document.getElementById('linkUrlInput').value='/quotation'" class="text-amber-600 hover:underline">/quotation</button>
                        <button type="button" onclick="document.getElementById('linkUrlInput').value='/combos'" class="text-amber-600 hover:underline">/combos</button>
                        <button type="button" onclick="document.getElementById('linkUrlInput').value='/sale-products'" class="text-amber-600 hover:underline">/sale-products</button>
                    </div>
                </div>

                <div>
                    <label for="sort_order" class="block text-xs font-bold text-gray-700 uppercase mb-1">Display Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $slide->sort_order) }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-amber-500" min="0">
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slide->is_active) ? 'checked' : '' }} class="h-4 w-4 text-amber-600 focus:ring-amber-500 rounded border-gray-300">
                    <span class="text-sm font-semibold text-gray-800">Active (Visible on homepage)</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.hero_carousel.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium transition">
                Cancel
            </a>
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow transition text-sm flex items-center gap-1.5">
                <i class="fas fa-save"></i> Update Slide
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('imageFileInput');
    const presetSelect = document.getElementById('presetSelect');
    const previewImage = document.getElementById('previewImage');
    const titleInput = document.getElementById('titleInput');
    const subtitleInput = document.getElementById('subtitleInput');
    const buttonTextInput = document.getElementById('buttonTextInput');

    const previewTitle = document.getElementById('previewTitle');
    const previewSubtitle = document.getElementById('previewSubtitle');
    const previewButton = document.getElementById('previewButton');

    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                previewImage.src = ev.target.result;
            };
            reader.readAsDataURL(this.files[0]);
            presetSelect.value = '';
        }
    });

    presetSelect.addEventListener('change', function() {
        if (this.value) {
            previewImage.src = '/' + this.value;
            fileInput.value = '';
        }
    });

    titleInput.addEventListener('input', function() {
        previewTitle.textContent = this.value || '';
    });
    subtitleInput.addEventListener('input', function() {
        previewSubtitle.textContent = this.value || '';
    });
    buttonTextInput.addEventListener('input', function() {
        previewButton.textContent = this.value || 'Order Now';
    });
});
</script>
@endsection
