<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Radhe Crackers - Bringing Joy, Spark by Spark')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #b37a2c;
            --secondary-color: #1e093b;
            --accent-color: #ffca49;
            --text-dark: #222222;
            --text-light: #666666;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, var(--primary-color) 0%, #d4a574 100%);
        }
        
        .firework-animation {
            animation: firework 2s ease-in-out infinite;
        }
        
        @keyframes firework {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        
        .sparkle {
            animation: sparkle 1.5s ease-in-out infinite;
        }
        
        @keyframes sparkle {
            0%, 100% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.2); }
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .category-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .category-card:hover {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #fff 0%, #fff5e6 100%);
        }
        
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .discount-badge {
            background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%);
            color: white;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #d4a574 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #a06a25 0%, #c49563 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(179, 122, 44, 0.3);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-secondary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .header-shadow {
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }
        
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .a:hover
         { 
            color:rgb(182, 113, 33) !important; 
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <!-- Top Bar with Business Info -->
    <div class=" text-white py-2 sm:py-3 shadow" style="background-color:rgb(182, 113, 33);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center text-xs sm:text-sm md:justify-between">
            
            <!-- Left: Business Enquiry -->
            <div class="flex items-center justify-start w-full md:w-1/3 mb-2 md:mb-0">
                <span class="flex items-center">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                    <strong class="hidden sm:inline">Business Enquiry:</strong> 
                    <strong class="sm:hidden">Call:</strong>
                    <a href="tel:+918807060809" class="ml-1">+91 8807060809</a>
                    <span class="hidden sm:inline"> / </span>
                    <a href="tel:+919751048974" class="hidden sm:inline">+91 9751048974</a>
                </span>
            </div>

            <!-- Center: Office Time -->
            <div class="flex items-center justify-center w-full md:w-1/3 mb-2 md:mb-0 text-center">
                <span class="flex items-center justify-center">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <strong>Office Time : </strong>  9am To 5pm
                </span>
            </div>

            <!-- Right: Auth Section -->
            <div class="flex items-center justify-end w-full md:w-1/3 space-x-2 sm:space-x-4 text-right">
                @auth
                <span class="flex items-center">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    <span class="hidden sm:inline">Welcome, {{ auth()->user()->name }}</span>
                    <span class="sm:hidden">{{ auth()->user()->name }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-white hover:text-gray-200 transition-colors font-medium">Logout</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="text-white hover:text-gray-800 transition-colors font-medium">Login</a>
                @endauth
            </div>

        </div>
    </div>
</div>


<div class="bg-white header-shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Logo -->
            <div class="flex items-center flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center" rel="home" aria-label="Radhe Crackers">
                    <img 
                        src="{{ asset('images/logo/2.png') }}"
                        alt="Radhe Crackers"
                        class="h-10 sm:h-12 w-auto max-w-[140px] sm:max-w-[160px] md:max-w-[200px]"
                        style="height:50px"
                    >
                </a>
            </div>

            <!-- Search Bar (hidden on small screens) -->
            <div class="flex-1 max-w-lg mx-auto hidden sm:block">
                <livewire:pages.search />
            </div>

            <!-- Action Buttons and Cart -->
            <div class="flex items-center gap-3 sm:gap-6">
                <!-- Order Now Button -->
                <a href="{{ route('order.form') }}" class="btn-primary text-xs sm:text-sm px-3 py-2 flex items-center">
                    <svg class="w-4 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                    </svg>
                    <span class="hidden sm:inline">Order Now</span>
                    <span class="sm:hidden">Order</span>
                </a>

                <!-- Cart Button -->
                <div class="relative">
                    <button onclick="toggleCartFromHeader()" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        <span class="hidden sm:inline ">My Cart ₹<span id="header-cart-total">0.00</span></span>
                        <span class="sm:hidden">Cart</span>
                        <span id="header-cart-count" class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5 hidden">0</span>
                    </button>
                </div>
            </div>

            <!-- Livewire Cart Component -->
            <livewire:pages.cart />
        </div>
    </div>
</div>

    <!-- Navigation -->
    <nav class=" text-white border-b-2 border-gray-900 shadow-sm"style="background-color: #1E093B;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-12 sm:h-16">
            
            <!-- Left: Shop By Categories -->
            <div class="flex items-center">
                <div class="relative group hidden md:block">
                    <button class="text-white font-medium flex items-center py-4">
                        <svg class="w-4 h-4 mr-2 a" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="hidden lg:inline a">Shop By Categories</span>
                        <span class="lg:hidden a">Categories</span>
                        <svg class="w-4 h-4 ml-1 a" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="absolute left-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-xl z-50 hidden group-hover:block">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-black mb-4">Product Categories</h3>
                            <div class="grid grid-cols-1 gap-2">
                                @php
                                    $categories = [
                                        'BIJILI CRACKERS' => ['icon' => '⚡', 'count' => \App\Models\Stock::active()->where('category', 'BIJILI CRACKERS')->count()],
                                        'BOMBS' => ['icon' => '💣', 'count' => \App\Models\Stock::active()->where('category', 'BOMBS')->count()],
                                        'CHIT PUT' => ['icon' => '🎆', 'count' => \App\Models\Stock::active()->where('category', 'CHIT PUT')->count()],
                                        'GIFT BOX' => ['icon' => '🎁', 'count' => \App\Models\Stock::active()->where('category', 'GIFT BOX')->count()],
                                        'ROCKETS' => ['icon' => '🚀', 'count' => \App\Models\Stock::active()->where('category', 'ROCKETS')->count()],
                                        'SINGLE FLASH' => ['icon' => '⚡', 'count' => \App\Models\Stock::active()->where('category', 'SINGLE FLASH')->count()],
                                        'SPARKLERS' => ['icon' => '✨', 'count' => \App\Models\Stock::active()->where('category', 'SPARKLERS')->count()],
                                        'TWINKLING STAR' => ['icon' => '⭐', 'count' => \App\Models\Stock::active()->where('category', 'TWINKLING STAR')->count()],
                                    ];
                                @endphp
                                @foreach($categories as $category => $data)
                                    <a href="{{ route('shop') }}?category={{ urlencode($category) }}" class="flex items-center justify-between p-3 hover:bg-orange-50 rounded-lg transition-colors group">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-xl group-hover:scale-110 transition-transform">{{ $data['icon'] }}</span>
                                            <span class="text-sm font-medium text-black">{{ $category }}</span>
                                        </div>
                                        <span class="text-xs text-black bg-gray-100 px-2 py-1 rounded-full">{{ $data['count'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Center: Navigation Links -->
            <div class="hidden md:flex items-center space-x-2 lg:space-x-6">
                <a href="{{ route('home') }}" class="a text-white-700 hover:text-white-500 font-medium text-sm lg:text-base">Home</a>
                <a href="{{ route('about') }}" class="a text-white-700 hover:text-white-500 font-medium text-sm lg:text-base hidden lg:inline">About Us</a>
                <a href="{{ route('shop') }}" class="a text-white-700 hover:text-white-500 font-medium text-sm lg:text-base">Buy Crackers</a>
                <a href="{{ route('express-shop') }}" class="a text-white-700 hover:text-white-500 font-medium text-sm lg:text-base hidden xl:inline">Express Shop</a>
                <a href="{{ route('sale-products') }}" class="a text-white-700 hover:text-white-500 font-medium text-sm lg:text-base hidden lg:inline">Sale Products</a>
                <a href="{{ route('price-list') }}" class="a text-white-700 hover:text-white-500 font-medium text-sm lg:text-base hidden xl:inline">Price List</a>
                <a href="{{ route('track-order') }}" class="a text-white-700 hover:text-white-500 font-medium text-sm lg:text-base hidden lg:inline">Order Tracking</a>
                <a href="{{ route('contact') }}" class="a text-white-700 hover:text-white-500 font-medium text-sm lg:text-base hidden lg:inline">Contact Us</a>
            </div>

            <!-- Right: Best Offers -->
            <div class="flex items-center hidden md:block">
                <button onclick="toggleBestOffersPopup()" class="a text-white hover:text-white font-medium text-sm lg:text-base">
                    Best Offers
                </button>
            </div>

            <!-- Mobile Buttons -->
            <div class="md:hidden flex items-center space-x-2">
                <button onclick="toggleBestOffersPopup()" class="text-white hover:text-white p-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </button>
                <button onclick="toggleMobileMenu()" class="text-white-700 hover:text-primary-500 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>



    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu fixed inset-0 bg-black bg-opacity-50 z-50 md:hidden">
        <div class="bg-white w-[280px] sm:w-[320px] h-full overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Menu</h2>
                    <button onclick="toggleMobileMenu()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Mobile Categories -->
                <div class="mb-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Categories</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category => $data)
                            <a href="{{ route('shop') }}?category={{ urlencode($category) }}" class="flex items-center justify-between p-2 sm:p-3 hover:bg-orange-50 rounded-lg transition-colors">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <span class="text-base sm:text-lg">{{ $data['icon'] }}</span>
                                    <span class="text-xs sm:text-sm font-medium text-gray-900">{{ $category }}</span>
                                </div>
                                <span class="text-xs text-gray-500">({{ $data['count'] }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <!-- Mobile Navigation Links -->
                <div class="space-y-2">
                    <a href="{{ route('home') }}" class="block p-2 sm:p-3 text-gray-700 hover:bg-orange-50 rounded-lg transition-colors text-sm sm:text-base">Home</a>
                    <a href="{{ route('about') }}" class="block p-2 sm:p-3 text-gray-700 hover:bg-orange-50 rounded-lg transition-colors text-sm sm:text-base">About Us</a>
                    <a href="{{ route('shop') }}" class="block p-2 sm:p-3 text-gray-700 hover:bg-orange-50 rounded-lg transition-colors text-sm sm:text-base">Buy Crackers</a>
                    <a href="{{ route('express-shop') }}" class="block p-2 sm:p-3 text-gray-700 hover:bg-orange-50 rounded-lg transition-colors text-sm sm:text-base">Express Shop</a>
                    <a href="{{ route('sale-products') }}" class="block p-2 sm:p-3 text-gray-700 hover:bg-orange-50 rounded-lg transition-colors text-sm sm:text-base">Sale Products</a>
                    <a href="{{ route('price-list') }}" class="block p-2 sm:p-3 text-gray-700 hover:bg-orange-50 rounded-lg transition-colors text-sm sm:text-base">Price List</a>
                    <a href="{{ route('track-order') }}" class="block p-2 sm:p-3 text-gray-700 hover:bg-orange-50 rounded-lg transition-colors text-sm sm:text-base">Order Tracking</a>
                    <a href="{{ route('contact') }}" class="block p-2 sm:p-3 text-gray-700 hover:bg-orange-50 rounded-lg transition-colors text-sm sm:text-base">Contact Us</a>
                    <button onclick="toggleBestOffersPopup()" class="block w-full text-left p-2 sm:p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors text-sm sm:text-base">Best Offers</button>
                </div>
                
                <!-- Mobile User Menu -->
                
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class=" text-black">
        <!-- Footer Content -->
        <div class="py-16">
            <div class="max-w-6xl mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Company Info as Image Module -->
                    <div class="col-span-1 md:col-span-2 flex flex-col items-center md:items-start">
                        <div class="p-8 w-full flex flex-col items-center md:items-start ">
                        
    <a href="{{ route('home') }}" class="flex items-center pb-3" rel="home" aria-label="Radhe Crackers">
        <img 
            src="{{ asset('images/logo/1.png') }}" class="custom-logo h-10 sm:h-10 md:h-12 w-auto max-w-[120px] sm:max-w-[150px] md:max-w-[200px] lg:max-w-none"
            alt="Radhe Crackers" style="height:80px"
           
             >
                </a>
                            <p class="text-center md:text-left mb-0 mt-2 text-sm md:text-base">
                                Your trusted fireworks destination in Sivakasi, the firecracker capital of India. 
                                We offer the finest quality fireworks at competitive prices, backed by our commitment 
                                to safety and customer satisfaction.
                            </p>
                            <div class="flex space-x-4 mt-6 md:mt-8">
                            <a href="#" class="a text-gray-900  ">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            
                            <a href="#" class="a text-gray-900 ">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                                </svg>
                            </a>
                        </div>
                        </div>
                        
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-6">Quick Links</h3>
                        <ul class="space-y-3">
                            <li><a href="{{ route('home') }}" class="a text-black-300 hover:text-orange-400 transition-colors">Home</a></li>
                            <li><a href="{{ route('about') }}" class="a text-black-300 hover:text-orange-400 transition-colors">About Us</a></li>
                            <li><a href="{{ route('shop') }}" class="a text-black-300 hover:text-orange-400 transition-colors">Crackers</a></li>
                            <li><a href="{{ route('express-shop') }}" class="a text-black-300 hover:text-orange-400 transition-colors">Express Shop</a></li>
                            <li><a href="{{ route('track-order') }}" class="a text-black-300 hover:text-orange-400 transition-colors">Order Tracking</a></li>
                            <li><a href="{{ route('contact') }}" class="a text-black-300 hover:text-orange-400 transition-colors">Contact Us</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-6">Contact Info</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <svg class="a w-6 h-6 text-gray-700 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-black-300 text-sm">3/180-5, Virudhunagar-Sivakasi main road,</p>
                                    <p class="text-black-300 text-sm">G.N. Patti, Amathur - 626005.</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="a w-6 h-6 text-gray-700 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                <div>
                                    <a href="tel:+918807060809" class="text-black-300 hover:text-black-400 transition-colors text-sm">+91 8807060809</a><br>
                                    <a href="tel:+919751048974" class="text-black-300 hover:text-black-400 transition-colors text-sm">+91 9751048974</a>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="a w-6 h-6 text-gray-700 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <a href="mailto:radhecrackers@gmail.com" class=" a text-black-300 hover:text-orange-400 transition-colors text-sm">radhecrackers@gmail.com</a>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="a w-6 h-6 text-gray-700 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-black-300 text-sm">Office Time: 9am To 5pm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-800 py-6">
            <div class="max-w-6xl mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-black-400 text-sm">
                        Copyrights © {{ date('Y') }}. Radhe Crackers. All Rights Reserved.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="a text-black-400 hover:text-orange-400 transition-colors text-sm">Privacy Policy</a>
                        <a href="#" class="a text-black-400 hover:text-orange-400 transition-colors text-sm">Terms of Service</a>
                        <a href="#" class="a text-black-400 hover:text-orange-400 transition-colors text-sm">Shipping Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>



    <!-- Best Offers Popup -->
    <div id="bestOffersPopup" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex justify-end h-full">
            <div class="bg-white w-full max-w-[280px] sm:max-w-[320px] md:max-w-md h-full transform translate-x-full transition-transform duration-300" id="popupContent">
                <div class="p-4 sm:p-6 h-full flex flex-col">
                    <div class="flex justify-between items-center mb-4 sm:mb-6">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Best Offers</h2>
                        <button onclick="toggleBestOffersPopup()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <livewire:components.best-offers />
                </div>
            </div>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Custom JavaScript -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('open');
        }
        
        function toggleBestOffersPopup() {
            const popup = document.getElementById('bestOffersPopup');
            const content = document.getElementById('popupContent');
            
            if (popup.classList.contains('hidden')) {
                popup.classList.remove('hidden');
                setTimeout(() => {
                    content.style.transform = 'translateX(0)';
                }, 10);
            } else {
                content.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    popup.classList.add('hidden');
                }, 300);
            }
        }
        
        function toggleCartFromHeader() {
            // Find the cart component and trigger its toggle method
            const cartComponent = document.querySelector('[wire\\:id*="pages.cart"]');
            if (cartComponent) {
                const wireId = cartComponent.getAttribute('wire:id');
                if (wireId && Livewire.find(wireId)) {
                    // Use Livewire's call method to trigger the toggleCart method
                    Livewire.find(wireId).call('toggleCart');
                } else {
                    // Fallback: try to find any cart component
                    const allCartComponents = document.querySelectorAll('[wire\\:id*="cart"]');
                    if (allCartComponents.length > 0) {
                        const firstCart = allCartComponents[0];
                        const firstWireId = firstCart.getAttribute('wire:id');
                        if (firstWireId && Livewire.find(firstWireId)) {
                            Livewire.find(firstWireId).call('toggleCart');
                        }
                    }
                }
            } else {
                // Alternative approach: try to click the floating cart button
                const floatingCartButton = document.querySelector('[wire\\:click="toggleCart"]');
                if (floatingCartButton) {
                    floatingCartButton.click();
                } else {
                    console.log('Cart component not found');
                }
            }
        }
        
        // Function to update header cart display
        function updateHeaderCart(total, itemCount) {
            const totalElement = document.getElementById('header-cart-total');
            const countElement = document.getElementById('header-cart-count');
            
            if (totalElement) {
                totalElement.textContent = parseFloat(total).toFixed(2);
            }
            
            if (countElement) {
                countElement.textContent = itemCount;
                countElement.style.display = itemCount > 0 ? 'inline-block' : 'none';
            }
        }
        
        // Listen for cart updates from Livewire
        document.addEventListener('livewire:load', function () {
            // Listen for cart update events
            Livewire.on('cartUpdated', (data) => {
                updateHeaderCart(data.total, data.itemCount);
            });
            
            // Also update on component updates
            Livewire.hook('message.processed', (message, component) => {
                if (component.fingerprint.name === 'pages.cart') {
                    // Update header cart when cart component updates
                    const total = component.get('total') || 0;
                    const itemCount = component.get('itemCount') || 0;
                    updateHeaderCart(total, itemCount);
                }
            });
            
            // Initial cart load - update header after a short delay
            setTimeout(() => {
                const cartComponents = document.querySelectorAll('[wire\\:id*="pages.cart"]');
                if (cartComponents.length > 0) {
                    const cartComponent = cartComponents[0];
                    const wireId = cartComponent.getAttribute('wire:id');
                    if (wireId && Livewire.find(wireId)) {
                        const component = Livewire.find(wireId);
                        const total = component.get('total') || 0;
                        const itemCount = component.get('itemCount') || 0;
                        updateHeaderCart(total, itemCount);
                    }
                }
            }, 500);
        });
        
        // Close mobile menu when clicking outside
        document.getElementById('mobileMenu').addEventListener('click', function(e) {
            if (e.target === this) {
                toggleMobileMenu();
            }
        });
        
        // Close best offers popup when clicking outside
        document.getElementById('bestOffersPopup').addEventListener('click', function(e) {
            if (e.target === this) {
                toggleBestOffersPopup();
            }
        });
    </script>
</body>
</html> 