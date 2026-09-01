@extends('layouts.app')

@section('title', 'Estimate - Radhe Crackers')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-orange-500">Home</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500 md:ml-2">Estimate</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Mobile Category Toggle -->
        <div class="lg:hidden mb-4">
            <button id="categoryToggle" class="w-full bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-between shadow-sm">
                <span class="font-semibold text-gray-900">Shop By Categories</span>
                <svg id="categoryToggleIcon" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <div id="categorySidebar" class="bg-white rounded-lg shadow-md p-6 lg:sticky lg:top-4 hidden lg:block">
                    <!-- Shop By Categories -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Shop By Categories</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('shop') }}" class="text-gray-700 hover:text-yellow-600">All Products</a></li>
                            @foreach($categories as $categoryId => $data)
                                <li>
                                    <a href="?category={{ $data['name'] }}" 
                                       class="flex items-center justify-between text-gray-700 hover:text-yellow-600 {{ request('category') == $data['name'] ? 'text-orange-500 font-medium' : '' }}">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-lg">{{ $data['icon'] }}</span>
                                            <span>{{ $data['name'] }}</span>
                                        </div>
                                        <span class="text-sm text-gray-500">({{ $data['count'] }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Highlight -->
                    <!--<div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Highlight</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-700 hover:text-orange-500">All Products</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-orange-500">Best Seller</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-orange-500">New Arrivals</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-orange-500">Sale</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-orange-500">Hot Items</a></li>
                        </ul>
                    </div> -->

                    <!-- Price Filter -->
                    <!-- <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Price Filter</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="price" value="all" class="mr-2">
                                <span class="text-sm">All</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price" value="0-90" class="mr-2">
                                <span class="text-sm">₹0 – ₹90</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price" value="180-270" class="mr-2">
                                <span class="text-sm">₹180 – ₹270</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price" value="360-450" class="mr-2">
                                <span class="text-sm">₹360 – ₹450</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price" value="450+" class="mr-2">
                                <span class="text-sm">₹450+</span>
                            </label>
                        </div>
                    </div> -->

                    <!-- Average Rating -->
                    <!--<div>
                        <h3 class="text-lg font-semibold mb-4">Average Rating</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="flex text-yellow-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                                <span class="ml-2 text-sm text-gray-600">(0)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="flex text-yellow-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                                <span class="ml-2 text-sm text-gray-600">(0)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="flex text-yellow-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                                <span class="ml-2 text-sm text-gray-600">(0)</span>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex flex-col sm:flex-row justify-center items-center sm:items-center mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 text-center">Shop</h1>
                        <!--<div class="flex items-center space-x-4 mt-4 sm:mt-0">-->
                        <!--    <span class="text-sm text-gray-600">Showing all {{ $products->total() }} results</span>-->
                        <!--    <form method="GET" action="{{ url()->current() }}" class="inline-block">-->
                        <!--        <input type="hidden" name="search" value="{{ request('search') }}">-->
                        <!--        <input type="hidden" name="category" value="{{ request('category') }}">-->
                        <!--        <input type="hidden" name="price" value="{{ request('price') }}">-->
                        <!--        <select name="sort" class="border border-gray-300 rounded px-3 py-1 text-sm" onchange="this.form.submit()">-->
                        <!--            <option value="default" {{ request('sort', 'default') == 'default' ? 'selected' : '' }}>Default sorting</option>-->
                        <!--            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Sort by price: low to high</option>-->
                        <!--            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Sort by price: high to low</option>-->
                        <!--            <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Sort by popularity</option>-->
                        <!--        </select>-->
                        <!--    </form>-->
                        <!--</div>-->
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="border border-gray-200 rounded-lg p-2 sm:p-4 hover:shadow-lg transition-shadow text-sm sm:text-base" style="border-radius:12px;">
                            
                            <div class="flex flex-col sm:flex-row items-center justify-between w-full gap-1 sm:gap-2">
                                    @if($product->discount_percentage)
                                            <div class="bg-red-500 text-white text-xs px-2 py-1 rounded mb-1 sm:mb-0 inline-block">
                                            {{ $product->discount_percentage }}% OFF
                                        </div>
                                    @endif
                                    @if($product->special_discount_percentage)
                                        <div class="bg-red-500 text-white text-xs px-2 py-1 rounded inline-block">
                                            +{{ $product->special_discount_percentage }}% Special
                                        </div>
                                    @endif
                                    <!-- youtube url starts here -->
                                    @if($product->youtube_url)
                                    <div class="text-center">
                                        <button onclick="openVideoModal('{{ $product->youtube_url }}', '{{ $product->item_name }}')" 
                                                class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center justify-center mx-auto">
                                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    @else
                                    <div class="text-center">
                                    <button class="text-gray-400 cursor-not-allowed flex items-center justify-center mx-auto group relative" 
                title="No video available for this product">
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
            </svg>
            <!-- Tooltip -->
            <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-10">
                No Video Available
            </span>
        </button>
    </div>
@endif
<!-- youtube url ends here -->  

                            </div>
                                <div class="text-center mb-4">
                                    @if($product->image)
                                        <img src="{{ $product->image_url }}" 
                                             alt="{{ $product->item_name }}" 
                                             class="w-24 h-24 object-cover rounded-lg mx-auto mb-2 cursor-pointer hover:scale-105 transition-transform shadow-sm"
                                             onclick="openProductModal({{ $product->id }})"
                                             title="Click to view details">
                                    @else
                                        <div class="text-4xl mb-2 cursor-pointer hover:scale-110 transition-transform inline-block" 
                                             onclick="openProductModal({{ $product->id }})"
                                             title="Click to view details">
                                            @switch($product->category)
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
                                        </div>
                                    @endif
                                </div>

                                <h3 class="font-semibold text-gray-900 mb-1 text-center cursor-pointer hover:text-orange-600 transition-colors"
                                    onclick="openProductModal({{ $product->id }})"
                                    title="Click to view details">
                                    {{ $product->item_name }}
                                </h3>
                                @if($product->description)
                                    <p class="text-xs text-gray-500 mb-2 text-center cursor-pointer hover:text-gray-700" 
                                       onclick="openProductModal({{ $product->id }})">
                                        {{ $product->description }}
                                    </p>
                                @endif
                                
                                <div class="text-center mb-2">
                                    <button type="button" onclick="openProductModal({{ $product->id }})" class="inline-flex items-center gap-1 text-xs text-orange-600 hover:text-orange-800 font-medium transition-colors mb-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        View Details
                                    </button>
                                </div>
                                
                                <div class="text-center mb-3">
                                    @if($product->original_price && $product->original_price > $product->price)
                                        <span class="line-through text-gray-400 mr-2">₹{{ number_format($product->original_price, 2) }}</span>
                                    @endif
                                    <span class="text-orange-600 font-bold text-lg">₹{{ number_format($product->price, 2) }}</span>
                                    

                                    
                                    @if($product->show_on_shop)
                                        <div class="text-sm text-green-600 font-semibold">Available</div>
                                    @else
                                        
                                    @endif
                                </div>
                                
                                @if($product->show_on_shop)
                                    <div class="flex items-center justify-center rounded text-white p-2"style="background-color: #1E093B;">
                                        <button type="button"
                                                onclick="updateQuantity({{ $product->id }}, -1)"
                                                class="w-8 h-8 text-white rounded-full flex items-center justify-center hover:bg-black-300" style="background-color:rgb(182, 113, 33);">
                                            -
                                        </button>
                                        <input type="number" 
                                               id="quantity-{{ $product->id }}" 
                                               value="0" 
                                               min="0" 
                                               onchange="setManualQuantity({{ $product->id }}, this.value)" 
                                               class="w-12 text-center bg-white/10 border border-white/20 rounded focus:outline-none focus:ring-1 focus:ring-yellow-400 text-sm font-semibold text-white p-1 mx-1" 
                                               style="-moz-appearance: textfield; appearance: textfield; font-size: 14px;">
                                        <button type="button"
                                                onclick="updateQuantity({{ $product->id }}, 1)"
                                                class="w-8 h-8 rounded-full text-white flex items-center justify-center hover:bg-black-300" style="background-color:rgb(182, 113, 33);">
                                            +
                                        </button>
                                        <button type="button"
                                                onclick="removeItem({{ $product->id }})"
                                                class="ml-2 text-black-500 hover:text-black-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="mt-2 inline-block bg-gray-300 text-gray-700 px-3 py-1 rounded text-xs font-semibold">Out of Stock</div>
                                        <div class="text-xs text-gray-500 mt-1">Product not available for purchase</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

               <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-8">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Cart Summary Modal / Floating Box -->
<div class="fixed bottom-4 right-4 z-50 max-w-sm w-[calc(100vw-32px)] sm:w-96 font-sans flex flex-col items-end gap-2 pointer-events-none" id="cart-summary-wrapper" style="display: none;">
    <!-- Floating Sticky Cart Pill Button (ALWAYS SMALL BY DEFAULT, like provided photo) -->
    <button onclick="toggleCartDrawer()" id="cart-badge-trigger" class="pointer-events-auto flex items-center gap-3 bg-gradient-to-r from-amber-500 via-orange-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-full px-4 sm:px-5 py-2.5 shadow-2xl hover:shadow-orange-500/50 transition-all duration-300 hover:scale-105 border-2 border-white cursor-pointer ml-auto" title="Click to view full cart items and checkout">
        <!-- Cart Icon -->
        <svg class="w-6 h-6 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
        </svg>

        <!-- Total Amount -->
        <span id="cart-badge-total" class="font-extrabold text-sm sm:text-base text-white tracking-wide">₹0.00</span>

        <!-- White Badge Circle for Item Count -->
        <span class="bg-white text-orange-600 font-extrabold text-xs sm:text-sm min-w-[28px] h-7 px-1.5 rounded-full flex items-center justify-center shadow-md" id="cart-badge-count">0</span>
    </button>

    <!-- Detailed Cart Drawer Panel (starts hidden by default) -->
    <div id="cart-summary-panel" class="hidden pointer-events-auto w-full bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all duration-300 flex flex-col max-h-[500px]">
        <!-- Panel Header -->
        <div class="px-4 py-3 text-white flex items-center justify-between" style="background-color: #1E093B;">
            <div class="flex items-center gap-2">
                <span class="text-xl">🛒</span>
                <span class="font-bold text-sm sm:text-base">Estimate Cart (<span id="cart-items-count">0</span>)</span>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="clearCart()" class="text-xs text-red-300 hover:text-red-200 transition-colors font-semibold uppercase tracking-wider">Clear</button>
                <button onclick="toggleCartDrawer()" class="text-gray-300 hover:text-white transition-colors" title="Minimize">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
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
            
            <button onclick="proceedToCheckout()" class="w-full mt-2 text-white py-3 rounded-xl text-sm sm:text-base font-bold shadow-lg transition-colors flex items-center justify-center gap-2 bg-[#B67121] hover:bg-orange-600">
                <span>Proceed to Checkout</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </div>
</div>


<!-- Video Modal -->
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
            
        
        </div>
    </div>
</div>




<script>
// Store product data
const products = {
    @foreach($products as $product)
        {{ $product->id }}: {
            id: {{ $product->id }},
            name: "{{ addslashes(str_replace(["\r", "\n"], '', $product->item_name)) }}",
            price: {{ $product->price }},
            original_price: {{ $product->original_price ?? $product->price }},
            discount_percentage: {{ $product->discount_percentage ?? 0 }},
            special_discount_percentage: {{ $product->special_discount_percentage ?? 0 }},
            available: {{ $product->show_on_shop ? 'true' : 'false' }},
            quantity: 0,
            showOnShop: {{ $product->show_on_shop ? 'true' : 'false' }},
            youtube_url: "{{ addslashes($product->youtube_url) }}"
        },
    @endforeach
};

// Get cart from localStorage (all items, all pages)
function getCart() {
    return JSON.parse(localStorage.getItem('cartItems') || '[]');
}

// Save cart to localStorage
function saveCart(cart) {
    localStorage.setItem('cartItems', JSON.stringify(cart));
}

// Update quantity for a product
function updateQuantity(productId, change) {
    let cart = getCart();
    let product = products[productId];
    if (!product || !product.showOnShop) {
        alert('This product is not available for purchase.');
        return;
    }
    let cartItem = cart.find(item => item.product_id === productId);
    let newQuantity = (cartItem ? cartItem.quantity : 0) + change;
    newQuantity = Math.max(0, newQuantity);

    if (cartItem) {
        cartItem.quantity = newQuantity;
        cartItem.total = product.price * newQuantity;
        cartItem.original_price = product.original_price;
        if (newQuantity === 0) {
            cart = cart.filter(item => item.product_id !== productId);
        }
    } else if (newQuantity > 0) {
        cart.push({
            product_id: productId,
            product_name: product.name,
            content: product.content || '',
            rate: product.price,
            original_price: product.original_price,
            quantity: newQuantity,
            total: product.price * newQuantity
        });
    }
    saveCart(cart);
    updateCartSummary();
    // Update UI for this page
    setQtyUI(productId, newQuantity);
}

// Remove item from cart
function removeItem(productId) {
    let cart = getCart();
    cart = cart.filter(item => item.product_id !== productId);
    saveCart(cart);
    updateCartSummary();
    // Update UI for this page
    setQtyUI(productId, 0);
}

// Update cart summary
function updateCartSummary() {
    const cart = getCart();
    
    // Total count of items
    const itemsCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    // Calculate subtotal
    const subtotal = cart.reduce((sum, item) => {
        const product = products[item.product_id];
        if (product) {
            let finalPrice = product.price;
            if (product.discount_percentage > 0) {
                finalPrice = product.original_price * (1 - product.discount_percentage / 100);
            }
            if (product.special_discount_percentage > 0) {
                finalPrice = finalPrice * (1 - product.special_discount_percentage / 100);
            }
            return sum + (item.quantity * finalPrice);
        }
        return sum + (item.quantity * (item.rate || item.price));
    }, 0);
    
    const packingCharge = subtotal * 0.05;
    const finalTotal = subtotal + packingCharge;

    const wrapper = document.getElementById('cart-summary-wrapper');
    const badgeCount = document.getElementById('cart-badge-count');
    const badgeTotal = document.getElementById('cart-badge-total');
    const itemsCountEl = document.getElementById('cart-items-count');
    const subtotalEl = document.getElementById('summary-subtotal');
    const packingEl = document.getElementById('summary-packing');
    const totalEl = document.getElementById('summary-total');
    const listContainer = document.getElementById('cart-items-list');

    if (itemsCount === 0) {
        if (wrapper) wrapper.style.display = 'none';
        return;
    }

    if (wrapper) wrapper.style.display = 'flex';
    if (badgeCount) badgeCount.textContent = itemsCount;
    if (badgeTotal) badgeTotal.textContent = `₹${finalTotal.toFixed(2)}`;
    if (itemsCountEl) itemsCountEl.textContent = itemsCount;
    if (subtotalEl) subtotalEl.textContent = `₹${subtotal.toFixed(2)}`;
    if (packingEl) packingEl.textContent = `₹${packingCharge.toFixed(2)}`;
    if (totalEl) totalEl.textContent = `₹${finalTotal.toFixed(2)}`;

    // Populate scrollable items list
    if (listContainer) {
        let html = '';
        cart.forEach(item => {
            const product = products[item.product_id];
            if (product) {
                let finalPrice = product.price;
                if (product.discount_percentage > 0) {
                    finalPrice = product.original_price * (1 - product.discount_percentage / 100);
                }
                if (product.special_discount_percentage > 0) {
                    finalPrice = finalPrice * (1 - product.special_discount_percentage / 100);
                }
                const lineTotal = item.quantity * finalPrice;
                html += `
                    <div class="flex items-center justify-between py-2 text-xs sm:text-sm">
                        <div class="flex-1 pr-2 text-left">
                            <span class="font-semibold text-gray-900 block text-left">${product.name}</span>
                            <span class="text-gray-500">${item.quantity} pcs × ₹${finalPrice.toFixed(2)}</span>
                        </div>
                        <div class="text-right font-bold text-gray-900 flex-shrink-0">
                            ₹${lineTotal.toFixed(2)}
                        </div>
                    </div>
                `;
            }
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

// Clear cart
function clearCart() {
    saveCart([]);
    // Reset UI for current page
    Object.keys(products).forEach(productId => {
        setQtyUI(productId, 0);
    });
    updateCartSummary();
}

// On page load, sync UI with cart for current page
function syncPageWithCart() {
    const cart = getCart();
    Object.keys(products).forEach(productId => {
        const cartItem = cart.find(item => item.product_id == productId);
        setQtyUI(productId, cartItem ? cartItem.quantity : 0);
    });
}

function setQtyUI(productId, quantity) {
    const el = document.getElementById(`quantity-${productId}`);
    if (el) {
        if (el.tagName === 'INPUT') {
            el.value = quantity;
        } else {
            el.textContent = quantity;
        }
    }
}

function setManualQuantity(productId, value) {
    let quantity = parseInt(value) || 0;
    quantity = Math.max(0, quantity);
    
    let cart = getCart();
    let product = products[productId];
    if (!product || !product.showOnShop) {
        alert('This product is not available for purchase.');
        return;
    }
    
    let cartItem = cart.find(item => item.product_id === productId);
    if (cartItem) {
        cartItem.quantity = quantity;
        cartItem.total = product.price * quantity;
        cartItem.original_price = product.original_price;
        if (quantity === 0) {
            cart = cart.filter(item => item.product_id !== productId);
        }
    } else if (quantity > 0) {
        cart.push({
            product_id: productId,
            product_name: product.name,
            content: product.content || '',
            rate: product.price,
            original_price: product.original_price,
            quantity: quantity,
            total: product.price * quantity
        });
    }
    
    saveCart(cart);
    updateCartSummary();
    setQtyUI(productId, quantity);
}

// Proceed to checkout
function proceedToCheckout() {
    const cart = getCart();
    if (cart.length === 0) {
        alert('Please select at least one available product to proceed.');
        return;
    }
    saveCart(cart);
    const items = cart.map(product => `${product.product_id}:${product.quantity}`).join(',');
    
    // Calculate total with original prices (not discounted)
    const total = cart.reduce((sum, item) => {
        const product = products[item.product_id];
        if (product) {
            // Use original price for order value
            return sum + (item.quantity * (product.original_price || product.price));
        }
        return sum + (item.quantity * (item.rate || item.price));
    }, 0);
    
    window.location.href = `{{ route('smart-checkout.show') }}?items=${items}&total=${total.toFixed(2)}`;
}

// Initialize
syncPageWithCart();
updateCartSummary();





// Video Modal Functions starts here
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
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeVideoModal() {
    // Hide modal
    document.getElementById('videoModal').classList.add('hidden');
    
    // Clear video source to stop playback
    document.getElementById('videoIframe').src = '';
    
    // Restore body scrolling
    document.body.style.overflow = 'auto';
}

function extractYouTubeVideoId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}

// Close modal when clicking outside
document.getElementById('videoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVideoModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeVideoModal();
    }
});

// Video Modal Functions starts here

// Mobile Category Toggle
document.addEventListener('DOMContentLoaded', function() {
    const categoryToggle = document.getElementById('categoryToggle');
    const categorySidebar = document.getElementById('categorySidebar');
    const categoryToggleIcon = document.getElementById('categoryToggleIcon');
    
    if (categoryToggle && categorySidebar) {
        categoryToggle.addEventListener('click', function() {
            const isHidden = categorySidebar.classList.contains('hidden');
            
            if (isHidden) {
                categorySidebar.classList.remove('hidden');
                categorySidebar.classList.add('block');
                categoryToggleIcon.style.transform = 'rotate(180deg)';
            } else {
                categorySidebar.classList.add('hidden');
                categorySidebar.classList.remove('block');
                categoryToggleIcon.style.transform = 'rotate(0deg)';
            }
        });
    }
});

</script>


<!-- Full Product Details & Meta Description Popup Modal -->
<div id="productDetailsModal" class="fixed inset-0 bg-black/75 backdrop-blur-sm overflow-y-auto h-full w-full z-50 hidden transition-opacity duration-300">
    <div class="relative top-4 sm:top-10 mx-auto p-4 sm:p-6 border w-11/12 max-w-3xl shadow-2xl rounded-2xl bg-white text-gray-900 mb-10">
        <!-- Close Button -->
        <button type="button" onclick="closeProductModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors z-20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start mt-2">
            <!-- Left: Product Media Gallery -->
            <div class="flex flex-col items-center">
                <div class="relative w-full rounded-2xl overflow-hidden bg-gray-50 border border-gray-200 h-64 sm:h-80 flex items-center justify-center shadow-inner">
                    <button id="modalCarouselPrev" type="button" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-orange-500 hover:text-white text-gray-800 shadow-md rounded-full w-9 h-9 flex items-center justify-center z-10 transition-all border border-gray-200" style="display:none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    <img id="modalProductImage" src="" alt="Product Image" class="object-contain max-h-full max-w-full p-2 transition-all duration-300" />
                    <div id="modalFallbackIcon" class="text-6xl hidden">🎆</div>

                    <button id="modalCarouselNext" type="button" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-orange-500 hover:text-white text-gray-800 shadow-md rounded-full w-9 h-9 flex items-center justify-center z-10 transition-all border border-gray-200" style="display:none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <!-- Carousel Indicators & Thumbnails -->
                <div id="modalCarouselIndicators" class="flex flex-wrap justify-center gap-2 mt-3"></div>
                <div id="modalGalleryThumbnails" class="flex flex-wrap justify-center gap-2 mt-2"></div>
            </div>

            <!-- Right: Product Information & Meta Description -->
            <div class="flex flex-col justify-between space-y-4">
                <div>
                    <!-- Badges -->
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span id="modalCategoryBadge" class="bg-amber-100 text-amber-900 font-semibold text-xs px-3 py-1 rounded-full uppercase tracking-wider"></span>
                        <span id="modalDiscountBadge" class="bg-red-500 text-white font-bold text-xs px-2.5 py-1 rounded-full hidden"></span>
                        <span id="modalSpecialBadge" class="bg-purple-600 text-white font-bold text-xs px-2.5 py-1 rounded-full hidden"></span>
                        <span id="modalStockStatus" class="font-semibold text-xs px-2.5 py-1 rounded-full bg-green-100 text-green-800"></span>
                    </div>

                    <!-- Product Name -->
                    <h2 id="modalProductName" class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight"></h2>
                    
                    <!-- Packaging Info (e.g. 1 Pkt/5 Pcs) -->
                    <p id="modalPackagingInfo" class="text-xs sm:text-sm font-medium text-gray-500 mt-1 flex items-center gap-1.5"></p>

                    <!-- Price Block -->
                    <div class="flex items-baseline gap-3 my-3 p-3 bg-orange-50/70 rounded-xl border border-orange-100">
                        <span id="modalProductPrice" class="text-2xl font-extrabold text-orange-600"></span>
                        <span id="modalOriginalPrice" class="text-sm text-gray-400 line-through hidden"></span>
                        <span id="modalSavingsBadge" class="text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded-md ml-auto hidden"></span>
                    </div>

                    <!-- Meta Description Section -->
                    <div id="modalMetaDescContainer" class="p-4 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200/80 shadow-sm">
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-900 mb-1.5">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Product Overview
                        </div>
                        <p id="modalMetaDescription" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>

                    <!-- Meta Keywords / Tags -->
                    <div id="modalKeywordsContainer" class="mt-3 hidden">
                        <span class="text-xs font-semibold text-gray-500 block mb-1">Keywords / Tags:</span>
                        <div id="modalKeywordsBadges" class="flex flex-wrap gap-1.5"></div>
                    </div>

                    <!-- YouTube Video Button -->
                    <div id="modalYoutubeContainer" class="mt-3 hidden">
                        <button id="modalYoutubeBtn" type="button" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg border border-red-200 transition-colors">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                            Watch Video Demonstration
                        </button>
                    </div>
                </div>

                <!-- Modal Quantity & Action Controls -->
                <div id="modalActionSection" class="pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center rounded-xl p-1.5 text-white shadow-sm" style="background-color: #1E093B;">
                            <button type="button" onclick="modalChangeQty(-1)" class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-lg hover:opacity-90 active:scale-95 transition-all" style="background-color: rgb(182, 113, 33);">-</button>
                            <input type="number" id="modalQtyInput" value="0" min="0" onchange="modalSetQty(this.value)" class="w-12 text-center bg-transparent border-none text-white font-bold text-sm focus:ring-0">
                            <button type="button" onclick="modalChangeQty(1)" class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-lg hover:opacity-90 active:scale-95 transition-all" style="background-color: rgb(182, 113, 33);">+</button>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500 block">Item Total</span>
                            <span id="modalItemSubtotal" class="text-lg font-bold text-gray-900">₹0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Products catalog data with SEO / Meta tags
const productsCatalog = {
    @foreach($products as $product)
    {{ $product->id }}: {
        id: {{ $product->id }},
        name: {!! json_encode($product->item_name) !!},
        category: {!! json_encode($product->category) !!},
        description: {!! json_encode($product->description ?? '') !!},
        meta_title: {!! json_encode($product->meta_title ?? '') !!},
        meta_description: {!! json_encode($product->meta_description ?? '') !!},
        meta_keywords: {!! json_encode($product->meta_keywords ?? '') !!},
        price: {{ (float)$product->price }},
        original_price: {{ (float)($product->original_price ?? 0) }},
        discount_percentage: {{ (int)($product->discount_percentage ?? 0) }},
        special_discount_percentage: {{ (int)($product->special_discount_percentage ?? 0) }},
        youtube_url: {!! json_encode($product->youtube_url ?? '') !!},
        show_on_shop: {{ $product->show_on_shop ? 'true' : 'false' }},
        image_url: {!! json_encode($product->image_url ?? '') !!},
        images: [
            @php $imgArr = []; @endphp
            @if($product->images->count())
                @foreach($product->images as $img)
                    @php
                        $imgPath = ltrim($img->image_path, '/');
                        $imgArr[] = asset($imgPath);
                    @endphp
                @endforeach
            @elseif($product->image)
                @php $imgArr[] = $product->image_url; @endphp
            @endif
            {!! collect($imgArr)->map(function($url){ return '"'.$url.'"'; })->implode(',') !!}
        ]
    },
    @endforeach
};

let currentModalProductId = null;
let currentModalImageIndex = 0;

function openProductModal(productId) {
    const product = productsCatalog[productId];
    if (!product) return;

    currentModalProductId = productId;
    currentModalImageIndex = 0;

    // Set Text Content
    document.getElementById('modalProductName').textContent = product.name;
    document.getElementById('modalCategoryBadge').textContent = product.category;
    document.getElementById('modalPackagingInfo').textContent = product.description ? '📦 ' + product.description : '';

    // Pricing
    document.getElementById('modalProductPrice').textContent = '₹' + product.price.toFixed(2);
    const originalPriceEl = document.getElementById('modalOriginalPrice');
    const savingsBadgeEl = document.getElementById('modalSavingsBadge');
    if (product.original_price > product.price) {
        originalPriceEl.textContent = '₹' + product.original_price.toFixed(2);
        originalPriceEl.classList.remove('hidden');
        const savings = product.original_price - product.price;
        savingsBadgeEl.textContent = 'Save ₹' + savings.toFixed(2);
        savingsBadgeEl.classList.remove('hidden');
    } else {
        originalPriceEl.classList.add('hidden');
        savingsBadgeEl.classList.add('hidden');
    }

    // Discounts
    const discountBadgeEl = document.getElementById('modalDiscountBadge');
    if (product.discount_percentage > 0) {
        discountBadgeEl.textContent = product.discount_percentage + '% OFF';
        discountBadgeEl.classList.remove('hidden');
    } else {
        discountBadgeEl.classList.add('hidden');
    }

    const specialBadgeEl = document.getElementById('modalSpecialBadge');
    if (product.special_discount_percentage > 0) {
        specialBadgeEl.textContent = '+' + product.special_discount_percentage + '% Special';
        specialBadgeEl.classList.remove('hidden');
    } else {
        specialBadgeEl.classList.add('hidden');
    }

    // Stock Status
    const stockStatusEl = document.getElementById('modalStockStatus');
    const actionSectionEl = document.getElementById('modalActionSection');
    if (product.show_on_shop) {
        stockStatusEl.textContent = 'Available';
        stockStatusEl.className = 'font-semibold text-xs px-2.5 py-1 rounded-full bg-green-100 text-green-800';
        actionSectionEl.classList.remove('hidden');
    } else {
        stockStatusEl.textContent = 'Out of Stock';
        stockStatusEl.className = 'font-semibold text-xs px-2.5 py-1 rounded-full bg-red-100 text-red-800';
        actionSectionEl.classList.add('hidden');
    }

    // Meta Description
    const metaDescEl = document.getElementById('modalMetaDescription');
    const metaContainerEl = document.getElementById('modalMetaDescContainer');
    const displayDesc = product.meta_description || product.description || ('Premium ' + product.name + ' (' + product.category + ') Sivakasi crackers available online at best wholesale rates.');
    metaDescEl.textContent = displayDesc;

    // Meta Keywords / Tags
    const keywordsContainerEl = document.getElementById('modalKeywordsContainer');
    const keywordsBadgesEl = document.getElementById('modalKeywordsBadges');
    keywordsBadgesEl.innerHTML = '';
    if (product.meta_keywords) {
        const keywords = product.meta_keywords.split(',').map(k => k.trim()).filter(Boolean);
        if (keywords.length > 0) {
            keywords.forEach(kw => {
                const badge = document.createElement('span');
                badge.className = 'text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-md border border-gray-200';
                badge.textContent = '#' + kw;
                keywordsBadgesEl.appendChild(badge);
            });
            keywordsContainerEl.classList.remove('hidden');
        } else {
            keywordsContainerEl.classList.add('hidden');
        }
    } else {
        keywordsContainerEl.classList.add('hidden');
    }

    // YouTube Video
    const youtubeContainer = document.getElementById('modalYoutubeContainer');
    const youtubeBtn = document.getElementById('modalYoutubeBtn');
    if (product.youtube_url) {
        youtubeContainer.classList.remove('hidden');
        youtubeBtn.onclick = function() {
            openVideoModal(product.youtube_url, product.name);
        };
    } else {
        youtubeContainer.classList.add('hidden');
    }

    // Setup Gallery / Carousel
    setupModalGallery(product);

    // Sync Quantity
    syncModalQuantity();

    // Show Modal
    document.getElementById('productDetailsModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function setupModalGallery(product) {
    const images = product.images && product.images.length > 0 ? product.images : (product.image_url ? [product.image_url] : []);
    const imgEl = document.getElementById('modalProductImage');
    const fallbackEl = document.getElementById('modalFallbackIcon');
    const prevBtn = document.getElementById('modalCarouselPrev');
    const nextBtn = document.getElementById('modalCarouselNext');
    const indicatorsEl = document.getElementById('modalCarouselIndicators');
    const thumbsEl = document.getElementById('modalGalleryThumbnails');

    indicatorsEl.innerHTML = '';
    thumbsEl.innerHTML = '';

    if (images.length > 0) {
        imgEl.src = images[0];
        imgEl.classList.remove('hidden');
        fallbackEl.classList.add('hidden');

        prevBtn.style.display = images.length > 1 ? 'flex' : 'none';
        nextBtn.style.display = images.length > 1 ? 'flex' : 'none';

        prevBtn.onclick = function(e) {
            e.stopPropagation();
            if (currentModalImageIndex > 0) {
                showModalImage(images, currentModalImageIndex - 1);
            }
        };

        nextBtn.onclick = function(e) {
            e.stopPropagation();
            if (currentModalImageIndex < images.length - 1) {
                showModalImage(images, currentModalImageIndex + 1);
            }
        };

        if (images.length > 1) {
            images.forEach((src, idx) => {
                const thumb = document.createElement('img');
                thumb.src = src;
                thumb.className = 'w-10 h-10 object-cover rounded-lg border-2 cursor-pointer transition-all ' + (idx === 0 ? 'border-orange-500 shadow-md scale-105' : 'border-gray-200 opacity-70 hover:opacity-100');
                thumb.onclick = () => showModalImage(images, idx);
                thumbsEl.appendChild(thumb);
            });
        }
    } else {
        imgEl.classList.add('hidden');
        fallbackEl.classList.remove('hidden');
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
    }
}

function showModalImage(images, idx) {
    currentModalImageIndex = idx;
    const imgEl = document.getElementById('modalProductImage');
    imgEl.src = images[idx];

    const thumbsEl = document.getElementById('modalGalleryThumbnails');
    Array.from(thumbsEl.children).forEach((thumb, i) => {
        if (i === idx) {
            thumb.className = 'w-10 h-10 object-cover rounded-lg border-2 cursor-pointer transition-all border-orange-500 shadow-md scale-105';
        } else {
            thumb.className = 'w-10 h-10 object-cover rounded-lg border-2 cursor-pointer transition-all border-gray-200 opacity-70 hover:opacity-100';
        }
    });
}

function syncModalQuantity() {
    if (!currentModalProductId) return;
    const cardInput = document.getElementById('quantity-' + currentModalProductId);
    const qty = cardInput ? parseInt(cardInput.value) || 0 : 0;
    document.getElementById('modalQtyInput').value = qty;
    
    const product = productsCatalog[currentModalProductId];
    if (product) {
        document.getElementById('modalItemSubtotal').textContent = '₹' + (qty * product.price).toFixed(2);
    }
}

function modalChangeQty(delta) {
    if (!currentModalProductId) return;
    updateQuantity(currentModalProductId, delta);
    syncModalQuantity();
}

function modalSetQty(val) {
    if (!currentModalProductId) return;
    setManualQuantity(currentModalProductId, val);
    syncModalQuantity();
}

function closeProductModal() {
    document.getElementById('productDetailsModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentModalProductId = null;
}

// Close on background click
document.getElementById('productDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProductModal();
    }
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProductModal();
    }
});
</script>
@endsection 