@extends('layouts.app')

@section('title', 'Sale Products - Radhe Crackers')

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
                            <span class="ml-1 text-gray-500 md:ml-2">Sale Products</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Sale Products</h1>
                <p class="text-lg text-gray-600">Amazing discounts on quality fireworks</p>
            </div>

            <!-- Sale Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($saleProducts as $product)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                        @if($product->discount_percentage)
                            <div class="bg-red-500 text-white text-xs px-2 py-1 rounded mb-2 inline-block">
                                -{{ $product->discount_percentage }}% OFF
                            </div>
                        @endif
                        
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
                            <p class="text-sm text-gray-600 mb-3 text-center">{{ $product->description }}</p>
                        @endif
                        
                        <div class="text-center mb-3">
                            @if($product->original_price && $product->original_price > $product->price)
                                <div class="text-sm text-gray-500 mb-1">
                                    <span class="line-through">₹{{ number_format($product->original_price, 2) }}</span>
                                </div>
                            @endif
                            <div class="text-orange-600 font-bold text-xl">₹{{ number_format($product->price, 2) }}</div>
                            <div class="text-sm text-gray-500">Available: {{ $product->quantity }} units</div>
                        </div>
                        
                        <a href="{{ route('order.form') }}" class=" text-white px-4 py-2 rounded hover:bg-gray-600 transition-colors inline-block w-full text-center"style="background-color: #1E093B;">
                            Add to cart
                        </a>
                    </div>
                @endforeach
            </div>

            @if($saleProducts->isEmpty())
                <div class="text-center py-12">
                    <div class="text-4xl mb-4">🎆</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Sale Products Available</h3>
                    <p class="text-gray-600">Check back later for amazing deals!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 