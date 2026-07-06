@extends('layouts.admin')

@section('title', 'Stock Management')
@section('page-title', 'Stock Management')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Stock Management</h2>
            <p class="text-gray-600">Manage inventory with auto-release and expiry</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 mt-4 sm:mt-0">
            <a href="{{ route('admin.stocks.add') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Product
            </a>
            <button onclick="openImportModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Import CSV
            </button>
            <a href="{{ route('admin.export.stocks') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </a>
            <a href="{{ route('admin.export.ordered-items') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Ordered Items
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-blue-600">{{ $totalStocks }}</div>
            <div class="text-sm text-blue-600">Total Items</div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-green-600">{{ $activeStocks }}</div>
            <div class="text-sm text-green-600">Active Items</div>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-yellow-600">{{ $availableStocks }}</div>
            <div class="text-sm text-yellow-600">Available</div>
        </div>
        <div class="bg-red-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-red-600">{{ $outOfStock }}</div>
            <div class="text-sm text-red-600">Out of Stock</div>
        </div>
    </div>

    <!-- Total Value -->
    <div class="bg-purple-50 p-4 rounded-lg mb-6">
        <div class="text-center">
            <div class="text-3xl font-bold text-purple-600">₹{{ number_format($totalValue, 2) }}</div>
            <div class="text-sm text-purple-600">Total Stock Value</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('admin.stocks') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Stock name, description" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="available" {{ request('status_filter') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="out_of_stock" {{ request('status_filter') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.stocks') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>



 <!-- Stocks Grouped by Category and Ordered by order_within_category -->
    <div class="space-y-10">
        @php
            // $categories: Collection of Category models
            // $stocksByCategory: Collection grouped by category name
        @endphp
        @foreach($categories as $category)
            <div id="cat-{{ Str::slug($category->name) }}" class="mb-2">
                <h3 class="text-lg font-bold text-blue-700 mb-2">{{ $category->name }}</h3>
               <div class="overflow-x-auto">
                   <table class="min-w-full bg-white border border-gray-200">
                       <thead class="bg-gray-50">
                           <tr>
                               <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                               <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordered Count</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Released</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Video</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $catStocks = $stocksByCategory[$category->name] ?? collect(); @endphp
                        @forelse($catStocks as $stock)
                        <tr class="hover:bg-gray-50">
                            <!-- ...existing code for each stock row... (copy your row markup here) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    @if($stock->image)
                                        <img src="{{ asset('storage/' . $stock->image) }}" alt="{{ $stock->item_name }}" class="w-12 h-12 object-cover rounded-lg">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-lg">
                                                @switch($stock->category)
                                                    @case('BOMBS')
                                                        💣
                                                        @break
                                                    @case('SINGLE FLASH')
                                                        ⚡
                                                        @break
                                                    @case('ROCKETS')
                                                        🚀
                                                        @break
                                                    @case('SPARKLERS')
                                                        ✨
                                                        @break
                                                    @case('CHIT PUT')
                                                        🎆
                                                        @break
                                                    @case('TWINKLING STAR')
                                                        ⭐
                                                        @break
                                                    @case('GIFT BOX')
                                                        🎁
                                                        @break
                                                    @case('BIJILI CRACKERS')
                                                        ⚡
                                                        @break
                                                    @default
                                                        🎆
                                                @endswitch
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $stock->item_name }}</div>
                                        @if($stock->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($stock->description, 50) }}</div>
                                        @endif
                                        @if($stock->category)
                                        <div class="text-xs text-blue-600">{{ $stock->category }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $stock->quantity }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">₹{{ number_format($stock->price, 2) }}</div>
                                @if($stock->original_price && $stock->original_price > $stock->price)
                                <div class="text-xs text-gray-500 line-through">₹{{ number_format($stock->original_price, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">₹{{ number_format($stock->quantity * $stock->price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-blue-900 font-semibold">
                                    {{ $stock->ordered_count }}
                                    @if($stock->ordered_count > 0)
                                        <span class="ml-2 inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Ordered</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($stock->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                            {{ $stock->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($stock->show_on_shop)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Available
                                        </span>
                                        @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Out of Stock
                                        </span>
                                        @endif
                                    </div>
                                    @if($stock->show_on_shop)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Visible on Stock
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($stock->last_released_at)
                                {{ $stock->last_released_at->format('d/m/Y H:i') }}
                                @else
                                Never
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($stock->youtube_url)
                                    <div class="flex items-center space-x-2">
                                        <button onclick="openVideoModal('{{ $stock->youtube_url }}', '{{ $stock->item_name }}')" 
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center" title="Watch Video">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                            </svg>
                                        </button>
                                        <a href="{{ $stock->youtube_url }}" target="_blank" 
                                           class="text-gray-500 hover:text-gray-700 text-xs" title="Open in YouTube">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 01-2-2V8a2 2 0 01 2-2h6M15 3h6v6M10 14L21 3"/>
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">No video</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-row items-center gap-4">
                                    <form action="{{ route('admin.stocks.toggle-active', $stock->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="relative w-8 h-4 rounded-full transition-colors flex-shrink-0 mt-2
                                            {{ $stock->is_active ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-300 hover:bg-gray-400' }}"
                                            title="{{ $stock->is_active ? 'Deactivate Stock' : 'Activate Stock' }}">
                                            <span class="absolute left-0 top-0 w-4 h-4 bg-white rounded-full shadow transform transition-transform
                                                {{ $stock->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.stocks.toggle-show-on-shop', $stock->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class=" mt-2 group relative w-8 h-4 rounded-full focus:outline-none transition-colors flex-shrink-0
                                            @if($stock->show_on_shop) bg-yellow-500 hover:bg-yellow-500 @else bg-gray-300 hover:bg-gray-400 @endif"
                                            title="{{ $stock->show_on_shop ? 'Hide from Shop' : 'Show on Shop' }}">
                                            <span class="absolute left-0 top-0 h-4 w-4 bg-white rounded-full shadow transform transition-transform
                                                @if($stock->show_on_shop) translate-x-4 @else translate-x-0 @endif"></span>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.stocks.edit', $stock->id) }}" class="text-blue-600 hover:text-blue-900 text-xl" title="Edit">
                                        <i class="fas fa-edit text-base"></i>
                                    </a>
                                    <form action="{{ route('admin.stocks.destroy', $stock->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this stock?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-xl" title="Delete">
                                            <i class="fas fa-trash text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-400">No products in this category</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
        @endforeach
        @if($categories->isEmpty())
            <div class="px-6 py-4 text-center text-gray-500">No categories found</div>
        @endif
    </div>


</div>

<!-- youtube Video Modal starts here -->
<div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="videoModalTitle">Product Video</h3>
                <button onclick="closeVideoModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Video Container -->
            <div class="relative w-full" style="padding-bottom: 56.25%;">
                <iframe id="videoIframe" 
                        class="absolute top-0 left-0 w-full h-full rounded-lg"
                        src=""
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            </div>
            
            <!-- Modal Footer -->
            <!-- <div class="flex justify-end mt-4">
                <button onclick="closeVideoModal()" 
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                    Close
                </button>
            </div> -->
        </div>
    </div>
</div>
<!-- youtube Video Modal ends here -->

<!-- Import CSV Modal starts here -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Import Stock Data (CSV/Excel)</h3>

            <!-- Step 1: File Upload -->
            <div id="uploadStep" class="space-y-4">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-4">
                        Upload a CSV or Excel file with stock data. Download the template first to see the required format.
                    </p>
                    <!-- download Template -->
                    <div class="flex gap-2 mb-4">
                        <a href="{{ route('admin.stocks.download-template') }}?format=csv" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                            Download CSV Template
                        </a>
                        <a href="{{ route('admin.stocks.download-template') }}?format=xlsx" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                            Download Excel Template
                        </a>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">File (CSV, Excel)</label>
                    <input type="file" id="csvFile" name="csv_file" accept=".csv,.txt,.xlsx,.xls" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div id="fileError" class="text-red-500 text-xs hidden"></div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeImportModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                    <button type="button" onclick="previewFile()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview Data
                    </button>
                </div>
            </div>

            <!-- Step 2: Preview Table -->
            <div id="previewStep" class="hidden space-y-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-md font-medium text-gray-900">File Preview</h4>
                        <p id="fileInfo" class="text-sm text-gray-600"></p>
                    </div>
                    <button type="button" onclick="backToUpload()" class="text-blue-600 hover:text-blue-800 text-sm">
                        ← Back to Upload
                    </button>
                </div>

                <div class="overflow-x-auto max-h-96 border border-gray-200 rounded-lg">
                    <div id="loadingPreview" class="hidden flex items-center justify-center p-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600">Loading preview...</span>
                    </div>
                    <table id="previewTable" class="min-w-full bg-white">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr id="tableHeaders"></tr>
                        </thead>
                        <tbody id="tableBody" class="divide-y divide-gray-200"></tbody>
                    </table>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeImportModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                    <button type="button" onclick="confirmImport()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Confirm Import
                    </button>
                </div>
            </div>

            <!-- Hidden form for final submission -->
            <form id="importForm" action="{{ route('admin.stocks.import-csv') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" name="csv_file" id="hiddenFileInput">
            </form>
        </div>
    </div>
</div>
<!-- Import CSV Modal ends here -->

<script>
let currentFile = null;

function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
    resetModal();
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    resetModal();
}

function resetModal() {
    document.getElementById('uploadStep').classList.remove('hidden');
    document.getElementById('previewStep').classList.add('hidden');
    document.getElementById('csvFile').value = '';
    document.getElementById('fileError').classList.add('hidden');
    document.getElementById('loadingPreview').classList.add('hidden');
    document.getElementById('previewTable').classList.remove('hidden');
    currentFile = null;
}

function showError(message) {
    // Go back to upload step and show error
    document.getElementById('uploadStep').classList.remove('hidden');
    document.getElementById('previewStep').classList.add('hidden');
    document.getElementById('fileError').textContent = message;
    document.getElementById('fileError').classList.remove('hidden');
}

function backToUpload() {
    document.getElementById('uploadStep').classList.remove('hidden');
    document.getElementById('previewStep').classList.add('hidden');
}

function previewFile() {
    const fileInput = document.getElementById('csvFile');
    const fileError = document.getElementById('fileError');
    
    if (!fileInput.files[0]) {
        fileError.textContent = 'Please select a file first.';
        fileError.classList.remove('hidden');
        return;
    }
    
    currentFile = fileInput.files[0];
    
    // Show preview step with loading
    document.getElementById('uploadStep').classList.add('hidden');
    document.getElementById('previewStep').classList.remove('hidden');
    document.getElementById('loadingPreview').classList.remove('hidden');
    document.getElementById('previewTable').classList.add('hidden');
    
    // Show loading state on button
    const previewBtn = event.target.closest('button');
    const originalText = previewBtn.innerHTML;
    previewBtn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...';
    previewBtn.disabled = true;
    
    const formData = new FormData();
    formData.append('csv_file', currentFile);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("admin.stocks.preview-import") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayPreview(data);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        showError('Failed to preview file: ' + error.message);
    })
    .finally(() => {
        previewBtn.innerHTML = originalText;
        previewBtn.disabled = false;
    });
}

function displayPreview(data) {
    // Hide loading and show table
    document.getElementById('loadingPreview').classList.add('hidden');
    document.getElementById('previewTable').classList.remove('hidden');
    
    // Update file info
    document.getElementById('fileInfo').textContent = `${data.filename} - ${data.total_rows} rows (showing first 10)`;
    
    // Create headers
    const headerRow = document.getElementById('tableHeaders');
    headerRow.innerHTML = '';
    data.headers.forEach(header => {
        const th = document.createElement('th');
        th.className = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider';
        th.textContent = header;
        headerRow.appendChild(th);
    });
    
    // Create table body
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = '';
    
    data.preview_data.forEach((row, index) => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50';
        
        data.headers.forEach(header => {
            const td = document.createElement('td');
            td.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
            
            // Handle different data formats
            let value = '';
            if (Array.isArray(row)) {
                // CSV format - row is an array
                const headerIndex = data.headers.indexOf(header);
                value = row[headerIndex] || '';
            } else {
                // Excel format - row is an object
                value = row[header] || '';
            }
            
            // Format the value based on header type
            if (header === 'price' || header === 'original_price') {
                value = value ? '₹' + parseFloat(value).toFixed(2) : '';
            } else if (header === 'quantity') {
                value = value ? parseInt(value).toLocaleString() : '';
            } else if (header === 'is_active') {
                value = value == '1' ? 'Active' : 'Inactive';
            }
            
            td.textContent = value;
            tr.appendChild(td);
        });
        
        tableBody.appendChild(tr);
    });
    
    document.getElementById('fileError').classList.add('hidden');
}

function confirmImport() {
    if (!currentFile) {
        alert('No file selected for import.');
        return;
    }
    
    // Set the file in the hidden form
    const hiddenInput = document.getElementById('hiddenFileInput');
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(currentFile);
    hiddenInput.files = dataTransfer.files;
    
    // Submit the form
    document.getElementById('importForm').submit();
}

// Close modal when clicking outside
document.getElementById('importModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImportModal();
    }
});

// Video Modal Functions
function openVideoModal(youtubeUrl, productName) {
    // Convert YouTube URL to embed URL
    const videoId = extractYouTubeVideoId(youtubeUrl);
    if (!videoId) {
        alert('Invalid YouTube URL');
        return;
    }
    
    const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
    
    // Set modal content
    document.getElementById('videoModalTitle').textContent = `${productName} - Video`;
    document.getElementById('videoIframe').src = embedUrl;
    
    // Show modal
    document.getElementById('videoModal').classList.remove('hidden');
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeVideoModal() {
    // Hide modal
    document.getElementById('videoModal').classList.add('hidden');
    
    // Clear video source to stop playback
    document.getElementById('videoIframe').src = '';
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

function extractYouTubeVideoId(url) {
    const patterns = [
        /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/,
        /youtube\.com\/watch\?.*v=([^&\n?#]+)/,
        /youtu\.be\/([^&\n?#]+)/
    ];
    
    for (const pattern of patterns) {
        const match = url.match(pattern);
        if (match) {
            return match[1];
        }
    }
    
    return null;
}

// Close video modal when clicking outside
document.getElementById('videoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVideoModal();
    }
});

// Close video modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeVideoModal();
    }
});
</script>
@endsection