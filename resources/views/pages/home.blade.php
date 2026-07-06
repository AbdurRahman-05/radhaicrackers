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
<div class="relative bg-gray-900">
    <div class="relative h-96 md:h-[500px] overflow-hidden">
        <!-- Banner 1 -->
        <div class="absolute inset-0 transition-opacity duration-1000" id="banner1">
            <img src="{{ asset('hero/bg.jpg') }}" alt="Radhe Crackers Banner" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                
            </div>
        </div>
        <!-- Banner 2 -->
        <div class="absolute inset-0 transition-opacity duration-1000 opacity-0" id="banner2">
            <img src="{{ asset('hero/bg.jpg') }}" alt="Fireworks Collection" class="w-full h-full object-cover">
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
        <h2 class="text-4xl font-bold text-center text-white mb-10">Best For Your Categories</h2>
        <div class="flex gap-4 overflow-x-auto pb-2 md:grid md:grid-cols-4 lg:grid-cols-7 md:gap-6 scrollbar-thin scrollbar-thumb-gray-200">
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
                <div class="min-w-[160px] md:min-w-0 bg-white rounded-xl shadow group cursor-pointer border border-gray-100 hover:border-gray-400 transition-all flex-shrink-0">
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
        <p class="text-white-600 text-center mb-12">Most loved firework items</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $popularProducts = \App\Models\HomepageProduct::where('is_popular', true)->where('is_active', true)->get();
            @endphp
            @foreach($popularProducts as $product)
                <div class="product-card bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow border border-gray-100 hover:border-gray-400">
                    <div class="p-6 text-center">
                        <div class="flex gap-2 justify-center mb-2">
                            @if($product->discount_percentage)
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">-{{ $product->discount_percentage }}% OFF</span>
                            @endif
                            @if($product->special_discount_percentage)
                                <span class="bg-orange-500 text-white px-2 py-1 rounded text-xs font-semibold">+{{ $product->special_discount_percentage }}% Special</span>
                            @endif
                            @if($product->youtube_url)
                                <a href="{{ $product->youtube_url }}" target="_blank" class="inline-block ml-1" title="Watch on YouTube">
                                    <span class="inline-block align-middle"><i class="fab fa-youtube text-red-600 text-lg"></i></span>
                                </a>
                            @endif
                        </div>
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/firework-default.png') }}" alt="{{ $product->item_name }}" class="w-full h-32 object-contain mb-4 mx-auto rounded">
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
                        <a href="{{ route('express-shop') }}" class="btn-primary w-full mt-2 pt-2">Order Now</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Latest Products Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4">Latest Products</h2>
        <p class="text-gray-600 text-center mb-12">New arrivals in our collection</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $latestProducts = \App\Models\HomepageProduct::where('is_latest', true)->where('is_active', true)->get();
            @endphp
            @foreach($latestProducts as $product)
                <div class="product-card bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow border border-gray-100 hover:border-gray-500">
                    <div class="p-6 text-center">
                        <div class="flex gap-2 justify-center mb-2">
                            @if($product->discount_percentage)
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">-{{ $product->discount_percentage }}% OFF</span>
                            @endif
                            @if($product->special_discount_percentage)
                                <span class="bg-orange-500 text-white px-2 py-1 rounded text-xs font-semibold">+{{ $product->special_discount_percentage }}% Special</span>
                            @endif
                            @if($product->youtube_url)
                                <a href="{{ $product->youtube_url }}" target="_blank" class="inline-block ml-1" title="Watch on YouTube">
                                    <span class="inline-block align-middle"><i class="fab fa-youtube text-red-600 text-lg"></i></span>
                                </a>
                            @endif
                        </div>
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/firework-default.png') }}" alt="{{ $product->item_name }}" class="w-full h-32 object-contain mb-4 mx-auto rounded">
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
                        <a href="{{ route('express-shop') }}" class="btn-primary w-full pt-2">Order Now</a>
                    </div>
                </div>
            @endforeach
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

// Modal controls
function openEstimateModal() {
    const modal = document.getElementById('estimateModal');
    const content = document.getElementById('estimateModalContent');
    if (modal && content) {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }
}

function closeEstimateModal() {
    const modal = document.getElementById('estimateModal');
    const content = document.getElementById('estimateModalContent');
    if (modal && content) {
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('estimateModal');
    if (modal) {
        // Close modal when clicking outside content area
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEstimateModal();
            }
        });
    }

    // Show modal immediately on page load
    openEstimateModal();

    // Auto popup every 10 seconds if closed
    setInterval(() => {
        const modal = document.getElementById('estimateModal');
        if (modal && modal.classList.contains('opacity-0')) {
            openEstimateModal();
        }
    }, 10000);
});
</script>

<!-- Creative Estimate Modal Popup -->
<div id="estimateModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
    <div class="relative bg-gradient-to-b from-[#2D0B5A] to-[#130427] text-white rounded-3xl w-full max-w-lg overflow-hidden border border-purple-500/20 shadow-2xl transform scale-95 transition-all duration-300" id="estimateModalContent">
        <!-- Close Button -->
        <button onclick="closeEstimateModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors p-2 bg-white/5 rounded-full hover:bg-white/10 z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        <!-- Header Image banner -->
        <div class="relative h-40 bg-cover bg-center" style="background-image: url('{{ asset('hero/bg.jpg') }}');">
            <div class="absolute inset-0 bg-gradient-to-t from-[#2D0B5A] via-[#2D0B5A]/50 to-transparent"></div>
            <div class="absolute bottom-4 left-6">
                <span class="inline-flex items-center gap-1 text-xs font-bold text-yellow-300 uppercase tracking-widest bg-black/30 backdrop-blur-md px-3 py-1 rounded-full mb-1">
                    📊 CIVIC WHOLESALE
                </span>
                <h3 class="text-2xl font-black">Estimate Calculator</h3>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 space-y-6">
            <p class="text-sm text-gray-300 leading-relaxed">
                Add products from the crackers list to create your purchase draft. Our system automatically processes your pricing logic instantly:
            </p>
            
            <!-- Wholesale Discount Flowchart -->
            <div class="bg-black/30 rounded-2xl p-4 border border-purple-500/10 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">1. Civic Discount</span>
                    <span class="font-bold text-green-400">Flat 70% Off</span>
                </div>
                <div class="border-t border-purple-500/15"></div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">2. Special Discount</span>
                    <span class="font-bold text-green-400">Additional 15% Off</span>
                </div>
                <div class="border-t border-purple-500/15"></div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">3. Packing Fees</span>
                    <span class="font-bold text-orange-400">5% Charge Only</span>
                </div>
            </div>
            
            <p class="text-xs text-gray-400 text-center">
                * PDF quote containing complete calculations will be generated automatically.
            </p>
            
            <!-- Actions -->
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('express-shop') }}" class="flex items-center justify-center px-4 py-3.5 bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-300 hover:to-orange-400 text-gray-900 font-extrabold text-sm rounded-xl shadow-lg transition-all duration-200 text-center">
                    Go to Quotation
                </a>
                <a href="{{ route('price-list') }}" class="flex items-center justify-center px-4 py-3.5 bg-white/10 hover:bg-white/15 text-white border border-white/10 font-bold text-sm rounded-xl transition-all duration-200 text-center">
                    View Price List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 