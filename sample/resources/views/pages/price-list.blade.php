@extends('layouts.app')

@section('title', 'Price List 2025 - Radhe Crackers')

@section('content')
<div class="py-8 bg-gradient-to-br from-orange-50 to-yellow-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Price List 2025</h1>
            <p class="text-lg text-gray-600 mb-6">Quality fireworks at amazing prices</p>
            <div class="flex justify-center">
                <a href="{{ route('price-list.download') }}" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-3 rounded-full hover:from-orange-600 hover:to-orange-700 font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    📄 Download PDF
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                    <h2 class="text-white text-xl font-bold text-center">Product Catalog</h2>
                </div>
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-orange-200">
                            <th class="px-6 py-5 text-center font-bold text-gray-700 text-lg w-96">
                                <div class="flex items-center justify-center space-x-2">
                                    <span>🖼️</span>
                                    <span>Image</span>
                                </div>
                            </th>
                            <th class="px-6 py-5 text-left font-bold text-gray-700 text-lg">
                                <div class="flex items-center space-x-2">
                                    <span>📋</span>
                                    <span>Product Details</span>
                                </div>
                            </th>
                            <th class="px-6 py-5 text-right font-bold text-gray-700 text-lg w-52">
                                <div class="flex items-center justify-end space-x-2">
                                    <span>💰</span>
                                    <span>Price & Offers</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stocks as $product)
                            <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-orange-50 hover:to-yellow-50 transition-all duration-300 group">
                                <td class="px-6 py-6 text-center">
                                    <div class="relative">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->item_name }}" class="w-48 h-48 object-cover rounded-xl border-2 border-gray-200 shadow-md group-hover:shadow-lg transition-shadow duration-300">
                                        @else
                                            <div class="w-48 h-48 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl border-2 border-gray-200 shadow-md flex items-center justify-center text-6xl group-hover:shadow-lg transition-shadow duration-300">
                                                @switch($product->category)
                                                    @case('BOMBS') 💣 @break
                                                    @case('SINGLE FLASH') ⚡ @break
                                                    @case('ROCKETS') 🚀 @break
                                                    @case('SPARKLERS') ✨ @break
                                                    @case('CHIT PUT') 🎆 @break
                                                    @case('TWINKLING STAR') ⭐ @break
                                                    @case('GIFT BOX') 🎁 @break
                                                    @case('BIJILI CRACKERS') ⚡ @break
                                                    @default 🎆
                                                @endswitch
                                            </div>
                                        @endif
                                        @if($product->discount_percentage)
                                            <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg animate-pulse">
                                                -{{ $product->discount_percentage }}%
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="space-y-3">
                                        <div class="font-bold text-gray-900 text-xl group-hover:text-orange-600 transition-colors duration-300">
                                            {!! nl2br(e($product->item_name)) !!}
                                        </div>
                                        @if($product->description)
                                            <div class="text-gray-600 leading-relaxed text-sm bg-gray-50 p-3 rounded-lg border-l-4 border-orange-300">
                                                {!! nl2br(e($product->description)) !!}
                                            </div>
                                        @endif
                                        @if($product->category)
                                            <div class="flex items-center space-x-2">
                                                <span class="bg-gradient-to-r from-orange-100 to-yellow-100 text-orange-800 text-sm font-medium px-4 py-2 rounded-full border border-orange-200">
                                                    🏷️ {{ $product->category }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="flex items-center space-x-2 text-green-600 font-semibold">
                                            <span class="text-lg">📦</span>
                                            <span>Available: {{ $product->quantity }} units</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <div class="space-y-3">
                                        @if($product->discount_percentage)
                                            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-bold px-4 py-2 rounded-full inline-block shadow-lg transform hover:scale-105 transition-transform duration-300">
                                                🎉 -{{ $product->discount_percentage }}% OFF
                                            </div>
                                        @endif
                                        <div class="space-y-2">
                                            @if($product->original_price && $product->original_price > $product->price)
                                                <div class="text-gray-500 text-sm">
                                                    <span class="line-through">₹{{ number_format($product->original_price, 2) }}</span>
                                                </div>
                                            @endif
                                            <div class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                                ₹{{ number_format($product->price, 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Per Unit
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden">
            <div class="space-y-4">
                @foreach($stocks as $product)
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-start space-x-4">
                            <!-- Image Column -->
                            <div class="flex-shrink-0 relative">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->item_name }}" class="w-40 h-40 object-cover rounded-xl border-2 border-gray-200 shadow-md">
                                @else
                                    <div class="w-40 h-40 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl border-2 border-gray-200 shadow-md flex items-center justify-center text-5xl">
                                        @switch($product->category)
                                            @case('BOMBS') 💣 @break
                                            @case('SINGLE FLASH') ⚡ @break
                                            @case('ROCKETS') 🚀 @break
                                            @case('SPARKLERS') ✨ @break
                                            @case('CHIT PUT') 🎆 @break
                                            @case('TWINKLING STAR') ⭐ @break
                                            @case('GIFT BOX') 🎁 @break
                                            @case('BIJILI CRACKERS') ⚡ @break
                                            @default 🎆
                                        @endswitch
                                    </div>
                                @endif
                                @if($product->discount_percentage)
                                    <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                        -{{ $product->discount_percentage }}%
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Details Column -->
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-gray-900 text-lg mb-2">{!! nl2br(e($product->item_name)) !!}</div>
                                @if($product->description)
                                    <div class="text-gray-600 text-sm mb-3 leading-relaxed bg-gray-50 p-2 rounded-lg text-xs">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                @endif
                                @if($product->category)
                                    <span class="inline-block bg-gradient-to-r from-orange-100 to-yellow-100 text-orange-800 text-xs font-medium px-3 py-1 rounded-full border border-orange-200 mb-2">
                                        🏷️ {{ $product->category }}
                                    </span>
                                @endif
                                <div class="text-green-600 text-xs font-semibold flex items-center">
                                    <span class="mr-1">📦</span>
                                    <span>Available: {{ $product->quantity }} units</span>
                                </div>
                            </div>
                            
                            <!-- Price Column -->
                            <div class="flex-shrink-0 text-right">
                                @if($product->discount_percentage)
                                    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-2 shadow-md">
                                        🎉 -{{ $product->discount_percentage }}% OFF
                                    </div>
                                @endif
                                <div class="space-y-1">
                                    @if($product->original_price && $product->original_price > $product->price)
                                        <div class="text-gray-500 text-xs">
                                            <span class="line-through">₹{{ number_format($product->original_price, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                        ₹{{ number_format($product->price, 2) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Per Unit
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($stocks->isEmpty())
            <div class="text-center py-16">
                <div class="text-6xl mb-6">🎆</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">No Products Available</h3>
                <p class="text-gray-600 text-lg">Check back later for amazing deals!</p>
            </div>
        @endif

        <!-- Footer Info -->
        <div class="mt-12 text-center">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-4">📞 Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-600">
                    <div class="flex items-center justify-center space-x-2">
                        <span class="text-2xl">📱</span>
                        <span>WhatsApp: +91 8807060809</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <span class="text-2xl">📱</span>
                        <span>WhatsApp: +91 9751048974</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 