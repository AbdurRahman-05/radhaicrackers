<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Shop</h1>
            <p class="text-gray-600 mt-2">Browse our collection of premium crackers</p>
        </div>



        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <!-- Search -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Search</h3>
                        <input wire:model.live="search" type="text" placeholder="Search products..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <!-- Categories -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Categories</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input wire:model.live="category_filter" type="radio" value="" class="mr-2">
                                <span class="text-sm">All Categories</span>
                            </label>
                            @foreach($categories as $category => $count)
                                @if($count > 0)
                                <label class="flex items-center">
                                    <input wire:model.live="category_filter" type="radio" value="{{ $category }}" class="mr-2">
                                    <span class="text-sm">{{ $category }} ({{ $count }})</span>
                                </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Price Range</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input wire:model.live="price_filter" type="radio" value="" class="mr-2">
                                <span class="text-sm">All Prices</span>
                            </label>
                            <label class="flex items-center">
                                <input wire:model.live="price_filter" type="radio" value="0-90" class="mr-2">
                                <span class="text-sm">₹0 – ₹90</span>
                            </label>
                            <label class="flex items-center">
                                <input wire:model.live="price_filter" type="radio" value="180-270" class="mr-2">
                                <span class="text-sm">₹180 – ₹270</span>
                            </label>
                            <label class="flex items-center">
                                <input wire:model.live="price_filter" type="radio" value="360-450" class="mr-2">
                                <span class="text-sm">₹360 – ₹450</span>
                            </label>
                            <label class="flex items-center">
                                <input wire:model.live="price_filter" type="radio" value="450+" class="mr-2">
                                <span class="text-sm">₹450+</span>
                            </label>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    @if($search || $category_filter || $price_filter)
                    <div class="mb-6">
                        <button wire:click="clearFilters" class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors">
                            Clear Filters
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
<div>
                            <h2 class="text-2xl font-bold text-gray-900">Products</h2>
                            <p class="text-sm text-gray-600 mt-1">Showing {{ $products->total() }} results</p>
                        </div>
                        <div class="flex items-center space-x-4 mt-4 sm:mt-0">
                            <select wire:model.live="sort_by" class="border border-gray-300 rounded px-3 py-1 text-sm">
                                <option value="created_at">Latest</option>
                                <option value="price">Price</option>
                                <option value="item_name">Name</option>
                            </select>
                            <button wire:click="sortBy('{{ $sort_by }}')" class="text-gray-600 hover:text-gray-800">
                                @if($sort_direction === 'asc')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </button>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($products as $product)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                                <!-- Discount Badge -->
                                @if($product->discount_percentage)
                                    <div class="bg-red-500 text-white text-xs px-2 py-1 rounded mb-2 inline-block">
                                        -{{ $product->discount_percentage }}% OFF
                                    </div>
                                @endif
                                
                                <!-- Product Image -->
                                <div class="text-center mb-4">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->item_name }}" 
                                             class="w-24 h-24 object-cover rounded-lg mx-auto mb-2">
                                    @else
                                        <div class="text-4xl mb-2">
                                            {{ $this->getCategoryIcon($product->category) }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Product Name -->
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $product->item_name }}</h3>
                                
                                <!-- Description -->
                                @if($product->description)
                                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($product->description, 80) }}</p>
                                @endif
                                
                                <!-- Price -->
                                <div class="mb-3">
                                    @if($product->original_price && $product->original_price > $product->price)
                                        <span class="line-through text-gray-400 mr-2">₹{{ number_format($product->original_price, 0) }}</span>
                                    @endif
                                    <span class="text-orange-600 font-bold text-lg">₹{{ number_format($product->price, 0) }}</span>
                                </div>
                                
                                <!-- Stock Status -->
                                <div class="mb-3">
                                    @if($product->quantity > 0)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            In Stock ({{ $product->quantity }})
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Add to Cart Button -->
                                <button wire:click="addToCart({{ $product->id }})" 
                                        @if($product->quantity <= 0) disabled @endif
                                        class="w-full bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed">
                                    @if($product->quantity > 0)
                                        Add to Cart
                                    @else
                                        Out of Stock
                                    @endif
                                </button>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
