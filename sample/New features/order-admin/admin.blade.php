<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Radhe Crackers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 transform transition-transform duration-300 ease-in-out" style="background-color: #1E093B;">
            
            <!-- Logo -->
    <div class="bg-white flex px-4 py-2items-center p-2">
    <a href="{{ route('home') }}" class="flex items-center space" rel="home" aria-label="Radhe Crackers">
        <img 
           
            src="{{ asset('images/logo/2.png') }}" class="px-5 custom-logo h-8 sm:h-10 md:h-12 w-auto max-w-[120px] sm:max-w-[150px] md:max-w-[200px] lg:max-w-none"
            alt="Radhe Crackers" style="height:50px"
           
             >
    </a>
</div>


            <!-- Navigation -->
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.orders') }}" 
                       class="flex items-center px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.orders*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                        Orders
                    </a>

                    <a href="{{ route('admin.payments') }}" 
                       class="flex items-center px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.payments*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-credit-card w-5 h-5 mr-3"></i>
                        Payments
                    </a>

                    <a href="{{ route('admin.stocks') }}" 
                       class="flex items-center px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.stocks') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-boxes w-5 h-5 mr-3"></i>
                        Stock Management
                    </a>

                    <a href="{{ route('admin.stocks.logs') }}" 
                       class="flex items-center px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.stocks.logs*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                        Stock Logs
                    </a>

                    <a href="{{ route('admin.users') }}" 
                       class="flex items-center px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.users*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-users w-5 h-5 mr-3"></i>
                        Users
                    </a>

                    <a href="{{ route('admin.pdf-manager') }}" 
                       class="flex items-center px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.pdf-manager*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-file-pdf w-5 h-5 mr-3"></i>
                        PDF Manager
                    </a>

                    <a href="{{ route('admin.whatsapp-links') }}" 
                       class="flex items-center px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.whatsapp-links*') ? 'bg-gray-700' : '' }}">
                        <i class="fab fa-whatsapp w-5 h-5 mr-3"></i>
                        WhatsApp Links
                    </a>

                    <a href="{{ route('admin.settings') }}" 
                       class="flex items-center px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.settings*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-cog w-5 h-5 mr-3"></i>
                        Settings
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="lg:ml-64">
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-4 py-3">
                    <!-- Mobile menu button -->
                    <button id="mobileMenuBtn" class="lg:hidden">
                        <i class="fas fa-bars text-gray-600 text-xl"></i>
                    </button>

                    <!-- Page Title -->
                    <div class="flex-1 lg:ml-4">
                        <h1 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-gray-400"></span>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative" id="userDropdown">
                            <button id="userMenuBtn" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                
                                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-home mr-2"></i>
                                    View Website
                                </a>
                                
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('warning') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ session('info') }}
                    </div>
                @endif

                {{-- Livewire page content slot or Blade section --}}
                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>

        <!-- Mobile Overlay -->
        <div id="mobileOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>
    </div>

    @livewireScripts
    <script>
        // Simple JavaScript for sidebar and dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userMenu = document.getElementById('userMenu');

            // Mobile menu toggle
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                mobileOverlay.classList.toggle('hidden');
            });

            // Close sidebar when clicking overlay
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                mobileOverlay.classList.add('hidden');
            });

            // User dropdown toggle
            userMenuBtn.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            });

            // Close user dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!userDropdown.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });

            // Show sidebar on desktop
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    mobileOverlay.classList.add('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html> 