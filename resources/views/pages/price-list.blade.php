@extends('layouts.app')

@section('title', 'Price List 2025 - Radhe Crackers')

@section('content')
<div class="py-8 bg-gradient-to-br from-gray-50 to-yellow-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Price List 2025</h1>
            <p class="text-lg text-gray-600 mb-6">Quality fireworks at amazing prices</p>
            
            <div class="mt-4 max-w-4xl mx-auto bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h3 class="text-gray-900 font-bold mb-4 text-center text-lg flex items-center justify-center gap-2">
                    📥 Download Options
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Option 1: With Images -->
                    <a href="{{ route('price-list.download') }}" class="flex flex-col items-center justify-between p-5 bg-gradient-to-br from-orange-50 to-orange-100 hover:from-orange-100 hover:to-orange-200 border border-orange-200 rounded-2xl text-center transition-all duration-300 transform hover:-translate-y-1 shadow-sm hover:shadow-md group">
                        <div class="flex flex-col items-center">
                            <span class="text-4xl mb-3 block transform group-hover:scale-110 transition-transform">🖼️</span>
                            <span class="font-bold text-orange-950 text-base block">With Images & Prices</span>
                            <span class="text-xs text-orange-800 mt-2 block leading-relaxed">Full catalog showing product pictures, MRP, and discounted prices.</span>
                        </div>
                        <span class="mt-4 w-full py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl text-xs shadow transition-colors block">
                            Download PDF
                        </span>
                    </a>
                    
                    <!-- Option 2: Without Images -->
                    <a href="{{ route('price-list.download', ['images' => 0]) }}" class="flex flex-col items-center justify-between p-5 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 border border-blue-200 rounded-2xl text-center transition-all duration-300 transform hover:-translate-y-1 shadow-sm hover:shadow-md group">
                        <div class="flex flex-col items-center">
                            <span class="text-4xl mb-3 block transform group-hover:scale-110 transition-transform">📄</span>
                            <span class="font-bold text-blue-950 text-base block">Without Images (Prices Only)</span>
                            <span class="text-xs text-blue-800 mt-2 block leading-relaxed">Compact price list with MRP and discounted prices. Ink-friendly.</span>
                        </div>
                        <span class="mt-4 w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow transition-colors block">
                            Download PDF
                        </span>
                    </a>

                    <!-- Option 3: Without Images & Prices -->
                    <a href="{{ route('price-list.download', ['images' => 0, 'prices' => 0]) }}" class="flex flex-col items-center justify-between p-5 bg-gradient-to-br from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 border border-gray-200 rounded-2xl text-center transition-all duration-300 transform hover:-translate-y-1 shadow-sm hover:shadow-md group">
                        <div class="flex flex-col items-center">
                            <span class="text-4xl mb-3 block transform group-hover:scale-110 transition-transform">✍️</span>
                            <span class="font-bold text-gray-950 text-base block">Blank Order Sheet</span>
                            <span class="text-xs text-gray-800 mt-2 block leading-relaxed">Product list without any prices or images. Ideal for manual orders.</span>
                        </div>
                        <span class="mt-4 w-full py-2 bg-gray-700 hover:bg-gray-800 text-white font-bold rounded-xl text-xs shadow transition-colors block">
                            Download PDF
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="px-6 py-4 "style="background-color: #1E093B;">
                    <h2 class="text-white text-xl font-bold text-center">Product Catalog</h2>
                </div>
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                            <th class="px-2 py-5 text-center font-bold text-gray-700 text-lg">🖼️ Image</th>
                            <th class="px-2 py-5 text-center font-bold text-gray-700 text-lg">📋 Product Details</th>
                            <th class="px-2 py-5 text-center font-bold text-gray-700 text-lg">💰 Price & Offers</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $groupedStocks = $stocks->groupBy('category');
                            $categories = \App\Models\Category::orderBy('sort_order')->get();
                        @endphp

                        @foreach($categories as $category)
                            @if(isset($groupedStocks[$category->name]))
                                <tr class="bg-gray-50">
                                    <td colspan="3" class="px-6 py-3">
                                        <h3 class="text-lg font-semibold text-orange-600">
                                            <span class="text-gray-500">{{ $category->sort_order }}.</span> {{ $category->name }}
                                        </h3>
                                    </td>
                                </tr>
                                @foreach($groupedStocks[$category->name]->sortBy('order_within_category') as $product)
                                    <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 transition-all duration-300 group">
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
                                                @if($product->discount_percentage || $product->special_discount_percentage)
                                                    @if($product->discount_percentage)
                                                        <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg animate-pulse">
                                                            {{ $product->discount_percentage }}%
                                                        </div>
                                                    @endif
                                                    @if($product->special_discount_percentage)
                                                        <div class="absolute -top-2 right-14 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                                            +{{ $product->special_discount_percentage }}%
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 text-center">
                                            <div class="space-y-3">
                                                <div class="font-bold text-gray-900 text-xl group-hover:text-orange-600 transition-colors duration-300">
                                                    {!! nl2br(e($product->item_name)) !!}
                                                </div>
                                                @if($product->description)
                                                    <div class="text-gray-600 leading-relaxed text-sm bg-gray-50 p-3 rounded-lg border-l-4 border-orange-300">
                                                        {!! nl2br(e($product->description)) !!}
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="bg-gradient-to-r from-orange-100 to-yellow-100 text-orange-800 text-sm font-medium px-4 py-2 rounded-full border border-orange-200">
                                                        🏷️ {{ $product->category }}
                                                    </span>
                                                </div>
                                                <div class="text-green-600 font-semibold">
                                                    📦 Available: {{ $product->quantity }} units
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 text-center">
                                            <div class="space-y-3">
                                                @if($product->discount_percentage || $product->special_discount_percentage)
                                                    <div class="flex gap-2 justify-center">
                                                        @if($product->discount_percentage)
                                                            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-bold px-4 py-2 rounded-full inline-block shadow-lg transform hover:scale-105 transition-transform duration-300">
                                                                🎉 {{ $product->discount_percentage }}% OFF
                                                            </div>
                                                        @endif
                                                        @if($product->special_discount_percentage)
                                                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-bold px-4 py-2 rounded-full inline-block shadow-lg transform hover:scale-105 transition-transform duration-300">
                                                                🎉 +{{ $product->special_discount_percentage }}% Extra OFF
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="space-y-2">
                                                    @if($product->original_price && $product->original_price > $product->price)
                                                        <div class="text-gray-500 text-sm line-through">
                                                            ₹{{ number_format($product->original_price, 2) }}
                                                        </div>
                                                    @endif
                                                    <div class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                                        ₹{{ number_format($product->price, 2) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">Per Unit</div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ✅ Mobile View Fix -->
        <div class="md:hidden mt-8">
            <div class="space-y-6">
                @foreach($stocks as $product)
                    <div class="bg-white rounded-2xl shadow-lg p-4 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                            <!-- Image -->
                            <div class="relative w-full sm:w-40 flex-shrink-0">
                                @if($product->image)
                                    <img loading="lazy" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->item_name }}" class="w-full h-auto sm:h-40 object-cover rounded-xl border-2 border-gray-200 shadow-md">
                                @else
                                    <div class="w-full sm:w-40 h-40 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl border-2 border-gray-200 shadow-md flex items-center justify-center text-5xl">
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
                                @if($product->discount_percentage || $product->special_discount_percentage)
                                    @if($product->discount_percentage)
                                        <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                            -{{ $product->discount_percentage }}%
                                        </div>
                                    @endif
                                    @if($product->special_discount_percentage)
                                        <div class="absolute -top-2 right-14 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                            -{{ $product->special_discount_percentage }}%
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-1">
                                <div class="font-bold text-gray-900 text-base mb-2">{!! nl2br(e($product->item_name)) !!}</div>
                                @if($product->description)
                                    <div class="text-gray-600 text-sm mb-2 leading-relaxed bg-gray-50 p-2 rounded-lg text-xs">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                @endif

                                <span class="inline-block bg-gradient-to-r from-orange-100 to-yellow-100 text-orange-800 text-xs font-medium px-3 py-1 rounded-full border border-orange-200 mb-2">
                                    🏷️ {{ $product->category }}
                                </span>

                                <div class="text-green-600 text-xs font-semibold mb-3">
                                    📦 Available: {{ $product->quantity }} units
                                </div>

                                @if($product->discount_percentage)
                                    <div class="flex gap-2 flex-wrap">
                                        <div class="bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-2 shadow-md inline-block">
                                            🎉 -{{ $product->discount_percentage }}% OFF
                                        </div>
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-2 shadow-md inline-block">
                                            🎉 -15% Extra OFF
                                        </div>
                                    </div>
                                @endif

                                <div class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                    ₹{{ number_format($product->price, 2) }}
                                </div>
                                @if($product->original_price && $product->original_price > $product->price)
                                    <div class="text-gray-500 text-xs line-through">
                                        ₹{{ number_format($product->original_price, 2) }}
                                    </div>
                                @endif
                                <div class="text-xs text-gray-500">Per Unit</div>
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

        <!-- Footer -->
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
