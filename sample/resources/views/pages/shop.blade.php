@extends('layouts.app')

@section('title', 'Shop - Radhe Crackers')

@section('content')
<div class="py-8">
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
                            <span class="ml-1 text-gray-500 md:ml-2">Shop</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <!-- Shop By Categories -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Shop By Categories</h3>
                        <ul class="space-y-2">
                            @foreach($categories as $category => $count)
                                <li>
                                    <a href="?category={{ urlencode($category) }}" 
                                       class="flex justify-between items-center text-gray-700 hover:text-orange-500 {{ request('category') == $category ? 'text-orange-500 font-medium' : '' }}">
                                        <span>{{ $category }}</span>
                                        <span class="text-sm text-gray-500">({{ $count }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Highlight -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Highlight</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-700 hover:text-orange-500">All Products</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-orange-500">Best Seller</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-orange-500">New Arrivals</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-orange-500">Sale</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-orange-500">Hot Items</a></li>
                        </ul>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-8">
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
                    </div>

                    <!-- Average Rating -->
                    <div>
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
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Shop</h1>
                        <div class="flex items-center space-x-4 mt-4 sm:mt-0">
                            <span class="text-sm text-gray-600">Showing all {{ $products->total() }} results</span>
                            <select class="border border-gray-300 rounded px-3 py-1 text-sm">
                                <option>Default sorting</option>
                                <option>Sort by price: low to high</option>
                                <option>Sort by price: high to low</option>
                                <option>Sort by popularity</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                            
                            <div class=" flex flex-row items-center justify-between w-full " >
                                    @if($product->discount_percentage)
                                            <div class="bg-red-500 text-white text-xs px-2 py-1 rounded mb-2 inline-block">
                                            -{{ $product->discount_percentage }}% OFF
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
                                             class="w-24 h-24 object-cover rounded-lg mx-auto mb-2">
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
                                        <span id="quantity-{{ $product->id }}" class="w-8 text-center">0</span>
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
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cart Summary -->
<div class="fixed z-40 bottom-4 right-4 bg-white rounded-lg shadow-lg p-4 border" id="cart-summary" style="display: none;">
    <div class="flex items-center justify-between mb-2">
        <span class="font-semibold">Cart Total: ₹<span id="cart-total">0.00</span></span>
        <button onclick="clearCart()" class="text-red-500 hover:text-red-700 text-sm">Clear</button>
    </div>
    <button onclick="proceedToCheckout()" class="w-full text-white px-4 py-2 rounded hover:bg-orange-600 transition-colors"  style="background-color:rgb(182, 113, 33);">
        Proceed to Checkout
    </button>
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
            name: "{{ $product->item_name }}",
            price: {{ $product->price }},
            available: {{ $product->show_on_shop ? 'true' : 'false' }},
            quantity: 0,
            showOnShop: {{ $product->show_on_shop ? 'true' : 'false' }}
        },
    @endforeach
};

// Update quantity for a product
function updateQuantity(productId, change) {
    const product = products[productId];
    
    // Prevent adding products that are not shown on shop
    if (!product.showOnShop) {
        alert('This product is not available for purchase.');
        return;
    }
    
    const newQuantity = Math.max(0, product.quantity + change);
    
    if (newQuantity !== product.quantity) {
        product.quantity = newQuantity;
        
        // Update display
        document.getElementById(`quantity-${productId}`).textContent = newQuantity;
        
        // Update cart summary
        updateCartSummary();
        // Save cart to localStorage
        saveCartToLocalStorage();
    }
}

// Remove item from cart
function removeItem(productId) {
    updateQuantity(productId, -products[productId].quantity);
}

// Save cart to localStorage
function saveCartToLocalStorage() {
    const cartItems = Object.values(products)
        .filter(product => product.quantity > 0 && product.showOnShop)
        .map(product => ({
            product_id: product.id,
            product_name: product.name,
            content: product.content || '',
            rate: product.price,
            quantity: product.quantity,
            total: product.price * product.quantity
        }));
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
}

// Update cart summary
function updateCartSummary() {
    const total = Object.values(products).reduce((sum, product) => {
        return sum + (product.quantity * product.price);
    }, 0);
    
    const cartSummary = document.getElementById('cart-summary');
    const cartTotal = document.getElementById('cart-total');
    
    cartTotal.textContent = total.toFixed(2);
    
    if (total > 0) {
        cartSummary.style.display = 'block';
    } else {
        cartSummary.style.display = 'none';
    }
    // Save cart to localStorage whenever summary is updated
    saveCartToLocalStorage();
}

// Clear cart
function clearCart() {
    Object.values(products).forEach(product => {
        product.quantity = 0;
        document.getElementById(`quantity-${product.id}`).textContent = '0';
    });
    updateCartSummary();
    saveCartToLocalStorage();
}

// Proceed to checkout
function proceedToCheckout() {
    const selectedProducts = Object.values(products).filter(product => product.quantity > 0 && product.showOnShop);
    
    if (selectedProducts.length === 0) {
        alert('Please select at least one available product to proceed.');
        return;
    }
    
    // Save cart to localStorage before proceeding
    saveCartToLocalStorage();
    // Create URL with selected items
    const items = selectedProducts.map(product => `${product.id}:${product.quantity}`).join(',');
    const total = Object.values(products).reduce((sum, product) => sum + (product.quantity * product.price), 0);
    window.location.href = `{{ route('checkout.form') }}?items=${items}&total=${total.toFixed(2)}`;
}

// Initialize cart summary
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



</script>
@endsection 