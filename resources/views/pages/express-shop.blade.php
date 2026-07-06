@extends('layouts.app')

@section('title', 'Quotation - Radhe Crackers')

@section('content')
<div class="bg-white py-8 pb-24">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Breadcrumb and Category Dropdown Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-2">
        <!-- Breadcrumb -->
            <nav class="text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li><a href="/" class="hover:underline">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-700">Quotation</li>
            </ol>
        </nav>
            <!-- Category Dropdown (Mobile/Tablet) -->
            <div class="sm:ml-4 w-full sm:w-auto">
                <button id="categoryDropdownToggle" class="w-full sm:w-auto bg-white border border-gray-300 rounded-lg p-3 flex items-center justify-between shadow-sm font-semibold">
                    <span class="font-semibold text-gray-900">Shop By Categories</span>
                    <svg id="categoryDropdownIcon" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="categoryDropdownMenu" class="bg-white rounded-lg shadow-md border mt-2 p-2 space-y-1 hidden z-20 absolute w-64 max-w-full">
                    <ul class="space-y-2">
                        <li><a href="{{ route('express-shop') }}" class="text-gray-700 hover:text-yellow-600">All Products</a></li>
                        @php
                            $dbCategories = \App\Models\Category::orderBy('sort_order')->orderBy('name')->get();
                            $categories = [];
                            $iconMap = [
                                'BIJILI CRACKERS' => '⚡',
                                'BOMBS' => '💣',
                                'CHIT PUT' => '🎆',
                                'GIFT BOX' => '🎁',
                                'ROCKETS' => '🚀',
                                'SINGLE FLASH' => '⚡',
                                'SPARKLERS' => '✨',
                                'TWINKLING STAR' => '⭐',
                                'test1' => '🎆',
                                'test2' => '🎆',
                            ];
                            foreach ($dbCategories as $category) {
                                $count = \App\Models\Stock::query()
                                    ->where(function($q) {
                                        $q->where(function($sub) {
                                            $sub->where('is_active', 1)
                                                ->where('quantity', '>', 0);
                                        })->orWhere('show_on_shop', 1);
                                    })
                                    ->where(function($q) use ($category) {
                                        $q->where('category', $category->name)
                                          ->orWhere('category', $category->id);
                                    })
                                    ->count();
                                $icon = $category->icon ?: ($iconMap[$category->name] ?? '🎆');
                                $categories[$category->name] = [
                                    'icon' => $icon,
                                    'count' => $count
                                ];
                            }
                            // Store original stockData for JavaScript (unfiltered)
                            $originalStockData = $stockData;
                            
                            // Filter $stockData by selected category if present (for display only)
                            $selectedCategory = request('category');
                            if ($selectedCategory && isset($stockData[$selectedCategory])) {
                                $stockData = [$selectedCategory => $stockData[$selectedCategory]];
                            }
                        @endphp
                        @foreach($categories as $category => $data)
                            <li>
                                <a href="?category={{ urlencode($category) }}" 
                                   class="flex items-center justify-between text-gray-700 hover:text-orange-500 {{ request('category') == $category ? 'text-orange-500 font-medium' : '' }}">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg">{{ $data['icon'] }}</span>
                                        <span>{{ $category }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">({{ $data['count'] }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Page Title -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 text-center">Quotation</h1>
        
        @if(empty($stockData))
            <div class="text-center py-12">
                <div class="text-4xl mb-4">🎆</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Products Available</h3>
                <p class="text-gray-600">Check back later for amazing fireworks!</p>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content -->
                <div class="w-full">
                    @foreach($dbCategories->sortBy('sort_order') as $category)
                        @if(isset($stockData[$category->name]))
                            <div id="category-{{ Str::slug($category->name) }}" class="mb-8">
                                <h2 class="text-xl font-bold mb-4">
                                    <span class="text-yellow-600">{{ $category->sort_order }}.</span>
                                    {{ $category->name }}
                                </h2>
                                
                                @php
                                    $stocks = collect($stockData[$category->name])->sortBy('order_within_category');
                                @endphp
                                
                                <!-- Desktop View -->
                                <div class="overflow-x-auto w-full hidden sm:block">
                                    <table class="min-w-full w-full border border-gray-200 rounded-lg">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($stocks as $stock)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="relative">
                                                            @if($stock->image)
                                                                <img src="{{ asset('storage/' . $stock->image) }}" 
                                                                     alt="{{ $stock->item_name }}" 
                                                                     class="w-16 h-16 object-cover rounded-lg">
                                                            @else
                                                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                                    <span class="text-2xl">
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
                                                            @if($stock->youtube_url)
                                                                <a href="{{ $stock->youtube_url }}" target="_blank" class="absolute top-1 right-1 bg-white rounded-full p-1 shadow">
                                                                    <svg class="w-7 h-7 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $stock->item_name }}</div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500">{{ $stock->description ?: 'No description available' }}</div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            @if($stock->original_price && $stock->original_price > $stock->price)
                                                                <span class="line-through text-gray-400 mr-2">₹{{ number_format($stock->original_price, 2) }}</span>
                                                                <span class="font-semibold text-red-600">₹{{ number_format($stock->price, 2) }}</span>
                                                                <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 mt-1">
                                                                    @if($stock->discount_percentage)
                                                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded mb-1 sm:mb-0">{{ $stock->discount_percentage }}% OFF</span>
                                                                    @endif
                                                                    @if($stock->special_discount_percentage)
                                                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">+{{ $stock->special_discount_percentage }}% Special</span>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <span class="font-semibold">₹{{ number_format($stock->price, 2) }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="flex items-center space-x-2">
                                                            <button type="button" 
                                                                    onclick="updateQuantity({{ $stock->id }}, -1)" 
                                                                    class="w-8 h-8 text-white rounded-full flex items-center justify-center hover:bg-gray-300"  style="background-color:rgb(182, 113, 33);">
                                                                -
                                                            </button>
                                                            <input type="number" 
                                                                   id="quantity-{{ $stock->id }}" 
                                                                   value="0" 
                                                                   min="0" 
                                                                   onchange="setManualQuantity({{ $stock->id }}, this.value, false)" 
                                                                   class="w-12 text-center bg-gray-50 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-orange-500 text-sm font-semibold text-gray-900 p-1 mx-1" 
                                                                   style="-moz-appearance: textfield; appearance: textfield; font-size: 14px;">
                                                            <button type="button" 
                                                                    onclick="updateQuantity({{ $stock->id }}, 1)" 
                                                                    class="w-8 h-8 text-white rounded-full flex items-center justify-center hover:bg-gray-300"  style="background-color:rgb(182, 113, 33);">
                                                                +
                                                            </button>
                                                            <button type="button" 
                                                                    onclick="removeItem({{ $stock->id }})" 
                                                                    class="ml-2 text-red-500 hover:text-red-700">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="text-xs text-gray-500 mt-1">Available: {{ $stock->quantity }}</div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900" id="total-{{ $stock->id }}">₹0.00</div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Mobile View -->
                                <div class="flex flex-col gap-4 sm:hidden">
                                    @foreach($stocks as $stock)
                                        <div class="bg-white rounded-lg shadow p-3 flex flex-row items-start justify-between gap-2">
                                            <div class="flex flex-col items-start w-1/2">
                                                <div class="relative">
                                                    @if($stock->image)
                                                        <img src="{{ asset('storage/' . $stock->image) }}" 
                                                             alt="{{ $stock->item_name }}" 
                                                             class="w-16 h-16 object-cover rounded-lg">
                                                    @else
                                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                            <span class="text-2xl">
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
                                                    @if($stock->youtube_url)
                                                        <a href="{{ $stock->youtube_url }}" target="_blank" class="absolute top-1 right-1 bg-white rounded-full p-1 shadow">
                                                            <svg class="w-7 h-7 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                                        </a>
                                                    @endif
                                                </div>
                                                <span class="text-lg font-semibold text-gray-900">{{ $stock->item_name }}</span>
                                                <p class="text-sm text-gray-500">{{ $stock->description ?: 'No description available' }}</p>
                                            </div>
                                            <div class="flex flex-col items-end w-1/2 gap-2">
                                                <div class="flex items-center justify-between text-sm text-gray-900 font-semibold mb-2">
                                                    @if($stock->original_price && $stock->original_price > $stock->price)
                                                        <span class="line-through text-gray-400">₹{{ number_format($stock->original_price, 2) }}</span>
                                                        <span class="font-semibold text-red-600">₹{{ number_format($stock->price, 2) }}</span>
                                                    @else
                                                        <span class="font-semibold">₹{{ number_format($stock->price, 2) }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                                                    @if($stock->discount_percentage)
                                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">{{ $stock->discount_percentage }}% OFF</span>
                                                    @endif
                                                    @if($stock->special_discount_percentage)
                                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">+{{ $stock->special_discount_percentage }}% Special</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <button type="button" 
                                                            onclick="updateQuantity({{ $stock->id }}, -1)" 
                                                            class="w-8 h-8 text-white rounded-full flex items-center justify-center hover:bg-gray-300"  style="background-color:rgb(182, 113, 33);">
                                                        -
                                                    </button>
                                                    <input type="number" 
                                                           id="mobile-quantity-{{ $stock->id }}" 
                                                           value="0" 
                                                           min="0" 
                                                           onchange="setManualQuantity({{ $stock->id }}, this.value, true)" 
                                                           class="w-12 text-center bg-gray-50 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-orange-500 text-sm font-semibold text-gray-900 p-1 mx-1" 
                                                           style="-moz-appearance: textfield; appearance: textfield; font-size: 14px;">
                                                    <button type="button" 
                                                            onclick="updateQuantity({{ $stock->id }}, 1)" 
                                                            class="w-8 h-8 text-white rounded-full flex items-center justify-center hover:bg-gray-300"  style="background-color:rgb(182, 113, 33);">
                                                        +
                                                    </button>
                                                </div>
                                                <div class="text-xs text-gray-500">Available: {{ $stock->quantity }}</div>
                                                <div class="text-sm font-medium text-gray-900" id="mobile-total-{{ $stock->id }}">₹0.00</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="mt-8 border-t border-gray-200 pt-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <!-- Left side: Total and Coupon -->
                    <div class="flex flex-col gap-4">
                        <div class="text-lg font-semibold text-gray-900">Total: ₹<span id="cart-total">0.00</span></div>
                    </div>

                    <!-- Right side: Action Buttons -->
                    <div class="flex items-center gap-3">
                        <button
                            onclick="generateEstimate()"
                            id="estimate-pdf-btn"
                            class="text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors bg-[#B67121] disabled:bg-gray-400 disabled:cursor-not-allowed"  
                            disabled
                        >Estimate</button>
                        <button
                            id="checkout-btn"
                            onclick="proceedToCheckout()" 
                            class="text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors font-semibold bg-[#1E093B] disabled:bg-gray-400 disabled:cursor-not-allowed"
                            disabled
                        >
                            Checkout
                        </button>
                    </div>
                </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Detailed Cart Summary Modal / Floating Box -->
<div class="fixed bottom-4 right-4 z-50 max-w-sm w-[calc(100vw-32px)] sm:w-96 font-sans" id="cart-summary-wrapper" style="display: none;">
    <!-- Minimize Cart Icon Badge (visible when minimized) -->
    <button onclick="toggleCartDrawer()" id="cart-badge-trigger" class="hidden absolute bottom-0 right-0 bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-full p-4 shadow-2xl hover:scale-105 transition-all flex items-center justify-center gap-2 border-2 border-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/></svg>
        <span class="bg-white text-orange-600 font-extrabold text-xs px-2 py-1 rounded-full border border-orange-200" id="cart-badge-count">0</span>
    </button>

    <!-- Detailed Cart Drawer Panel -->
    <div id="cart-summary-panel" class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all duration-300 flex flex-col max-h-[500px]">
        <!-- Panel Header -->
        <div class="px-4 py-3 text-white flex items-center justify-between" style="background-color: #1E093B;">
            <div class="flex items-center gap-2">
                <span class="text-xl">🛒</span>
                <span class="font-bold text-sm sm:text-base">Estimate Cart (<span id="cart-items-count">0</span>)</span>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="clearCart()" class="text-xs text-red-300 hover:text-red-200 transition-colors font-semibold uppercase tracking-wider">Clear</button>
                <button onclick="toggleCartDrawer()" class="text-gray-300 hover:text-white transition-colors" title="Minimize">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
        </div>

        <!-- Scrollable items list -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3 divide-y divide-gray-50 max-h-60" id="cart-items-list">
            <!-- Populated dynamically via JS -->
        </div>

        <!-- Calculations Footer -->
        <div class="bg-gray-50 border-t border-gray-100 p-4 space-y-2">
            <div class="flex justify-between text-xs sm:text-sm text-gray-600">
                <span>Items Subtotal:</span>
                <span id="summary-subtotal">₹0.00</span>
            </div>
            <div class="flex justify-between text-xs sm:text-sm text-orange-600 font-medium">
                <span>Delivery/Packing Fee (+5%):</span>
                <span id="summary-packing">₹0.00</span>
            </div>
            <hr class="border-gray-200">
            <div class="flex justify-between text-base sm:text-lg font-extrabold text-gray-900">
                <span>Total Amount:</span>
                <span id="summary-total">₹0.00</span>
            </div>
            
            <div class="grid grid-cols-2 gap-2 mt-2">
                <button onclick="generateEstimate()" id="summary-estimate-btn" class="text-white py-3 rounded-xl text-xs sm:text-sm font-bold shadow transition-colors flex items-center justify-center bg-[#B67121] hover:bg-orange-600">
                    Estimate PDF
                </button>
                <button onclick="proceedToCheckout()" class="text-white py-3 rounded-xl text-xs sm:text-sm font-bold shadow transition-colors flex items-center justify-center gap-1 bg-[#1E093B] hover:bg-opacity-90">
                    <span>Checkout</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Store product data (using original unfiltered data for cart management)
const products = {
    @foreach($originalStockData as $category => $stocks)
        @foreach($stocks as $stock)
            {{ $stock->id }}: {
                id: {{ $stock->id }},
                name: "{{ addslashes(str_replace(["\r", "\n"], '', $stock->item_name)) }}",
                description: "{{ addslashes(str_replace(["\r", "\n"], '', $stock->description ?: '')) }}",
                price: {{ $stock->price }},
                original_price: {{ $stock->original_price ?? $stock->price }},
                discount_percentage: {{ $stock->discount_percentage ?? 0 }},
                special_discount_percentage: {{ $stock->special_discount_percentage ?? 0 }},
                available: {{ $stock->quantity }},
                quantity: 0
            },
        @endforeach
    @endforeach
};

// Get cart from localStorage
function getCart() {
    return JSON.parse(localStorage.getItem('cartItems') || '[]');
}

// Sync UI with cart for current page
function syncPageWithCart() {
    const cart = getCart();
    Object.keys(products).forEach(function(productId) {
        const cartItem = cart.find(item => item.product_id == productId);
        products[productId].quantity = cartItem ? cartItem.quantity : 0;
        
        // Only update UI elements if they exist (for currently visible products)
        var desktopQuantityEl = document.getElementById(`quantity-${productId}`);
        var mobileQuantityEl = document.getElementById(`mobile-quantity-${productId}`);
        var desktopTotalEl = document.getElementById(`total-${productId}`);
        var mobileTotalEl = document.getElementById(`mobile-total-${productId}`);
        
        if (desktopQuantityEl) {
            if (desktopQuantityEl.tagName === 'INPUT') {
                desktopQuantityEl.value = products[productId].quantity;
            } else {
                desktopQuantityEl.textContent = products[productId].quantity;
            }
        }
        if (mobileQuantityEl) {
            if (mobileQuantityEl.tagName === 'INPUT') {
                mobileQuantityEl.value = products[productId].quantity;
            } else {
                mobileQuantityEl.textContent = products[productId].quantity;
            }
        }
        var totalAmount = (products[productId].quantity * products[productId].price).toFixed(2);
        if (desktopTotalEl) desktopTotalEl.textContent = `₹${totalAmount}`;
        if (mobileTotalEl) mobileTotalEl.textContent = `₹${totalAmount}`;
    });
}

// On page load, sync UI with cart
document.addEventListener('DOMContentLoaded', function() {
    syncPageWithCart();
    updateCartTotal();
    updateCheckoutButton();
});

// Set manual quantity from input entry
function setManualQuantity(productId, value, isMobile) {
    let quantity = parseInt(value) || 0;
    quantity = Math.max(0, quantity);
    
    const product = products[productId];
    if (!product) {
        console.error('Product not found:', productId);
        return;
    }
    
    if (quantity !== product.quantity) {
        product.quantity = quantity;
        saveCartToLocalStorage();
        syncPageWithCart();
        updateCartTotal();
        updateCheckoutButton();
    }
}

// Update quantity for a product
function updateQuantity(productId, change) {
    console.log('updateQuantity called:', productId, change); // Debug log
    
    const product = products[productId];
    if (!product) {
        console.error('Product not found:', productId);
        return;
    }
    
    const newQuantity = Math.max(0, product.quantity + change); // Removed upper limit
    
    if (newQuantity !== product.quantity) {
        product.quantity = newQuantity;
        saveCartToLocalStorage();
        syncPageWithCart();
        updateCartTotal();
        updateCheckoutButton();
    }
}

function saveCartToLocalStorage() {
    const cartItems = Object.values(products)
        .filter(product => product.quantity > 0)
        .map(product => ({
            product_id: product.id,
            product_name: product.name,
            content: product.content || '',
            rate: product.price,
            original_price: product.original_price,
            discount_percentage: product.discount_percentage,
            special_discount_percentage: product.special_discount_percentage,
            quantity: product.quantity,
            total: product.price * product.quantity
        }));
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
}

// Remove item from cart
function removeItem(productId) {
    updateQuantity(productId, -products[productId].quantity);
    syncPageWithCart();
}

// Update cart total
function updateCartTotal() {
    const selectedProducts = Object.values(products).filter(product => product.quantity > 0);
    const total = selectedProducts.reduce((sum, product) => {
        return sum + (product.quantity * product.price);
    }, 0);
    
    const totalStr = total.toFixed(2);
    document.getElementById('cart-total').textContent = totalStr;
    
    renderCartSummary();
}

// Proceed to checkout
function proceedToCheckout() {
    const selectedProducts = Object.values(products).filter(product => product.quantity > 0);
    
    //fallback if no products selected
    if (selectedProducts.length === 0) {
        alert('Please select at least one product to proceed.');
        return;
    }
    
    // Create URL with selected items
    const items = selectedProducts.map(product => `${product.id}:${product.quantity}`).join(',');
    
    // Calculate total with original prices (not discounted)
    const total = Object.values(products).reduce((sum, product) => {
        return sum + (product.quantity * product.original_price);
    }, 0);
    
    window.location.href = `{{ route('smart-checkout.show') }}?items=${items}&total=${total.toFixed(2)}`;
}

// btn disabled if no products selected
function updateCheckoutButton() {
    const selectedProducts = Object.values(products).filter(product => product.quantity > 0);
    const hasItems = selectedProducts.length > 0;
    
    document.getElementById('checkout-btn').disabled = !hasItems;
    document.getElementById('estimate-pdf-btn').disabled = !hasItems;
    
    // Show/hide floating checkout drawer wrapper
    const wrapper = document.getElementById('cart-summary-wrapper');
    if (wrapper) {
        if (hasItems) {
            wrapper.style.display = 'block';
            renderCartSummary();
        } else {
            wrapper.style.display = 'none';
        }
    }
}

function renderCartSummary() {
    const selectedProducts = Object.values(products).filter(product => product.quantity > 0);
    const itemsCount = selectedProducts.reduce((sum, product) => sum + product.quantity, 0);
    
    const subtotal = selectedProducts.reduce((sum, product) => {
        return sum + (product.quantity * product.price);
    }, 0);
    
    const packingCharge = subtotal * 0.05;
    const finalTotal = subtotal + packingCharge;
    
    const badgeCount = document.getElementById('cart-badge-count');
    const itemsCountEl = document.getElementById('cart-items-count');
    const subtotalEl = document.getElementById('summary-subtotal');
    const packingEl = document.getElementById('summary-packing');
    const totalEl = document.getElementById('summary-total');
    const listContainer = document.getElementById('cart-items-list');

    if (badgeCount) badgeCount.textContent = itemsCount;
    if (itemsCountEl) itemsCountEl.textContent = itemsCount;
    if (subtotalEl) subtotalEl.textContent = `₹${subtotal.toFixed(2)}`;
    if (packingEl) packingEl.textContent = `₹${packingCharge.toFixed(2)}`;
    if (totalEl) totalEl.textContent = `₹${finalTotal.toFixed(2)}`;

    if (listContainer) {
        let html = '';
        selectedProducts.forEach(product => {
            const lineTotal = product.quantity * product.price;
            html += `
                <div class="flex items-center justify-between py-2 text-xs sm:text-sm">
                    <div class="flex-1 pr-2 text-left">
                        <span class="font-semibold text-gray-900 block text-left">${product.name}</span>
                        <span class="text-gray-500">${product.quantity} pcs × ₹${product.price.toFixed(2)}</span>
                    </div>
                    <div class="text-right font-bold text-gray-900 flex-shrink-0">
                        ₹${lineTotal.toFixed(2)}
                    </div>
                </div>
            `;
        });
        listContainer.innerHTML = html;
    }
}

function toggleCartDrawer() {
    const panel = document.getElementById('cart-summary-panel');
    const badge = document.getElementById('cart-badge-trigger');
    if (panel && badge) {
        if (panel.classList.contains('hidden')) {
            panel.classList.remove('hidden');
            badge.classList.add('hidden');
        } else {
            panel.classList.add('hidden');
            badge.classList.remove('hidden');
        }
    }
}

function clearCart() {
    Object.keys(products).forEach(productId => {
        products[productId].quantity = 0;
    });
    saveCartToLocalStorage();
    syncPageWithCart();
    updateCartTotal();
    updateCheckoutButton();
}

// Update generateEstimate to include coupon
function generateEstimate() {
    // Map selected products to the correct structure for PDF
    const selectedProducts = Object.values(products)
        .filter(product => product.quantity > 0)
        .map(product => ({
            product_id: product.id,
            product_name: product.name,
            description: product.description || '',
            content: product.content || '',
            rate: product.price,
            original_price: product.original_price,
            discount_percentage: product.discount_percentage,
            special_discount_percentage: product.special_discount_percentage,
            quantity: product.quantity,
            total: product.original_price * product.quantity
        }));
    if (selectedProducts.length === 0) {
        alert('Please select at least one product.');
        return;
    }

    // Disable buttons temporarily to prevent double click
    const btn = document.getElementById('estimate-pdf-btn');
    const summaryBtn = document.getElementById('summary-estimate-btn');
    if (btn) btn.disabled = true;
    if (summaryBtn) summaryBtn.disabled = true;

    // Create a form dynamically
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('express-shop.estimate-pdf') }}";
    form.target = '_blank';

    // CSRF Token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = "{{ csrf_token() }}";
    form.appendChild(csrfInput);

    // Items JSON
    const itemsInput = document.createElement('input');
    itemsInput.type = 'hidden';
    itemsInput.name = 'items';
    itemsInput.value = JSON.stringify(selectedProducts);
    form.appendChild(itemsInput);

    // Customer JSON (empty or default info)
    const customerInput = document.createElement('input');
    customerInput.type = 'hidden';
    customerInput.name = 'customer';
    customerInput.value = JSON.stringify({
        name: '',
        mobile: '',
        email: '',
        city: '',
        state: '',
        pin_code: ''
    });
    form.appendChild(customerInput);

    document.body.appendChild(form);
    form.submit();
    
    // Clean up
    setTimeout(() => {
        document.body.removeChild(form);
        if (btn) btn.disabled = false;
        if (summaryBtn) summaryBtn.disabled = false;
    }, 1000);
}

// Initialize cart total
updateCartTotal();

// ...existing code...

// Add event listeners for coupon input
document.addEventListener('DOMContentLoaded', function() {
    const couponInput = document.getElementById('coupon-code');
    if (couponInput) {
        couponInput.addEventListener('input', updateCouponButton);
        couponInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyCoupon();
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('categoryDropdownToggle');
    const menu = document.getElementById('categoryDropdownMenu');
    const icon = document.getElementById('categoryDropdownIcon');
    if (toggle && menu) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.classList.toggle('hidden');
            icon.style.transform = menu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
        document.addEventListener('click', function(e) {
            if (!menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        });
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
</script>
@endsection