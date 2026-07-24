<div class="p-4 sm:p-6 bg-white rounded-lg shadow-md">
    <!-- Header & Action Buttons -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4 border-b pb-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Stock Management</h2>
            <p class="text-xs sm:text-sm text-gray-600">Manage inventory, product pricing & stock levels</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.stocks.add') }}" class="bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold px-3 py-2 rounded-md flex items-center gap-1">
                <i class="fas fa-plus"></i> Add Product
            </a>
            <button wire:click="showAddStock" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-md flex items-center gap-1">
                <i class="fas fa-bolt"></i> Quick Add
            </button>
            <button wire:click="exportStocks" class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-3 py-2 rounded-md flex items-center gap-1">
                <i class="fas fa-file-csv"></i> Export CSV
            </button>
            <button wire:click="showBulkUpload" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold px-3 py-2 rounded-md flex items-center gap-1">
                <i class="fas fa-file-upload"></i> Bulk Upload
            </button>
            <button wire:click="autoReleaseStocks" class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-3 py-2 rounded-md flex items-center gap-1" title="Auto release 10 units">
                <i class="fas fa-magic"></i> Auto Release
            </button>
            <button wire:click="autoExpireStocks" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-3 py-2 rounded-md flex items-center gap-1" title="Auto expire 10 mins">
                <i class="fas fa-clock"></i> Auto Expire
            </button>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        <div class="bg-blue-50 p-3 rounded-lg text-center border border-blue-100">
            <div class="text-lg sm:text-xl font-bold text-blue-600">{{ $totalStocks }}</div>
            <div class="text-xs text-blue-700 font-medium">Total Items</div>
        </div>
        <div class="bg-green-50 p-3 rounded-lg text-center border border-green-100">
            <div class="text-lg sm:text-xl font-bold text-green-600">{{ $activeStocks }}</div>
            <div class="text-xs text-green-700 font-medium">Active Items</div>
        </div>
        <div class="bg-amber-50 p-3 rounded-lg text-center border border-amber-100">
            <div class="text-lg sm:text-xl font-bold text-amber-600">{{ $availableStocks }}</div>
            <div class="text-xs text-amber-700 font-medium">Available</div>
        </div>
        <div class="bg-rose-50 p-3 rounded-lg text-center border border-rose-100">
            <div class="text-lg sm:text-xl font-bold text-rose-600">{{ $outOfStock }}</div>
            <div class="text-xs text-rose-700 font-medium">Out of Stock</div>
        </div>
        <div class="bg-purple-50 p-3 rounded-lg text-center border border-purple-100 col-span-2 sm:col-span-1">
            <div class="text-lg sm:text-xl font-bold text-purple-600">₹{{ number_format($totalValue, 2) }}</div>
            <div class="text-xs text-purple-700 font-medium">Stock Value</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Search Stock</label>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Product name or category..." class="w-full px-3 py-1.5 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Year (Order Stats)</label>
                <select wire:model.live="selected_year" class="w-full px-3 py-1.5 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Years</option>
                    @foreach($available_years as $yr)
                        <option value="{{ $yr }}" wire:key="yr-opt-{{ $yr }}">{{ $yr }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Status Filter</label>
                <select wire:model.live="status_filter" class="w-full px-3 py-1.5 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="available">Available</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
        </div>
        <div class="mt-3 flex justify-between items-center border-t border-gray-200 pt-2">
            <button wire:click="clearFilters" class="text-gray-600 hover:text-gray-800 text-xs font-semibold">Clear Filters</button>
            <button wire:click="exportOrderedItems" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold flex items-center gap-1">
                <i class="fas fa-file-export"></i> Export Ordered Items
            </button>
        </div>
    </div>

    <!-- Stocks Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 text-xs sm:text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-2 py-2 text-left font-bold text-gray-600 uppercase tracking-wider">S.No</th>
                    <th class="px-2 py-2 text-left font-bold text-gray-600 uppercase tracking-wider">Product Info</th>
                    <th class="px-2 py-2 text-left font-bold text-gray-600 uppercase tracking-wider">Qty & Price</th>
                    <th class="px-2 py-2 text-left font-bold text-gray-600 uppercase tracking-wider">Total Value</th>
                    <th class="px-2 py-2 text-left font-bold text-gray-600 uppercase tracking-wider">Orders</th>
                    <th class="px-2 py-2 text-left font-bold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-2 py-2 text-left font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($stocks as $stock)
                <tr class="hover:bg-gray-50" wire:key="stock-row-{{ $stock->id }}">
                    <td class="px-2 py-2 whitespace-nowrap font-bold text-gray-800">
                        {{ $catalogSnoMap[$stock->id] ?? '-' }}
                    </td>
                    <td class="px-2 py-2">
                        <div class="flex items-center space-x-2">
                            @if($stock->image_url)
                                <img src="{{ $stock->image_url }}" alt="{{ $stock->item_name }}" class="w-8 h-8 object-cover rounded flex-shrink-0">
                            @else
                                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center flex-shrink-0 text-sm">
                                    🎆
                                </div>
                            @endif
                            <div class="min-w-0">
                                <div class="font-bold text-gray-900 truncate max-w-[200px]" title="{{ $stock->item_name }}">{{ $stock->item_name }}</div>
                                @if($stock->category)
                                    <div class="text-[11px] text-blue-600 font-medium">{{ $stock->category }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap">
                        <div class="font-bold text-gray-900">₹{{ number_format($stock->price, 2) }}</div>
                        <div class="text-xs text-gray-500">Qty: <span class="font-bold {{ $stock->quantity <= 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $stock->quantity }}</span></div>
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap font-semibold text-gray-900">
                        ₹{{ number_format($stock->quantity * $stock->price, 2) }}
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-800">
                            {{ $stock->ordered_count }} orders
                        </span>
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap">
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex px-1.5 py-0.5 text-[10px] font-bold rounded-full w-max
                                @if($stock->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $stock->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($stock->quantity > 0)
                                <span class="inline-flex px-1.5 py-0.5 text-[10px] font-bold rounded-full bg-blue-100 text-blue-800 w-max">Available</span>
                            @else
                                <span class="inline-flex px-1.5 py-0.5 text-[10px] font-bold rounded-full bg-rose-100 text-rose-800 w-max">Out of Stock</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <button wire:click="showEditStock({{ $stock->id }})" class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded text-sm" title="Edit Product">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="toggleStockStatus({{ $stock->id }})" class="p-1 text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded text-sm" title="{{ $stock->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="fas fa-toggle-{{ $stock->is_active ? 'on' : 'off' }}"></i>
                            </button>
                            <button wire:click="manualRelease({{ $stock->id }})" class="p-1 text-green-600 hover:text-green-800 hover:bg-green-50 rounded text-sm" title="Release 1 Unit">
                                <i class="fas fa-rocket"></i>
                            </button>
                            <button wire:click="resetStock({{ $stock->id }})" class="p-1 text-red-500 hover:text-red-700 hover:bg-red-50 rounded text-sm" title="Reset Stock">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button wire:click="toggleShowOnShop({{ $stock->id }})"
                                class="p-1 rounded text-sm transition-colors {{ $stock->show_on_shop ? 'text-gray-700 hover:bg-gray-200' : 'text-gray-400 hover:bg-gray-100' }}"
                                title="{{ $stock->show_on_shop ? 'Visible in Shop' : 'Hidden in Shop' }}">
                                <i class="fas fa-eye{{ $stock->show_on_shop ? '' : '-slash' }}"></i>
                            </button>
                            <button wire:click="toggleShowOnHome({{ $stock->id }})"
                                class="p-1 rounded text-sm transition-colors {{ $stock->show_on_home ? 'text-amber-500 hover:bg-amber-50' : 'text-gray-400 hover:bg-gray-100' }}"
                                title="{{ $stock->show_on_home ? 'Featured on Home' : 'Not Featured' }}">
                                <i class="fas fa-star"></i>
                            </button>
                            @if($stock->youtube_url)
                            <a href="{{ $stock->youtube_url }}" target="_blank" class="p-1 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded text-sm" title="Watch Video">
                                <i class="fas fa-video"></i>
                            </a>
                            @endif
                            <button wire:click="deleteStock({{ $stock->id }})" class="p-1 text-red-600 hover:text-red-800 hover:bg-red-50 rounded text-sm" title="Delete Product">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No stocks found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Stock Modal -->
    @if($showAddModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="addStockModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Stocks</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                        <input wire:model="item_name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('item_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select wire:model="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Category</option>
                            <option value="BIJILI CRACKERS">BIJILI CRACKERS</option>
                            <option value="BOMBS">BOMBS</option>
                            <option value="CHIT PUT">CHIT PUT</option>
                            <option value="GIFT BOX">GIFT BOX</option>
                            <option value="BOXING">BOXING</option>
                            <option value="ROCKETS">ROCKETS</option>
                            <option value="SINGLE FLASH">SINGLE FLASH</option>
                            <option value="SPARKLERS">SPARKLERS</option>
                            <option value="TWINKLING STAR">TWINKLING STAR</option>
                        </select>
                        @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <input wire:model="quantity" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                            <input wire:model="price" type="number" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Original Price</label>
                            <input wire:model="original_price" type="number" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('original_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount %</label>
                            <input wire:model="discount_percentage" type="number" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('discount_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex items-center">
                        <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label class="ml-2 text-sm text-gray-700">Active</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                        <input wire:model="image" type="file" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        
                        @if($imagePreview)
                            <div class="mt-2">
                                <img src="{{ $imagePreview }}" alt="Preview" class="w-20 h-20 object-cover rounded-lg border">
                            </div>
                        @endif
                    </div>
                 



                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="showAddModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                    <button wire:click="addStock" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Stock</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Stock Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="editStockModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Stock</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                        <input wire:model="item_name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('item_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select wire:model="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Category</option>
                            <option value="BIJILI CRACKERS">BIJILI CRACKERS</option>
                            <option value="BOMBS">BOMBS</option>
                            <option value="CHIT PUT">CHIT PUT</option>
                            <option value="GIFT BOX">GIFT BOX</option>
                            <option value="BOXING">BOXING</option>
                            <option value="ROCKETS">ROCKETS</option>
                            <option value="SINGLE FLASH">SINGLE FLASH</option>
                            <option value="SPARKLERS">SPARKLERS</option>
                            <option value="TWINKLING STAR">TWINKLING STAR</option>
                        </select>
                        @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <input wire:model="quantity" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                            <input wire:model="price" type="number" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Original Price</label>
                            <input wire:model="original_price" type="number" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('original_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount %</label>
                            <input wire:model="discount_percentage" type="number" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('discount_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex items-center">
                        <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label class="ml-2 text-sm text-gray-700">Active</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                        <input wire:model="image" type="file" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        
                        @if($imagePreview)
                            <div class="mt-2">
                                <img src="{{ $imagePreview }}" alt="Preview" class="w-20 h-20 object-cover rounded-lg border">
                                @if($editingStock && $editingStock->image)
                                    <button wire:click="removeImage" type="button" class="mt-1 text-xs text-red-600 hover:text-red-800">
                                        Remove Image
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                    <!-- Youtube URL Input -->
                   
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="showEditModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                    <button wire:click="updateStock" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Stock</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Bulk Upload Modal -->
    @if($showBulkUploadModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="bulkUploadModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Upload Stocks</h3>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-4">
                        Upload a CSV file with stock data. Download the template first to see the required format.
                    </p>
                    <button wire:click="downloadTemplate" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors mb-4">
                        Download Template
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CSV File</label>
                        <input wire:model="csv_file" type="file" accept=".csv,.txt" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('csv_file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if($bulk_upload_progress > 0)
                    <div class="mb-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $bulk_upload_progress }}%"></div>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Uploading... {{ number_format($bulk_upload_progress, 1) }}%</p>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="showBulkUploadModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                    <button wire:click="bulkUpload" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Upload</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('download-csv', () => {
        // Redirect to the export route to trigger download
        window.location.href = '{{ route("admin.export.stocks") }}';
    });
});
</script> 