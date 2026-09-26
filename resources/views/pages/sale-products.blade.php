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
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow relative">
                        <div class=" flex flex-row items-center justify-between w-full " >
                        
                        @if($product->discount_percentage)
                            <div class="bg-red-500 text-white text-xs px-2 py-1 rounded mb-2 inline-block">
                                -{{ $product->discount_percentage }}% OFF
                            </div>
                        @endif
                        @if($product->special_discount_percentage)
                            <div class="bg-red-500 text-white text-xs px-2 py-1 rounded mb-2 inline-block">
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
                        </div>
                         <!-- youtube url ends here -->
                        <div class="text-center mb-4">
                            @if($product->image)
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->item_name }}" 
                                     class="w-24 h-24 object-cover rounded-lg mx-auto mb-2"
                                     onerror="handleCardImageError(this, '{{ addslashes($product->category) }}', '{{ basename($product->image) }}')"
                                     data-category="{{ $product->category }}"
                                     data-filename="{{ basename($product->image) }}">
                                <div class="card-fallback-icon text-4xl mb-2 hidden">
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
                        
                        <h3 class="font-semibold text-gray-900 mb-1 text-center">{{ $product->item_name }}</h3>
                        @if($product->description)
                            <p class="text-xs font-medium text-gray-700 mb-1 text-center">{{ $product->description }}</p>
                        @endif
                        @if($product->meta_description)
                            <p class="text-xs text-gray-500 mb-2 text-center line-clamp-2 px-1" title="{{ $product->meta_description }}">{{ $product->meta_description }}</p>
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
                        
                        <a href="{{ route('order.form') }}" class="text-white px-4 py-2 rounded hover:bg-gray-600 transition-colors inline-block w-full text-center mb-3" style="background-color: #1E093B;">
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

<!-- Video Modal starts here -->
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

<!-- JavaScript for Video Modal starts here -->
<script>
// Video Modal Functions
function openVideoModal(youtubeUrl, productName) {
    if (!youtubeUrl) return;
    const videoId = extractYouTubeVideoId(youtubeUrl);
    if (!videoId) {
        if (/^https?:\/\//i.test(youtubeUrl.trim())) {
            window.open(youtubeUrl.trim(), '_blank');
            return;
        }
        alert('Invalid YouTube URL');
        return;
    }
    
    const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&enablejsapi=1&rel=0`;
    
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
    if (!url || typeof url !== 'string') return null;
    url = url.trim();
    
    // Direct 11-char ID
    if (/^[a-zA-Z0-9_-]{11}$/.test(url)) {
        return url;
    }

    // YouTube Shorts: youtube.com/shorts/VIDEO_ID or youtu.be/shorts/VIDEO_ID
    const shortsMatch = url.match(/(?:youtube\.com|youtu\.be)\/shorts\/([a-zA-Z0-9_-]{11})/i);
    if (shortsMatch && shortsMatch[1]) {
        return shortsMatch[1];
    }

    // Shortened share links: youtu.be/VIDEO_ID
    const youtuBeMatch = url.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/i);
    if (youtuBeMatch && youtuBeMatch[1]) {
        return youtuBeMatch[1];
    }

    // Live streams: youtube.com/live/VIDEO_ID
    const liveMatch = url.match(/youtube\.com\/live\/([a-zA-Z0-9_-]{11})/i);
    if (liveMatch && liveMatch[1]) {
        return liveMatch[1];
    }

    // Standard watch URL: youtube.com/watch?v=VIDEO_ID
    const watchMatch = url.match(/[?&]v=([a-zA-Z0-9_-]{11})/i);
    if (watchMatch && watchMatch[1]) {
        return watchMatch[1];
    }

    // Embed URLs: youtube.com/embed/VIDEO_ID
    const embedMatch = url.match(/youtube(?:-nocookie)?\.com\/embed\/([a-zA-Z0-9_-]{11})/i);
    if (embedMatch && embedMatch[1]) {
        return embedMatch[1];
    }

    // General fallback regex
    const generalMatch = url.match(/(?:v\/|u\/\w\/|embed\/|watch\?v=|&v=|shorts\/|live\/)([a-zA-Z0-9_-]{11})/i);
    if (generalMatch && generalMatch[1]) {
        return generalMatch[1];
    }

    // Match path ending with 11-char ID
    const pathMatch = url.match(/\/([a-zA-Z0-9_-]{11})(?:[?&#]|$)/);
    if (pathMatch && pathMatch[1]) {
        return pathMatch[1];
    }

    return null;
}

function handleCardImageError(img, category, filename) {
    if (!img) return;
    const retryCount = parseInt(img.dataset.retryCount || '0', 10);
    
    if (retryCount === 0) {
        img.dataset.retryCount = '1';
        if (filename) {
            const currentSrc = img.src || '';
            if (currentSrc.includes('/storage/stocks/')) {
                img.src = '/' + filename;
                return;
            } else if (!currentSrc.includes('/storage/stocks/')) {
                img.src = '/storage/stocks/' + filename;
                return;
            }
        }
    } else if (retryCount === 1) {
        img.dataset.retryCount = '2';
        img.src = '/images/firework-default.png';
        return;
    }
    
    img.style.display = 'none';
    const fallbackIcon = img.parentElement ? img.parentElement.querySelector('.card-fallback-icon') : null;
    if (fallbackIcon) {
        fallbackIcon.classList.remove('hidden');
    }
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
</script>
<!-- JavaScript for Video Modal ends here -->
@endsection 