@extends('layouts.app')

@section('title', 'Radhe Crackers – Bringing Joy, Spark by Spark')

@section('content')

<!-- Legal Notice Bottom-Right Popup -->
<div id="legalNoticePopup" class="fixed bottom-0 left-0 right-0 z-50 bg-white border border-red-300 rounded-t-xl shadow-lg max-w-xs w-full p-4 text-gray-900 animate-slide-in mx-auto mb-4 md:bottom-4 md:left-auto md:right-4 md:rounded-xl md:max-w-xs" style="display: block;">
    <button onclick="document.getElementById('legalNoticePopup').style.display='none'" class="absolute top-2 right-3 text-gray-500 hover:text-red-600 text-3xl font-bold md:text-2xl">&times;</button>
    <h3 class="text-base font-bold mb-2 text-red-700 text-center">Important Legal Notice</h3>
    <p class="text-xs leading-relaxed text-justify">
  As per 2018 supreme court order, online sale of firecrackers are not permitted! We value our customers and at the same time, respect jurisdiction. We request you to add your products to the cart and submit the required crackers through the enquiry button. We will contact you within 24 hrs and confirm the order through WhatsApp or phone call. We send the parcels through registered and legal transport service providers as like every other major companies in Sivakasi is doing so.    </p>
</div>

<style>
@media (max-width: 768px) {
  #legalNoticePopup {
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    max-width: 100vw !important;
    border-radius: 1rem 1rem 0 0 !important;
    margin-bottom: 0 !important;
    padding-bottom: 2.5rem !important;
  }
  #legalNoticePopup .absolute.top-2.right-3 {
    right: 1rem !important;
    top: 0.5rem !important;
  }
}
@keyframes slide-in {
  from { transform: translateY(100px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
.animate-slide-in {
  animation: slide-in 0.5s ease;
}
</style>
<!-- Hero Banner Slider -->
@php
    $heroSlides = \App\Models\HeroSlide::getActiveSlides();
    if ($heroSlides->isEmpty()) {
        $heroSlides = collect([
            (object)[
                'id' => 1,
                'title' => 'Festival of Lights Celebration',
                'subtitle' => 'Premium Sivakasi Crackers Direct to Your Doorstep',
                'image_url' => asset('images/radhe_crackers_images_2026/home carosel 1.png'),
                'link_url' => route('express-shop'),
                'button_text' => 'Shop Now',
            ],
            (object)[
                'id' => 2,
                'title' => 'Exclusive Festive Mega Offers',
                'subtitle' => 'Enjoy Up to 70% + 15% Special Discount This Season',
                'image_url' => asset('images/radhe_crackers_images_2026/home carosel 2.png'),
                'link_url' => route('express-shop'),
                'button_text' => 'Order Now',
            ],
        ]);
    }
@endphp
<div class="relative bg-gray-900">
    <div class="relative h-72 sm:h-96 md:h-[500px] overflow-hidden" id="heroSliderContainer">
        @foreach($heroSlides as $index => $slide)
            <div class="absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }} hero-slide" id="banner{{ $index }}">
                <img src="{{ $slide->image_url }}" alt="{{ $slide->title ?: 'Radhe Crackers Banner' }}" class="w-full h-full object-cover">
                @if($slide->title || $slide->subtitle)
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent flex items-end md:items-center justify-start md:justify-center p-6 md:p-12">
                        <div class="max-w-2xl text-left md:text-center text-white">
                            @if($slide->title)
                                <h1 class="text-2xl sm:text-4xl md:text-5xl font-black drop-shadow-lg tracking-tight mb-2">{{ $slide->title }}</h1>
                            @endif
                            @if($slide->subtitle)
                                <p class="text-xs sm:text-base md:text-lg text-amber-200 drop-shadow mb-4 font-medium">{{ $slide->subtitle }}</p>
                            @endif
                            @if($slide->link_url)
                                <a href="{{ $slide->link_url }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-black font-extrabold text-xs sm:text-sm px-6 py-2.5 rounded-full shadow-lg hover:scale-105 transition-all">
                                    <span>{{ $slide->button_text ?: 'Order Now' }}</span>
                                    <span>&rarr;</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endforeach

        @if($heroSlides->count() > 1)
            <!-- Banner Navigation Dots -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2.5 z-20">
                @foreach($heroSlides as $index => $slide)
                    <button onclick="showBanner({{ $index }})" 
                            aria-label="Go to slide {{ $index + 1 }}"
                            class="w-3 h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-amber-400 w-8' : 'bg-white/60 hover:bg-white' }} hero-nav" 
                            id="nav{{ $index }}"></button>
                @endforeach
            </div>

            <!-- Left / Right arrows -->
            <button onclick="prevBanner()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 text-white flex items-center justify-center transition z-20 text-lg" aria-label="Previous Slide">
                &#10094;
            </button>
            <button onclick="nextBanner()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 text-white flex items-center justify-center transition z-20 text-lg" aria-label="Next Slide">
                &#10095;
            </button>
        @endif
    </div>
</div>



<!-- Category Images Section -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <!-- Kids Category -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow border border-gray-100 hover:border-gray-400">
                <img src="{{ asset('front/Kids Special.jpg') }}" alt="Kids Crackers" class="w-full h-48 object-cover">
                <div class="p-5 text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Kids Crackers</h3>
                    <p class="text-gray-600 text-sm">Safe and fun crackers for children</p>
    </div>
</div>
            <!-- Gift Category -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow border border-gray-100 hover:border-gray-400">
                <img src="{{ asset('front/Gift Boxes.jpg') }}" alt="Gift Boxes" class="w-full h-48 object-cover">
                <div class="p-5 text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Gift Boxes</h3>
                    <p class="text-gray-600 text-sm">Perfect gift packages for celebrations</p>
                </div>
            </div>
            <!-- New Arrivals -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow border border-gray-100 hover:border-gray-400">
                <img src="{{ asset('front/New Arrivals.jpg') }}" alt="New Arrivals" class="w-full h-48 object-cover">
                <div class="p-5 text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">New Arrivals</h3>
                    <p class="text-gray-600 text-sm">Latest products in our collection</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Best For Your Categories Section (Horizontal Scroll on Mobile) --}}
@if(\Illuminate\Support\Facades\Cache::get('home_show_categories_section', true))
<div class="py-16 bg-gradient-to-r from-[#1E093B] via-[#3B156C] to-[#B67121]">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-white mb-2">Best For Your Categories</h2>
        <p class="text-center text-amber-200 text-sm mb-10">Explore our wide selection of authentic Sivakasi firecrackers</p>
        <div class="flex gap-4 overflow-x-auto pb-2 md:grid md:grid-cols-4 lg:grid-cols-7 md:gap-6 scrollbar-thin scrollbar-thumb-gray-200">
            @php
                $categoryConfigs = [
                    'SINGLE FLASH' => ['search' => 'SINGLE FLASH', 'patterns' => ['single flash', 'single colour shot', '2" single'], 'default_img' => 'images/radhe_crackers_images_2026/single flash.png'],
                    'BIJILI CRACKERS' => ['search' => 'BIJILI CRACKERS', 'patterns' => ['bijili'], 'default_img' => 'images/radhe_crackers_images_2026/bijili crackers.png'],
                    'BOMBS' => ['search' => 'BOMB', 'patterns' => ['bomb'], 'default_img' => 'images/bijili-crackers.jpg'],
                    'ROCKETS' => ['search' => 'ROCKET', 'patterns' => ['rocket'], 'default_img' => 'images/radhe_crackers_images_2026/rockets.png'],
                    'SPARKLERS' => ['search' => 'SPARKLERS', 'patterns' => ['sparkler'], 'default_img' => 'images/radhe_crackers_images_2026/sparkles.png'],
                    'CHIT PUT' => ['search' => 'CHIT PUT', 'patterns' => ['chit put', 'paper flash'], 'default_img' => 'images/new.png'],
                    'TWINKLING STAR' => ['search' => 'TWINKLING STAR', 'patterns' => ['twinkling'], 'default_img' => 'images/single-flash.webp'],
                ];
                $resolvedCategories = [];
                foreach ($categoryConfigs as $displayName => $cfg) {
                    $stockQuery = \App\Models\Stock::where(function($q) use ($cfg) {
                        foreach ($cfg['patterns'] as $p) {
                            $q->orWhere('category', 'LIKE', "%$p%")
                              ->orWhere('item_name', 'LIKE', "%$p%");
                        }
                    });
                    $count = (clone $stockQuery)->count();
                    $sampleStock = (clone $stockQuery)->whereNotNull('image')->where('image', '!=', '')->first();
                    $imgUrl = $sampleStock ? $sampleStock->image_url : asset($cfg['default_img']);
                    $resolvedCategories[$displayName] = [
                        'image' => $imgUrl,
                        'count' => $count,
                        'search' => $cfg['search']
                    ];
                }
            @endphp
            @foreach($resolvedCategories as $categoryName => $category)
                <a href="{{ route('express-shop') }}?category={{ urlencode($category['search']) }}" 
                   class="min-w-[160px] md:min-w-0 bg-white rounded-xl shadow group cursor-pointer border border-gray-100 hover:border-amber-400 hover:shadow-xl transition-all flex-shrink-0 block transform hover:-translate-y-1">
                    <div class="relative overflow-hidden rounded-t-xl bg-gray-50 h-28 flex items-center justify-center">
                        <img src="{{ $category['image'] }}" alt="{{ $categoryName }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-5 transition-all duration-300"></div>
                    </div>
                    <div class="p-3 text-center">
                        <h3 class="font-bold text-gray-900 mb-1 text-sm group-hover:text-amber-700 transition-colors">{{ $categoryName }}</h3>
                        <p class="text-xs text-gray-500 font-medium">{{ $category['count'] }} Products</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Marquee Section -->
<div class="bg-gradient-to-r from-white-700 to-white-900 text-black py-5 p-8 overflow-hidden">
    <div class="marquee-container">
        <div class="marquee-content">
            <div class="marquee-item"><span class="text-yellow-300 mr-2">⭐</span> Door Delivery Available all over India</div>
            <div class="marquee-item"><span class="text-yellow-300 mr-2">⭐</span> Minimum order value Rs.2500 for Tamilnadu and Rs.5000 for other states</div>
            <div class="marquee-item"><span class="text-yellow-300 mr-2">⭐</span> Best Quality Fireworks</div>
            <div class="marquee-item"><span class="text-yellow-300 mr-2">⭐</span> Safe and Reliable Products</div>
        </div>
    </div>
</div>

{{-- Popular Products Section --}}
@if(\Illuminate\Support\Facades\Cache::get('home_show_popular_section', true))
<div class="py-16 text-white bg-gradient-to-r from-[#1E093B] via-[#3B156C] to-[#B67121]">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4">Popular Products</h2>
        <p class="text-amber-200 text-center mb-12">Most loved firework items</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $popularProducts = \App\Models\HomepageProduct::where('is_popular', true)->where('is_active', true)->get();
                if ($popularProducts->isEmpty()) {
                    $popularProducts = \App\Models\Stock::where('is_popular', true)
                        ->where(function($q) { $q->where('is_active', 1)->orWhere('quantity', '>', 0); })
                        ->take(8)
                        ->get();
                }
            @endphp
            @forelse($popularProducts as $product)
                <div class="product-card bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-gray-100 hover:border-amber-400 flex flex-col justify-between">
                    <div class="p-6 text-center flex flex-col items-center flex-1 justify-between">
                        <div class="w-full">
                            <div class="flex flex-wrap gap-1.5 justify-center mb-3">
                                @if($product->discount_percentage)
                                    <span class="bg-red-500 text-white px-2 py-0.5 rounded text-xs font-bold">-{{ $product->discount_percentage }}% OFF</span>
                                @endif
                                @if($product->special_discount_percentage)
                                    <span class="bg-orange-500 text-white px-2 py-0.5 rounded text-xs font-bold">+{{ $product->special_discount_percentage }}% Special</span>
                                @endif
                                @if($product->youtube_url)
                                    <a href="{{ $product->youtube_url }}" target="_blank" class="inline-flex items-center text-red-600 hover:text-red-700 ml-1" title="Watch Video">
                                        <i class="fab fa-youtube text-lg"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="w-full h-36 flex items-center justify-center mb-4 bg-gray-50 rounded-xl p-2">
                                <img src="{{ $product->image_url }}" alt="{{ $product->item_name }}" class="max-h-full max-w-full object-contain hover:scale-105 transition-transform duration-300">
                            </div>
                            <h3 class="text-base font-bold mb-1.5 text-gray-900 leading-snug">{{ $product->item_name }}</h3>
                            @if($product->description)
                                <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $product->description }}</p>
                            @endif
                        </div>
                        <div class="w-full mt-2">
                            <div class="mb-3 flex items-baseline justify-center gap-2">
                                @if($product->original_price && $product->original_price > $product->price)
                                    <span class="line-through text-gray-400 text-sm">₹{{ number_format($product->original_price, 0) }}</span>
                                @endif
                                <span class="text-amber-700 font-extrabold text-2xl">₹{{ number_format($product->price, 0) }}</span>
                            </div>
                            <a href="{{ route('express-shop') }}" class="btn-primary w-full block py-2.5 font-bold rounded-xl shadow hover:shadow-lg transition text-center">Order Now</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-8 text-amber-200">
                    <p>No popular products featured right now. Check out our <a href="{{ route('express-shop') }}" class="underline font-bold text-yellow-300">quotation page</a> for the full catalog!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endif

<!-- Latest Products Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4 text-gray-900">Latest Products</h2>
        <p class="text-gray-500 text-center mb-12">New arrivals in our collection</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $latestProducts = \App\Models\HomepageProduct::where('is_latest', true)->where('is_active', true)->get();
                if ($latestProducts->isEmpty()) {
                    $latestProducts = \App\Models\Stock::where('is_latest', true)
                        ->where(function($q) { $q->where('is_active', 1)->orWhere('quantity', '>', 0); })
                        ->take(6)
                        ->get();
                }
            @endphp
            @forelse($latestProducts as $product)
                <div class="product-card bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-gray-100 hover:border-amber-500 flex flex-col justify-between">
                    <div class="p-6 text-center flex flex-col items-center flex-1 justify-between">
                        <div class="w-full">
                            <div class="flex flex-wrap gap-1.5 justify-center mb-3">
                                @if($product->discount_percentage)
                                    <span class="bg-red-500 text-white px-2 py-0.5 rounded text-xs font-bold">-{{ $product->discount_percentage }}% OFF</span>
                                @endif
                                @if($product->special_discount_percentage)
                                    <span class="bg-orange-500 text-white px-2 py-0.5 rounded text-xs font-bold">+{{ $product->special_discount_percentage }}% Special</span>
                                @endif
                                @if($product->youtube_url)
                                    <a href="{{ $product->youtube_url }}" target="_blank" class="inline-flex items-center text-red-600 hover:text-red-700 ml-1" title="Watch Video">
                                        <i class="fab fa-youtube text-lg"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="w-full h-36 flex items-center justify-center mb-4 bg-gray-50 rounded-xl p-2">
                                <img src="{{ $product->image_url }}" alt="{{ $product->item_name }}" class="max-h-full max-w-full object-contain hover:scale-105 transition-transform duration-300">
                            </div>
                            <h3 class="text-base font-bold mb-1.5 text-gray-900 leading-snug">{{ $product->item_name }}</h3>
                            @if($product->description)
                                <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $product->description }}</p>
                            @endif
                        </div>
                        <div class="w-full mt-2">
                            <div class="mb-3 flex items-baseline justify-center gap-2">
                                @if($product->original_price && $product->original_price > $product->price)
                                    <span class="line-through text-gray-400 text-sm">₹{{ number_format($product->original_price, 0) }}</span>
                                @endif
                                <span class="text-amber-700 font-extrabold text-2xl">₹{{ number_format($product->price, 0) }}</span>
                            </div>
                            <a href="{{ route('express-shop') }}" class="btn-primary w-full block py-2.5 font-bold rounded-xl shadow hover:shadow-lg transition text-center">Order Now</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-gray-400">
                    <p>No new arrivals featured right now. Check out our <a href="{{ route('express-shop') }}" class="underline font-bold text-amber-600">quotation page</a> for the full catalog!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="py-16 text-white bg-gradient-to-r from-[#1E093B] via-[#3B156C] to-[#B67121]">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-6">Ready to Celebrate?</h2>
        <p class="text-xl mb-8">Get the best fireworks for your special occasions</p>
        <div class="space-x-4">
            <div class="flex flex-col items-center justify-center space-y-2 relative max-w-xs mx-auto md:flex-row md:space-y-4 md:space-x-4 md:max-w-none">
                
                <a href="{{ route('contact') }}" class=" border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-black transition-colors w-64 -mt-6 md:mt-0 md:w-auto md:relative md:top-0 bg-transparent z-0" style="backdrop-filter: blur(0);">Contact Us</a>
            </div>
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
// Dynamic Banner Slider Functionality
let currentBannerIdx = 0;
const totalBanners = {{ $heroSlides->count() }};

function showBanner(index) {
    if (totalBanners <= 0) return;
    currentBannerIdx = (index + totalBanners) % totalBanners;

    document.querySelectorAll('.hero-slide').forEach((slide, i) => {
        if (i === currentBannerIdx) {
            slide.classList.remove('opacity-0', 'pointer-events-none');
            slide.classList.add('opacity-100');
        } else {
            slide.classList.add('opacity-0', 'pointer-events-none');
            slide.classList.remove('opacity-100');
        }
    });

    document.querySelectorAll('.hero-nav').forEach((nav, i) => {
        if (i === currentBannerIdx) {
            nav.classList.remove('bg-white/60', 'w-3');
            nav.classList.add('bg-amber-400', 'w-8');
        } else {
            nav.classList.add('bg-white/60', 'w-3');
            nav.classList.remove('bg-amber-400', 'w-8');
        }
    });
}

function nextBanner() {
    showBanner(currentBannerIdx + 1);
}

function prevBanner() {
    showBanner(currentBannerIdx - 1);
}

let bannerInterval;
function startBannerAutoSlide() {
    if (totalBanners > 1) {
        bannerInterval = setInterval(nextBanner, 6000);
    }
}

const sliderContainer = document.getElementById('heroSliderContainer');
if (sliderContainer) {
    sliderContainer.addEventListener('mouseenter', () => clearInterval(bannerInterval));
    sliderContainer.addEventListener('mouseleave', () => startBannerAutoSlide());
}

startBannerAutoSlide();
</script>

<!-- Floating Compact Cost Estimator Widget -->
<a href="{{ route('express-shop') }}" 
   id="floatingCostEstimator" 
   title="Click to calculate cost & generate estimate"
   class="fixed left-3 bottom-4 sm:left-6 sm:bottom-6 z-40 group flex items-center gap-2.5 bg-gradient-to-r from-[#2D0B5A] via-[#1E093B] to-[#B67121] text-white px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-full border-2 border-yellow-400/80 shadow-2xl transition-all duration-300 hover:scale-105 active:scale-95 animate-cost-estimator-pulse">
    
    <!-- Pulsing Outer Glow Aura -->
    <span class="absolute -inset-1 rounded-full bg-gradient-to-r from-yellow-400 via-amber-500 to-purple-600 opacity-75 blur-sm animate-pulse group-hover:opacity-100 transition-opacity pointer-events-none"></span>

    <!-- Widget Content Container -->
    <div class="relative flex items-center gap-2 z-10">
        <!-- Icon Container with Blinking Red Badge -->
        <div class="relative flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-yellow-400 text-purple-950 rounded-full font-black shadow-md flex-shrink-0">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h6m-6 4h6M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <!-- Blinking Notification Pulse -->
            <span class="absolute -top-0.5 -right-0.5 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-300 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border border-white"></span>
            </span>
        </div>

        <!-- Text Block -->
        <div class="flex flex-col text-left">
            <div class="flex items-center gap-1.5">
                <span class="text-xs sm:text-sm font-extrabold text-white tracking-wide leading-tight group-hover:text-yellow-300 transition-colors">
                    Quick Price Calculator
                </span>
                <span class="bg-yellow-400 text-purple-950 font-black text-[9px] px-1.5 py-0.2 rounded-full uppercase animate-bounce shadow-sm">
                    70% OFF
                </span>
            </div>
            <span class="text-[10px] text-yellow-300/90 font-semibold leading-none mt-0.5 flex items-center gap-0.5">
                Instant Order & Estimate &rarr;
            </span>
        </div>
    </div>
</a>

<!-- Continuous Glow Pulse Animation Styling -->
<style>
@keyframes cost-estimator-pulse {
    0%, 100% {
        box-shadow: 0 0 12px rgba(234, 179, 8, 0.6), 0 0 25px rgba(182, 113, 33, 0.4);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 0 22px rgba(234, 179, 8, 0.95), 0 0 40px rgba(182, 113, 33, 0.7);
        transform: scale(1.03);
    }
}
.animate-cost-estimator-pulse {
    animation: cost-estimator-pulse 2s infinite ease-in-out;
}
</style>
@endsection 