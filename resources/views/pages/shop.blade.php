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
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->item_name }}" 
                                             class="w-24 h-24 object-cover rounded-lg mx-auto mb-2" onclick="openGalleryModal({{ $product->id }}, '{{ addslashes($product->item_name) }}')">
                                    @else
                                        <div class="text-4xl mb-2">
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
                                
                                <!-- Gallery Modal -->
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900" id="galleryModalTitle">Product Gallery</h3>
            <button onclick="closeGalleryModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <!-- Carousel Container -->
        <div id="carouselContainer" class="w-full flex flex-col items-center mb-6">
            <div class="relative w-full max-w-lg flex items-center justify-center">
                <button id="carouselPrev" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-blue-500 hover:text-white text-blue-700 shadow-lg rounded-full w-10 h-10 flex items-center justify-center z-10 transition-all duration-200 border border-blue-200" style="display:none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="overflow-hidden rounded-2xl shadow-xl border-2 border-blue-100 bg-gradient-to-br from-blue-50 to-white w-full h-72 flex items-center justify-center">
                    <img id="carouselImage" src="" alt="Gallery Image" class="object-contain w-full h-full transition-transform duration-300 ease-in-out" />
                </div>
                <button id="carouselNext" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-blue-500 hover:text-white text-blue-700 shadow-lg rounded-full w-10 h-10 flex items-center justify-center z-10 transition-all duration-200 border border-blue-200" style="display:none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <div id="carouselIndicators" class="flex justify-center mt-4 space-x-3">
                <!-- Dots will be rendered here by JS -->
            </div>
        </div>
        <div id="galleryImages" class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-2"></div>
    </div>
</div>


                                <h3 class="font-semibold text-gray-900 mb-2 text-center">{{ $product->item_name }}</h3>
                                @if($product->description)
                                    <p class="text-sm text-gray-600 mb-2 text-center">{{ $product->description }}</p>
                                @endif
                                
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
    const itemsCountEl = document.getElementById('cart-items-count');
    const subtotalEl = document.getElementById('summary-subtotal');
    const packingEl = document.getElementById('summary-packing');
    const totalEl = document.getElementById('summary-total');
    const listContainer = document.getElementById('cart-items-list');

    if (itemsCount === 0) {
        if (wrapper) wrapper.style.display = 'none';
        return;
    }

    if (wrapper) wrapper.style.display = 'block';
    if (badgeCount) badgeCount.textContent = itemsCount;
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
    const items = cart.map(product => `${product.product_id}:${product.quantity}`).join(',');
    
    // Calculate total with original prices (not discounted)
    const total = cart.reduce((sum, item) => {
        const product = products[item.product_id];
        if (product) {
            // Use original price for order value
            return sum + (item.quantity * product.original_price);
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


<script>
// Stock images data for gallery modal
const stockImages = {};

@foreach($products as $product)
    stockImages[{{ $product->id }}] = [
        @php $imgArr = []; @endphp
        @if($product->images->count())
            @foreach($product->images as $img)
                @php
                    $imgPath = ltrim($img->image_path, '/');
                    $imgArr[] = asset($imgPath);
                @endphp
            @endforeach
        @elseif($product->image)
            @php $imgArr[] = asset('storage/' . ltrim($product->image, '/')); @endphp
        @endif
        {!! collect($imgArr)->map(function($url){ return '"'.$url.'"'; })->implode(',') !!}
    ];
@endforeach

function openGalleryModal(productId, productName) {
    const images = stockImages[productId] || [];
    // Carousel logic
    const carouselContainer = document.getElementById('carouselContainer');
    const carouselImage = document.getElementById('carouselImage');
    const carouselPrev = document.getElementById('carouselPrev');
    const carouselNext = document.getElementById('carouselNext');
    const carouselIndicators = document.getElementById('carouselIndicators');
    let currentIndex = 0;
    if (images.length > 0) {
        carouselImage.src = images[0];
        carouselContainer.style.display = 'flex';
        carouselPrev.style.display = images.length > 1 ? 'block' : 'none';
        carouselNext.style.display = images.length > 1 ? 'block' : 'none';
        // Indicators
        carouselIndicators.innerHTML = '';
        images.forEach((img, idx) => {
            const dot = document.createElement('button');
            dot.className = 'w-3 h-3 rounded-full ' + (idx === 0 ? 'bg-blue-600' : 'bg-gray-300');
            dot.onclick = () => showCarouselImage(idx);
            carouselIndicators.appendChild(dot);
        });
    } else {
        carouselContainer.style.display = 'none';
    }
    function showCarouselImage(idx) {
        currentIndex = idx;
        carouselImage.src = images[idx];
        Array.from(carouselIndicators.children).forEach((dot, i) => {
            dot.className = 'w-3 h-3 rounded-full ' + (i === idx ? 'bg-blue-600' : 'bg-gray-300');
        });
    }
    carouselPrev.onclick = function() {
        if (currentIndex > 0) showCarouselImage(currentIndex - 1);
    };
    carouselNext.onclick = function() {
        if (currentIndex < images.length - 1) showCarouselImage(currentIndex + 1);
    };
    // Show first image
    showCarouselImage(0);
    // ...existing code for galleryImages grid...
    const galleryImagesDiv = document.getElementById('galleryImages');
    galleryImagesDiv.innerHTML = '';
    if (images.length === 0) {
        galleryImagesDiv.innerHTML = '<div class="col-span-2 text-center text-gray-500">No gallery images available.</div>';
    } else {
        images.forEach(src => {
            const img = document.createElement('img');
            img.src = src;
            img.className = 'w-full h-32 object-cover rounded-lg shadow';
            galleryImagesDiv.appendChild(img);
        });
    }
    document.getElementById('galleryModalTitle').textContent = productName + ' - Gallery';
    document.getElementById('galleryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeGalleryModal() {
    document.getElementById('galleryModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close gallery modal when clicking outside
document.getElementById('galleryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeGalleryModal();
    }
});

// Close gallery modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGalleryModal();
    }
});
</script>
@endsection 