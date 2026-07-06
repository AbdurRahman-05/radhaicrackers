@extends('layouts.app')

@section('title', 'Radhe Crackers – Bringing Joy, Spark by Spark')

@section('content')
<!-- Hero Banner Slider -->
<div class="relative bg-gray-900">
    <div class="relative h-96 md:h-[500px] overflow-hidden">
        <!-- Banner 1 -->
        <div class="absolute inset-0 transition-opacity duration-1000" id="banner1">
            <img src="{{ asset('images/banner1.jpg') }}" alt="Radhe Crackers Banner" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                
            </div>
        </div>
        <!-- Banner 2 -->
        <div class="absolute inset-0 transition-opacity duration-1000 opacity-0" id="banner2">
            <img src="{{ asset('images/banner2.jpg') }}" alt="Fireworks Collection" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                
            </div>
        </div>
        <!-- Banner Navigation -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <button onclick="showBanner(1)" class="w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-100 transition-colors" id="nav1"></button>
            <button onclick="showBanner(2)" class="w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-100 transition-colors" id="nav2"></button>
        </div>
    </div>

    
</div>

<!-- Category Images Section -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <!-- Kids Category -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow border border-gray-100 hover:border-gray-400">
                <img src="{{ asset('images/kids.png') }}" alt="Kids Crackers" class="w-full h-48 object-cover">
                <div class="p-5 text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Kids Crackers</h3>
                    <p class="text-gray-600 text-sm">Safe and fun crackers for children</p>
    </div>
</div>
            <!-- Gift Category -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow border border-gray-100 hover:border-gray-400">
                <img src="{{ asset('images/gift.png') }}" alt="Gift Boxes" class="w-full h-48 object-cover">
                <div class="p-5 text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Gift Boxes</h3>
                    <p class="text-gray-600 text-sm">Perfect gift packages for celebrations</p>
                </div>
            </div>
            <!-- New Arrivals -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow border border-gray-100 hover:border-gray-400">
                <img src="{{ asset('images/new.png') }}" alt="New Arrivals" class="w-full h-48 object-cover">
                <div class="p-5 text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">New Arrivals</h3>
                    <p class="text-gray-600 text-sm">Latest products in our collection</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Best For Your Categories Section (Horizontal Scroll on Mobile) -->
<div class="py-16"style="background-color: #1E093B;">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-white mb-10">Best For Your Categories</h2>
        <div class="flex gap-4 overflow-x-auto pb-2 md:grid md:grid-cols-4 lg:grid-cols-7 md:gap-6 scrollbar-thin scrollbar-thumb-orange-200">
            @php
                $categories = [
                    'SINGLE FLASH' => ['image' => 'single-flash.webp', 'count' => \App\Models\Stock::where('category', 'SINGLE FLASH')->count()],
                    'BIJILI CRACKERS' => ['image' => 'bijili-crackers.jpg', 'count' => \App\Models\Stock::where('category', 'BIJILI CRACKERS')->count()],
                    'BOMBS' => ['image' => 'bijili-crackers.jpg', 'count' => \App\Models\Stock::where('category', 'BOMBS')->count()],
                    'ROCKETS' => ['image' => 'bijili-crackers.jpg', 'count' => \App\Models\Stock::where('category', 'ROCKETS')->count()],
                    'SPARKLERS' => ['image' => 'bijili-crackers.jpg', 'count' => \App\Models\Stock::where('category', 'SPARKLERS')->count()],
                    'CHIT PUT' => ['image' => 'bijili-crackers.jpg', 'count' => \App\Models\Stock::where('category', 'CHIT PUT')->count()],
                    'TWINKLING STAR' => ['image' => 'bijili-crackers.jpg', 'count' => \App\Models\Stock::where('category', 'TWINKLING STAR')->count()]
                ];
            @endphp
            @foreach($categories as $categoryName => $category)
                <div class="min-w-[160px] md:min-w-0 bg-white rounded-xl shadow group cursor-pointer border border-gray-100 hover:border-orange-400 transition-all flex-shrink-0">
                    <div class="relative overflow-hidden rounded-t-xl">
                        <img src="{{ asset('images/' . $category['image']) }}" alt="{{ $categoryName }}" class="w-full h-28 object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                    </div>
                    <div class="p-3 text-center">
                        <h3 class="font-semibold text-gray-900 mb-1 text-sm">{{ $categoryName }}</h3>
                        <p class="text-xs text-gray-600">{{ $category['count'] }} Products</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Marquee Section -->
<div class="bg-gradient-to-r from-white-700 to-white-900 text-black py-5 p-8 overflow-hidden">
    <div class="marquee-container">
        <div class="marquee-content">
            <div class="marquee-item"><span class="text-yellow-300 mr-2">⭐</span> Door Delivery Available all over India</div>
            <div class="marquee-item"><span class="text-yellow-300 mr-2">⭐</span> Minimum order value Rs.2500 for Tamilnadu and Rs.3000 for other states</div>
            <div class="marquee-item"><span class="text-yellow-300 mr-2">⭐</span> Best Quality Fireworks</div>
            <div class="marquee-item"><span class="text-yellow-300 mr-2">⭐</span> Safe and Reliable Products</div>
        </div>
    </div>
</div>

<!-- Popular Products Section -->
<div class="py-16 text-white"style="background-color: #1E093B;">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4">Popular Products</h2>
        <p class="text-white-600 text-center mb-12">Most loved firework items</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $popularProducts = \App\Models\Stock::active()->whereIn('category', ['BOMBS', 'SINGLE FLASH', 'ROCKETS'])->take(4)->get();
            @endphp
            @foreach($popularProducts as $product)
                <div class="product-card bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow border border-gray-100 hover:border-gray-400">
                    <div class="p-6 text-center">
                        <img src="{{ $product->image_url ?? asset('images/firework-default.png') }}" alt="{{ $product->item_name }}" class="w-full h-32 object-contain mb-4 mx-auto rounded">
                        <h3 class="text-lg font-bold mb-2 text-gray-900">{{ $product->item_name }}</h3>
                        @if($product->description)
                            <p class="text-sm text-gray-600 mb-4">{{ $product->description }}</p>
                        @endif
                        <div class="mb-4 flex items-center justify-center gap-2">
                            @if($product->original_price)
                                <span class="line-through text-gray-400 text-lg">₹{{ number_format($product->original_price, 0) }}</span>
                            @endif
                            <span class="text-orange-600 font-bold text-2xl">₹{{ number_format($product->price, 0) }}</span>
                        </div>
                        @if($product->discount_percentage)
                            <div class="discount-badge mb-4 inline-block bg-red-500 text-white px-3 rounded-full text-xs font-semibold">
                                -{{ $product->discount_percentage }}% OFF
                            </div><br>
                        @endif
                        <a href="{{ route('order.form') }}" class="btn-primary w-full mt-2 pt-2">Order Now</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Latest Products Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4">Latest Products</h2>
        <p class="text-gray-600 text-center mb-12">New arrivals in our collection</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $latestProducts = \App\Models\Stock::active()->latest()->take(6)->get();
            @endphp
            @foreach($latestProducts as $product)
                <div class="product-card bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow border border-gray-100 hover:border-gray-500">
                    <div class="p-6 text-center">
                        <img src="{{ $product->image_url ?? asset('images/firework-default.png') }}" alt="{{ $product->item_name }}" class="w-full h-32 object-contain mb-4 mx-auto rounded">
                        <h3 class="text-lg font-bold mb-2 text-gray-900">{{ $product->item_name }}</h3>
                        @if($product->description)
                            <p class="text-sm text-gray-600 mb-4">{{ $product->description }}</p>
                        @endif
                        <div class="mb-4 flex items-center justify-center gap-2">
                            @if($product->original_price)
                                <span class="line-through text-gray-400 text-lg">₹{{ number_format($product->original_price, 0) }}</span>
                            @endif
                            <span class="text-orange-600 font-bold text-2xl">₹{{ number_format($product->price, 0) }}</span>
                        </div>
                        @if($product->discount_percentage)
                            <div class="discount-badge mb-4 inline-block bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                -{{ $product->discount_percentage }}% OFF
                            </div><br>
                        @endif
                        <a href="{{ route('order.form') }}" class="btn-primary w-full pt-2">Order Now</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="py-16 text-white "style="background-color: #1E093B;">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-6">Ready to Celebrate?</h2>
        <p class="text-xl mb-8">Get the best fireworks for your special occasions</p>
        <div class="space-x-4">
            <a href="{{ route('order.form') }}" class="px-8 py-5 rounded-lg btn-primary w-full mt-2">Order Now</a>
            <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-black transition-colors inline-block">Contact Us</a>
        </div>
    </div>
</div>

<!-- Marquee Animation Styles -->
<style>
.marquee-container {
    overflow: hidden;
    white-space: nowrap;
}
.marquee-content {
    display: inline-block;
    animation: marquee 20s linear infinite;
}
.marquee-item {
    display: inline-block;
    margin-right: 50px;
    font-size: 16px;
    font-weight: 500;
}
@keyframes marquee {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}
</style>
<script>
// Banner Slider Functionality
let currentBanner = 1;
const banners = ['banner1', 'banner2'];
const navs = ['nav1', 'nav2'];
function showBanner(bannerNumber) {
    banners.forEach(banner => {
        document.getElementById(banner).style.opacity = '0';
    });
    navs.forEach(nav => {
        document.getElementById(nav).classList.remove('bg-opacity-100');
        document.getElementById(nav).classList.add('bg-opacity-50');
    });
    document.getElementById(`banner${bannerNumber}`).style.opacity = '1';
    document.getElementById(`nav${bannerNumber}`).classList.remove('bg-opacity-50');
    document.getElementById(`nav${bannerNumber}`).classList.add('bg-opacity-100');
    currentBanner = bannerNumber;
}
setInterval(() => {
    currentBanner = currentBanner === 1 ? 2 : 1;
    showBanner(currentBanner);
}, 5000);
document.getElementById('nav1').classList.remove('bg-opacity-50');
document.getElementById('nav1').classList.add('bg-opacity-100');
</script>
@endsection 