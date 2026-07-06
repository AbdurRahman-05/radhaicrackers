<div class="p-6 bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Stock Management</h2>
            <p class="text-gray-600">Manage inventory with auto-release and expiry</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 mt-4 sm:mt-0">
            <a href="{{ route('admin.stocks.add') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Product
            </a>
            <button wire:click="showAddStock" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Quick Add
            </button>
            <button wire:click="autoReleaseStocks" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Auto Release
            </button>
            <button wire:click="autoExpireStocks" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Auto Expire
            </button>
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

    <!-- Action Buttons -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('admin.stocks.add') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Product
            </a>
            <button wire:click="showAddStock" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Quick Add
            </button>
            <button wire:click="autoReleaseStocks" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Auto Release (10 units)
            </button>
            <button wire:click="autoExpireStocks" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Auto Expire (10 mins)
            </button>
            <button wire:click="exportStocks" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </button>
            <button wire:click="showBulkUpload" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Bulk Upload
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input wire:model.live="search" type="text" placeholder="Stock name, description" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="available">Available</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
        </div>
        <div class="mt-4">
            <button wire:click="clearFilters" class="text-gray-600 hover:text-gray-800 text-sm">Clear Filters</button>
        </div>
    </div>

    <!-- Stocks Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Released</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($stocks as $stock)
                <tr class="hover:bg-gray-50">
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">₹{{ number_format($stock->quantity * $stock->price, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($stock->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $stock->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($stock->quantity > 0)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Available
                            </span>
                            @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                Out of Stock
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button wire:click="showEditStock({{ $stock->id }})" class="text-blue-600 hover:text-blue-900">Edit</button>
                            <button wire:click="toggleStockStatus({{ $stock->id }})" class="text-yellow-600 hover:text-yellow-900">
                                {{ $stock->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                            <button wire:click="manualRelease({{ $stock->id }})" class="text-green-600 hover:text-green-900">Release</button>
                            <button wire:click="resetStock({{ $stock->id }})" class="text-red-600 hover:text-red-900">Reset</button>
                            <button wire:click="toggleShowOnShop({{ $stock->id }})"
                                class="w-8 h-8 rounded-full flex items-center justify-center border-2 transition-colors {{ $stock->show_on_shop ? 'bg-gray-500 border-gray-600 text-white' : 'bg-gray-200 border-gray-300 text-gray-600' }}"
                                title="Show on Shop">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
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