@extends('layouts.app')

@section('title', 'Smart Checkout - Radhe Crackers')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Smart Checkout</h1>
        <p class="text-gray-600">Complete your order with real-time validation and smart discounts</p>
    </div>

    <!-- Progress Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-center max-w-lg mx-auto px-4">
            <div class="flex items-center flex-shrink-0">
                <div class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center text-sm font-bold">1</div>
                <span class="ml-2 text-sm font-medium text-blue-600 hidden sm:inline-block">Cart Review</span>
            </div>
            <div class="flex-grow border-t-2 border-gray-300 mx-2 sm:mx-4"></div>
            <div class="flex items-center flex-shrink-0">
                <div class="bg-gray-300 text-gray-600 rounded-full h-8 w-8 flex items-center justify-center text-sm font-bold">2</div>
                <span class="ml-2 text-sm font-medium text-gray-500 hidden sm:inline-block">Customer Details</span>
            </div>
            <div class="flex-grow border-t-2 border-gray-300 mx-2 sm:mx-4"></div>
            <div class="flex items-center flex-shrink-0">
                <div class="bg-gray-300 text-gray-600 rounded-full h-8 w-8 flex items-center justify-center text-sm font-bold">3</div>
                <span class="ml-2 text-sm font-medium text-gray-500 hidden sm:inline-block">Payment & Confirm</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Cart & Coupon -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Cart Summary -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    Cart Summary
                </h2>
                
                <div id="cart-items-container" class="space-y-3">
                    <!-- Cart items will be populated here -->
                </div>
                
                <div class="border-t pt-4 mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">Subtotal:</span>
                        <span id="cart-subtotal">₹0.00</span>
                    </div>
                </div>
            </div>

            <!-- Smart Coupon System -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    Smart Discounts
                </h2>
                
                <!-- Coupon Input -->
                <div class="flex space-x-2 mb-4">
                    <input type="text" id="coupon-code" class="flex-1 min-w-0 border border-gray-300 rounded-lg px-3 py-2 sm:px-4 sm:py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm sm:text-base" placeholder="Enter coupon code">
                    <button type="button" id="apply-coupon" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg font-medium transition duration-200 text-sm sm:text-base flex-shrink-0">
                        Apply
                    </button>
                </div>
                
                <!-- Coupon Status -->
                <div id="coupon-status" class="hidden">
                    <!-- Success/Error messages will appear here -->
                </div>
                
                <!-- Available Coupons -->
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Available Coupons:</h3>
                    <div id="available-coupons" class="space-y-2">
                        <!-- Available coupons will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Lucky Spinning Wheel (Unlocked for orders above 5k) -->
            <div id="lucky-wheel-card" class="bg-gradient-to-br from-[#1E093B] via-[#2D0B5A] to-[#170529] rounded-2xl shadow-xl overflow-hidden text-white border-2 border-amber-400/40 relative">
                <!-- Festive Header -->
                <div class="px-5 py-4 bg-black/30 backdrop-blur-sm border-b border-amber-400/20 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <span class="text-3xl animate-bounce">🎡</span>
                        <div>
                            <h3 class="text-lg font-black tracking-wide text-amber-300 flex items-center gap-2">
                                LUCKY SPINNING WHEEL
                                <span class="text-[10px] uppercase font-extrabold bg-gradient-to-r from-amber-400 to-yellow-300 text-purple-950 px-2.5 py-0.5 rounded-full shadow">
                                    Diwali Bonanza
                                </span>
                            </h3>
                            <p class="text-xs text-purple-200">Unlock for orders above ₹5,000 & Win Free Crackers or 5% Discount!</p>
                        </div>
                    </div>
                    <div id="wheel-status-pill" class="text-xs font-bold px-3 py-1 rounded-full bg-purple-900/80 border border-purple-400/40 text-purple-200 flex items-center gap-1.5">
                        <span id="wheel-status-dot" class="w-2 h-2 rounded-full bg-yellow-400"></span>
                        <span id="wheel-status-text">Checking Eligibility...</span>
                    </div>
                </div>

                <!-- Revoked Lucky Spin Banner (when total drops below 5k) -->
                <div id="wheel-revoked-banner" class="hidden p-4 bg-gradient-to-r from-red-950/95 via-purple-950/90 to-red-950/95 border-b border-red-500/40 text-red-200 transition-all duration-300">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="text-2xl animate-pulse flex-shrink-0">⚠️</span>
                            <div>
                                <h4 class="font-extrabold text-sm text-red-300">Lucky Spin Reward Removed</h4>
                                <p class="text-xs text-red-200 mt-0.5">
                                    Your order total dropped below ₹5,000. Free spin cracker/discount was automatically removed. Add <strong class="text-yellow-300">₹<span id="revoked-rem-amt">0.00</span></strong> more to spin again!
                                </p>
                            </div>
                        </div>
                        <button type="button" onclick="document.getElementById('wheel-revoked-banner').classList.add('hidden')" class="text-red-400 hover:text-white text-lg font-bold px-2 py-1 flex-shrink-0" title="Dismiss">&times;</button>
                    </div>
                </div>

                <!-- Locked Teaser Banner (when < 5k) -->
                <div id="wheel-locked-banner" class="p-5 bg-purple-950/60 border-b border-amber-500/20">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-amber-500/20 border border-amber-400/40 flex items-center justify-center text-2xl flex-shrink-0">
                                🔒
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-amber-300">Unlock the Wheel for Normal Crackers Above ₹5,000!</h4>
                                <p class="text-xs text-purple-200 mt-0.5">
                                    Normal crackers total: <strong class="text-white" id="wheel-current-amount">₹0.00</strong>. 
                                    Add <strong class="text-yellow-300" id="wheel-remaining-amount">₹5,000.00</strong> more of normal crackers to unlock your free spin!
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('shop') }}" class="flex-shrink-0 px-4 py-2 bg-gradient-to-r from-amber-500 to-yellow-400 hover:from-amber-600 hover:to-yellow-500 text-purple-950 font-black text-xs rounded-xl shadow-lg transition-all duration-200 hover:scale-105 flex items-center gap-1.5">
                            <span>➕ Add More Crackers</span>
                        </a>
                    </div>
                    <!-- Progress Bar -->
                    <div class="mt-3.5">
                        <div class="flex justify-between text-[11px] text-purple-300 font-semibold mb-1">
                            <span>Progress to Unlock:</span>
                            <span id="wheel-progress-pct" class="text-amber-300">0%</span>
                        </div>
                        <div class="w-full h-2.5 bg-purple-900/90 rounded-full overflow-hidden border border-purple-700/50">
                            <div id="wheel-progress-bar" class="h-full bg-gradient-to-r from-amber-500 via-yellow-400 to-amber-300 rounded-full transition-all duration-500" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Unlocked Celebration Banner (when >= 5k) -->
                <div id="wheel-unlocked-banner" class="hidden p-4 bg-gradient-to-r from-emerald-900/70 to-purple-900/70 border-b border-emerald-400/30 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="text-2xl">🎉</span>
                        <div>
                            <h4 class="font-extrabold text-sm text-emerald-300">Congratulations! Lucky Wheel is Unlocked!</h4>
                            <p class="text-xs text-emerald-100">You qualify for 1 Free Spin. Spin the wheel to claim your prize!</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 bg-emerald-400 text-emerald-950 text-[11px] font-black rounded-lg uppercase tracking-wider animate-pulse">
                        Ready to Spin
                    </span>
                </div>

                <!-- Already Spun Banner -->
                <div id="wheel-won-banner" class="hidden p-4 bg-gradient-to-r from-amber-900/80 to-purple-950 border-b border-amber-400/40 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl" id="won-banner-emoji">🎁</span>
                        <div>
                            <h4 class="font-extrabold text-sm text-amber-300">Prize Claimed: <span id="won-prize-name-text">25 Raider</span></h4>
                            <p class="text-xs text-amber-100" id="won-prize-desc-text">This prize has been automatically applied to your order!</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-amber-400 text-purple-950 text-xs font-black rounded-full shadow">
                        Applied ✓
                    </span>
                </div>

                <!-- Wheel Arena -->
                <div class="p-6 flex flex-col lg:flex-row items-center justify-around gap-6 relative">
                    <!-- Canvas Wheel Area -->
                    <div class="relative flex flex-col items-center">
                        <!-- Sleek Top Pointer Needle -->
                        <div class="absolute -top-3 z-30 flex flex-col items-center pointer-events-none drop-shadow-xl" style="left: 50%; transform: translateX(-50%);">
                            <div class="w-0 h-0 border-l-[14px] border-l-transparent border-r-[14px] border-r-transparent border-t-[26px] border-t-amber-400 filter drop-shadow"></div>
                            <div class="w-3.5 h-3.5 bg-yellow-300 rounded-full -mt-2 border-2 border-purple-950"></div>
                        </div>

                        <!-- Canvas Wheel Container -->
                        <div class="relative p-2 rounded-full bg-gradient-to-tr from-amber-500 via-purple-700 to-yellow-400 shadow-2xl">
                            <canvas id="lucky-wheel-canvas" width="330" height="330" class="rounded-full shadow-inner block"></canvas>
                            
                            <!-- Center Spin Hub Button -->
                            <button type="button" id="wheel-center-btn" class="absolute inset-0 m-auto w-20 h-20 rounded-full bg-gradient-to-b from-yellow-300 via-amber-400 to-yellow-500 text-purple-950 font-black text-xs uppercase tracking-wider flex flex-col items-center justify-center shadow-2xl border-4 border-purple-950 hover:scale-105 active:scale-95 transition-transform duration-200 cursor-pointer z-20">
                                <span class="text-base">🎯</span>
                                <span id="center-btn-label" class="text-[11px] font-black leading-tight">SPIN</span>
                            </button>

                            <!-- Glassmorphism Overlay when locked -->
                            <div id="wheel-locked-overlay" class="absolute inset-0 rounded-full bg-purple-950/80 backdrop-blur-[2px] flex flex-col items-center justify-center text-center p-4 z-20">
                                <span class="text-4xl mb-1">🔒</span>
                                <span class="font-extrabold text-xs text-amber-300 uppercase tracking-wider">Locked</span>
                                <span class="text-[10px] text-purple-200 mt-0.5">Normal Crackers Above ₹5,000</span>
                            </div>
                        </div>

                        <!-- Spin CTA Button under wheel -->
                        <div class="mt-4 text-center">
                            <button type="button" id="wheel-action-btn" class="px-8 py-3 bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 hover:from-amber-500 hover:to-yellow-500 text-purple-950 font-extrabold text-sm rounded-xl shadow-lg transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-2 mx-auto">
                                <span class="text-lg">🎡</span>
                                <span id="wheel-action-btn-text">SPIN TO WIN</span>
                            </button>
                        </div>
                    </div>

                    <!-- 5 Prizes Showcase List -->
                    <div class="w-full lg:w-64 space-y-2">
                        <h5 class="text-xs font-black uppercase tracking-wider text-amber-300 pb-1 border-b border-amber-500/30 flex items-center justify-between">
                            <span>🎁 5 EXCITING PRIZES</span>
                            <span class="text-[10px] text-purple-300 font-normal">Your Luck</span>
                        </h5>

                        <!-- Prize 1: 25 Raider -->
                        <div id="prize-card-1" class="p-2 rounded-xl bg-white/5 border border-white/10 hover:border-amber-400/40 transition-all flex items-center gap-2.5">
                            <img src="/storage/stocks/716pk6nQfo6s9RIh72gZ114WSakDaBRACNY87Pw2.jpg" alt="25 Raider" class="w-10 h-10 object-cover rounded-lg border border-amber-400/40 bg-black/40 flex-shrink-0" onerror="this.src='/images/firework-default.png'">
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-white truncate">25 Radhe Raider</div>
                                <div class="text-[11px] font-extrabold text-amber-300">Worth ₹220 (FREE Gift)</div>
                            </div>
                        </div>

                        <!-- Prize 2: 5% Discount -->
                        <div id="prize-card-2" class="p-2 rounded-xl bg-white/5 border border-white/10 hover:border-amber-400/40 transition-all flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-lg bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center text-lg text-emerald-300 flex-shrink-0 font-black">
                                5%
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-white truncate">5% Extra Discount</div>
                                <div class="text-[11px] font-extrabold text-emerald-300">Instant off total order</div>
                            </div>
                        </div>

                        <!-- Prize 3: 30 Shot Regular -->
                        <div id="prize-card-3" class="p-2 rounded-xl bg-white/5 border border-white/10 hover:border-amber-400/40 transition-all flex items-center gap-2.5">
                            <img src="/storage/stocks/ZMW1truK7lG7qqJ6xcMjArWB6bo8Fm1douNjHrud.jpg" alt="30 Shot Regular" class="w-10 h-10 object-cover rounded-lg border border-amber-400/40 bg-black/40 flex-shrink-0" onerror="this.src='/images/firework-default.png'">
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-white truncate">30 Shot Regular</div>
                                <div class="text-[11px] font-extrabold text-purple-300">Worth ₹390 (FREE Gift)</div>
                            </div>
                        </div>

                        <!-- Prize 4: 6 Inch Tin Shower -->
                        <div id="prize-card-4" class="p-2 rounded-xl bg-white/5 border border-white/10 hover:border-amber-400/40 transition-all flex items-center gap-2.5">
                            <img src="/storage/stocks/yxg0ReFPwjqJXFGS4wpScuToCMhOKoTyZbu4tQSL.jpg" alt="6 Inch Tin Shower" class="w-10 h-10 object-cover rounded-lg border border-amber-400/40 bg-black/40 flex-shrink-0" onerror="this.src='/images/firework-default.png'">
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-white truncate">6 Inch Tin Shower</div>
                                <div class="text-[11px] font-extrabold text-rose-300">Worth ₹200 (FREE Gift)</div>
                            </div>
                        </div>

                        <!-- Prize 0: Better Luck Next Time -->
                        <div id="prize-card-0" class="p-2 rounded-xl bg-white/5 border border-white/10 hover:border-amber-400/40 transition-all flex items-center gap-2.5 opacity-85">
                            <div class="w-10 h-10 rounded-lg bg-purple-500/20 border border-purple-400/40 flex items-center justify-center text-lg text-purple-300 flex-shrink-0">
                                🍀
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-white truncate">Better Luck Next Time</div>
                                <div class="text-[11px] text-gray-300">Diwali Warm Wishes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information Form -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Customer Information
                </h2>
                
                <form id="customer-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="customer_name" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number *</label>
                            <input type="tel" name="customer_mobile" required maxlength="10" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="customer_email" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                            <select name="customer_state" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select State</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Kerala">Kerala</option>
                                <option value="Karnataka">Karnataka</option>
                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                <option value="Telangana">Telangana</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">District *</label>
                            <input type="text" name="customer_district" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                            <input type="text" name="customer_city" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Point *</label>
                            <input type="text" name="delivery_point" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pin Code *</label>
                            <input type="text" name="pin_code" required maxlength="6" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 sticky top-4">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Order Summary
                </h2>
                
                <div class="space-y-2.5 text-xs sm:text-sm">
                    <!-- 1. SubTotal (MRP) -->
                    <div class="flex justify-between text-gray-700">
                        <span class="font-medium">SubTotal:</span>
                        <span id="order-value" class="font-bold text-gray-900">₹0.00</span>
                    </div>

                    <!-- 2. Discount (70%) -->
                    <div class="flex justify-between text-emerald-700 font-medium">
                        <span>Discount (70%):</span>
                        <span id="discount-70">-₹0.00</span>
                    </div>

                    <!-- 3. After Discount -->
                    <div class="flex justify-between text-gray-700 border-t border-dashed border-gray-200 pt-1">
                        <span>After Discount:</span>
                        <span id="after-discount-70" class="font-semibold text-gray-800">₹0.00</span>
                    </div>

                    <!-- 4. Spl Discount (15%) -->
                    <div class="flex justify-between text-emerald-700 font-medium">
                        <span>Spl Discount (15%):</span>
                        <span id="discount-15">-₹0.00</span>
                    </div>

                    <!-- 5. After Spl. Discount -->
                    <div class="flex justify-between text-gray-700 border-t border-dashed border-gray-200 pt-1">
                        <span>After Spl. Discount:</span>
                        <span id="after-discount-15" class="font-semibold text-gray-800">₹0.00</span>
                    </div>

                    <!-- 6. Coupon Discount -->
                    <div class="flex justify-between text-purple-700 font-medium">
                        <span>Coupon Discount:</span>
                        <span id="coupon-discount">-₹0.00</span>
                    </div>

                    <!-- 7. After Coupon Discount -->
                    <div class="flex justify-between text-gray-700 border-t border-dashed border-gray-200 pt-1">
                        <span>After Coupon Discount:</span>
                        <span id="after-coupon-discount" class="font-semibold text-gray-800">₹0.00</span>
                    </div>

                    <!-- 8. Net rate Items / Combo -->
                    <div id="combo-subtotal-row" class="flex justify-between text-amber-900 bg-amber-50/80 px-2.5 py-1 rounded-lg border border-amber-200 font-bold">
                        <span>Net rate Items / Combo:</span>
                        <span id="combo-subtotal">₹0.00</span>
                    </div>

                    <!-- Lucky Spin rows if any -->
                    <div id="lucky-spin-discount-row" class="hidden flex justify-between text-amber-700 font-bold bg-amber-50 px-2 py-1 rounded-lg border border-amber-200">
                        <span>🎡 Lucky Spin (5% Disc):</span>
                        <span id="lucky-spin-discount">-₹0.00</span>
                    </div>
                    <div id="lucky-spin-gift-row" class="hidden flex justify-between items-center text-emerald-700 font-bold bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-200">
                        <span>🎡 Free Lucky Gift:</span>
                        <span id="lucky-spin-gift-name" class="text-xs bg-emerald-200/80 text-emerald-900 px-2 py-0.5 rounded-full font-bold"></span>
                    </div>

                    <!-- 9. T. Amt (Total Amount before packing) -->
                    <div class="flex justify-between text-gray-900 font-bold border-t border-gray-300 pt-2 text-sm">
                        <span>T. Amt:</span>
                        <span id="total-before-packing">₹0.00</span>
                    </div>

                    <!-- 10. Add packing 5% -->
                    <div class="flex justify-between items-center text-orange-700 font-medium" id="packing-charge-row">
                        <span id="packing-charge-label">Add packing 5%:</span>
                        <span id="packing-charge" class="font-bold">₹0.00</span>
                    </div>

                    <hr class="border-gray-300 my-1">

                    <!-- 11. Net Amt / Payable Amt -->
                    <div class="flex justify-between items-center text-base sm:text-lg font-black text-gray-900 bg-gray-50 p-2.5 rounded-xl border border-gray-200">
                        <span>Net Amt / Payable Amt:</span>
                        <span id="final-total" class="text-[#1E093B] text-xl sm:text-2xl">₹0.00</span>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-6 space-y-3">
                    <button type="button" id="place-order-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="btn-text">Place Order</span>
                        <span id="btn-loading" class="hidden">Processing...</span>
                    </button>
                    
                    <a href="{{ route('shop') }}" class="block w-full text-center bg-white border border-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg hover:bg-gray-50 transition duration-200">
                        Continue Shopping
                    </a>
                </div>
                
                <!-- Security Notice -->
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center text-sm text-blue-800">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        Secure checkout with SSL encryption
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Winner Modal Dialog -->
<div id="lucky-winner-modal" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-gradient-to-b from-[#1E093B] via-[#2A084E] to-[#120224] border-2 border-amber-400 rounded-3xl max-w-md w-full p-6 text-center text-white relative shadow-2xl overflow-hidden animate-scale-up">
        <div class="relative z-10">
            <div class="w-20 h-20 mx-auto rounded-full bg-amber-400/20 border-2 border-amber-400 flex items-center justify-center text-4xl mb-3 shadow-lg" id="modal-prize-icon-wrap">
                <span id="modal-prize-emoji">🎉</span>
            </div>
            <span class="inline-block px-3 py-1 bg-amber-400 text-purple-950 font-black text-xs uppercase tracking-widest rounded-full shadow mb-2">
                Lucky Spin Result
            </span>
            <h3 class="text-2xl font-black text-amber-300" id="modal-prize-title">You Won!</h3>
            <p class="text-xs text-purple-200 mt-1" id="modal-prize-subtitle">Congratulations on your reward</p>

            <div class="my-5 p-4 rounded-2xl bg-black/40 border border-amber-400/30 flex items-center justify-center gap-4 text-left" id="modal-prize-preview-box">
                <img id="modal-prize-img" src="" alt="Prize" class="w-16 h-16 object-cover rounded-xl border border-amber-400/50 shadow hidden">
                <div>
                    <h4 class="font-extrabold text-base text-white" id="modal-prize-name">Prize Name</h4>
                    <p class="text-xs font-bold text-amber-300 mt-0.5" id="modal-prize-val">Worth ₹XXX</p>
                    <p class="text-[11px] text-emerald-400 font-semibold mt-1" id="modal-prize-status">✓ Automatically added to your bill</p>
                </div>
            </div>

            <button type="button" id="modal-claim-btn" class="w-full py-3 bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 hover:from-amber-500 hover:to-yellow-500 text-purple-950 font-black text-sm rounded-xl shadow-lg transition-transform hover:scale-105 active:scale-95">
                Awesome! Continue to Order
            </button>
        </div>
    </div>
</div>

<!-- Hidden form for submission -->
<form id="order-submission-form" method="POST" action="{{ route('smart-checkout.submit') }}" class="hidden">
    @csrf
    <input type="hidden" name="items" id="order-items-json">
    <input type="hidden" name="coupon_code" id="order-coupon-code">
    <input type="hidden" name="coupon_discount" id="order-coupon-discount">
    <input type="hidden" name="lucky_spin_prize" id="order-lucky-spin-prize">
    <input type="hidden" name="lucky_spin_discount" id="order-lucky-spin-discount">
    <input type="hidden" name="total" id="order-total">
    <input type="hidden" name="clear_cart" value="true">
</form>

<script>
// Smart Checkout JavaScript
class SmartCheckout {
    constructor() {
        this.cartItems = [];
        this.couponData = null;
        this.orderValue = 0;
        this.finalTotal = 0;
        this.qualifyingAmount = 0;
        this.isProcessing = false;

        // Lucky Wheel Properties
        this.luckySpinPrize = null;
        this.luckySpinDiscount = 0;
        this.hasSpunWheel = false;
        this.isSpinning = false;
        this.currentWheelRotation = 0;
        this.wheelCanvas = null;
        this.wheelCtx = null;
        this.loadedWheelImages = {};

        // 5 Wheel Prizes:
        // 1. Better Luck Next Time
        // 2. 25 Raider worth 220
        // 3. 5% Discount
        // 4. 30 Shot Regular worth 390
        // 5. 6 Inch Tin Shower worth 200
        this.wheelPrizes = [
            {
                id: 0,
                name: "Better Luck Next Time",
                worthText: "Diwali Wishes",
                type: "none",
                color: "#4A154B",
                textColor: "#FFFFFF",
                accent: "#702459",
                icon: "🍀",
                image: null
            },
            {
                id: 1,
                name: "25 Raider",
                fullName: "25 Radhe Raider",
                worthText: "₹220 FREE",
                type: "product",
                productId: 1903,
                originalPrice: 220,
                color: "#D97706",
                textColor: "#FFFFFF",
                accent: "#F59E0B",
                icon: "🎆",
                image: "/storage/stocks/716pk6nQfo6s9RIh72gZ114WSakDaBRACNY87Pw2.jpg"
            },
            {
                id: 2,
                name: "5% Discount",
                worthText: "5% OFF Total",
                type: "discount",
                color: "#059669",
                textColor: "#FFFFFF",
                accent: "#10B981",
                icon: "🏷️",
                image: null
            },
            {
                id: 3,
                name: "30 Shot Regular",
                fullName: "30 Shot Regular",
                worthText: "₹390 FREE",
                type: "product",
                productId: 1905,
                originalPrice: 390,
                color: "#7C3AED",
                textColor: "#FFFFFF",
                accent: "#8B5CF6",
                icon: "💥",
                image: "/storage/stocks/ZMW1truK7lG7qqJ6xcMjArWB6bo8Fm1douNjHrud.jpg"
            },
            {
                id: 4,
                name: "6 Inch Tin Shower",
                fullName: "6 Inch Tin Shower",
                worthText: "₹200 FREE",
                type: "product",
                productId: 1862,
                originalPrice: 200,
                color: "#DC2626",
                textColor: "#FFFFFF",
                accent: "#EF4444",
                icon: "✨",
                image: "/storage/stocks/yxg0ReFPwjqJXFGS4wpScuToCMhOKoTyZbu4tQSL.jpg"
            }
        ];
        
        // Clear any previously stored data on page load
        this.clearPreviousSessionData();
        
        this.initializeEventListeners();
        this.loadCart();
        this.initLuckyWheel();
        this.updateDisplay();
    }
    
    clearPreviousSessionData() {
        // Clear any previously stored coupon data
        sessionStorage.removeItem('appliedCoupon');
        sessionStorage.removeItem('checkout-draft');
        
        // Reset form to clean state
        const form = document.getElementById('customer-form');
        if (form) {
            form.reset();
        }
        
        // Clear coupon input and status
        const couponInput = document.getElementById('coupon-code');
        const couponStatus = document.getElementById('coupon-status');
        if (couponInput) {
            couponInput.value = '';
            couponInput.disabled = false;
        }
        if (couponStatus) {
            couponStatus.classList.add('hidden');
        }
        
        // Reset apply button
        const applyBtn = document.getElementById('apply-coupon');
        if (applyBtn) {
            applyBtn.style.display = 'block';
            applyBtn.disabled = false;
        }
    }
    
    initializeEventListeners() {
        const applyBtn = document.getElementById('apply-coupon');
        const couponInput = document.getElementById('coupon-code');
        
        // Coupon application
        applyBtn.addEventListener('click', () => {
            if (!this.couponData) {
                this.applyCoupon();
            }
        });
        
        couponInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !this.couponData) {
                this.applyCoupon();
            }
        });
        
        // Disable coupon input if already applied
        if (this.couponData) {
            couponInput.disabled = true;
            applyBtn.style.display = 'none';
        }
        
        // Form submission
        document.getElementById('place-order-btn').addEventListener('click', () => this.submitOrder());
        
        // Real-time form validation
        document.getElementById('customer-form').addEventListener('input', () => this.validateForm());
        
        // Handle page visibility change (tab switching)
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                // Refresh CSRF token when page becomes visible
                this.refreshCSRFToken();
            }
        });
    }
    
    loadCart() {
        const stockMap = @json($stockMap ?? []);
        let items = [];

        // 1. Check URL parameters FIRST (e.g. ?items=1773:2,1774:1)
        const urlParams = new URLSearchParams(window.location.search);
        const itemsParam = urlParams.get('items');
        if (itemsParam && itemsParam.trim() !== '') {
            const pairs = itemsParam.split(',');
            pairs.forEach(pair => {
                const parts = pair.split(':');
                if (parts.length === 2) {
                    const productId = parseInt(parts[0]);
                    const qty = parseInt(parts[1]);
                    if (productId && qty > 0) {
                        items.push({
                            product_id: productId,
                            quantity: qty
                        });
                    }
                }
            });
        }

        // 2. If URL had no items, check localStorage & sessionStorage
        if (!items || items.length === 0) {
            const rawData = localStorage.getItem('cartItems') || localStorage.getItem('cart') || sessionStorage.getItem('cartItems');
            if (rawData) {
                try {
                    const parsed = JSON.parse(rawData);
                    if (Array.isArray(parsed) && parsed.length > 0) {
                        items = parsed;
                    }
                } catch (e) {
                    console.error('Error parsing cart items:', e);
                }
            }
        }

        // 3. Hydrate all items with stockMap details to ensure valid names & original prices
        if (Array.isArray(items) && items.length > 0) {
            this.cartItems = items.map(item => {
                const pId = parseInt(item.product_id || item.id || 0);
                const isCombo = !!(item.is_combo || (pId >= 999000 && pId <= 999999) || (typeof item.product_name === 'string' && item.product_name.toUpperCase().includes('COMBO')));
                const stock = stockMap[pId] || {};
                
                let origPrice = 0;
                if (typeof item.original_price !== 'undefined' && item.original_price !== null && !isNaN(item.original_price) && Number(item.original_price) > 0) {
                    origPrice = Number(item.original_price);
                } else if (stock.original_price && Number(stock.original_price) > 0) {
                    origPrice = Number(stock.original_price);
                } else if (item.rate || item.price || stock.price) {
                    origPrice = Number(item.rate || item.price || stock.price || 0);
                }

                let currentRate = 0;
                if (typeof item.rate !== 'undefined' && item.rate !== null && !isNaN(item.rate) && Number(item.rate) > 0) {
                    currentRate = Number(item.rate);
                } else if (item.price && Number(item.price) > 0) {
                    currentRate = Number(item.price);
                } else if (stock.price) {
                    currentRate = Number(stock.price);
                } else {
                    currentRate = origPrice;
                }

                const qty = Math.max(1, parseInt(item.quantity || item.qty || 1));
                const finalOrigPrice = isCombo ? (currentRate > 0 ? currentRate : origPrice) : (origPrice > 0 ? origPrice : currentRate);

                return {
                    product_id: pId,
                    product_name: item.product_name || item.name || stock.name || (isCombo ? 'Diwali Combo Pack' : `Product #${pId}`),
                    content: item.content || (isCombo ? 'Diwali Combo Pack' : ''),
                    rate: item.is_lucky_spin_gift ? 0 : (isCombo ? finalOrigPrice : currentRate),
                    price: isCombo ? finalOrigPrice : (item.price || currentRate),
                    original_price: finalOrigPrice,
                    quantity: qty,
                    total: item.is_lucky_spin_gift ? 0 : ((isCombo ? finalOrigPrice : finalOrigPrice) * qty),
                    is_combo: isCombo,
                    is_lucky_spin_gift: !!item.is_lucky_spin_gift,
                    is_free_gift: !!item.is_free_gift
                };
            }).filter(item => (item.product_id > 0 || item.is_combo) && item.quantity > 0);

            // Persist back to localStorage
            if (this.cartItems.length > 0) {
                localStorage.setItem('cartItems', JSON.stringify(this.cartItems));
            }
        } else {
            this.cartItems = [];
        }

        this.calculateTotals();
    }
    
    calculateTotals() {
        let regularSubtotal = 0;
        let comboSubtotal = 0;

        this.cartItems.forEach(item => {
            if (item.is_lucky_spin_gift || item.is_free_gift) return;
            const qty = Number(item.quantity || item.qty || 0);
            if (item.is_combo) {
                const comboPrice = Number(item.price || item.rate || item.original_price || 0);
                comboSubtotal += (comboPrice * qty);
            } else {
                const originalPrice = (typeof item.original_price !== 'undefined' && item.original_price !== null && !isNaN(item.original_price) && Number(item.original_price) > 0)
                    ? Number(item.original_price)
                    : Number(item.rate || item.price || 0);
                regularSubtotal += (originalPrice * qty);
            }
        });
        
        this.regularSubtotal = regularSubtotal;
        this.comboSubtotal = comboSubtotal;
        this.orderValue = regularSubtotal > 0 ? regularSubtotal : comboSubtotal;

        // Apply wholesale discounts on regular products (MRP)
        const discount70 = Math.round(regularSubtotal * 0.70 * 100) / 100;
        const afterDiscount70 = Math.max(0, Math.round((regularSubtotal - discount70) * 100) / 100);
        const discount15 = Math.round(afterDiscount70 * 0.15 * 100) / 100;
        const afterDiscount15 = Math.max(0, Math.round((afterDiscount70 - discount15) * 100) / 100);

        this.discount70 = discount70;
        this.afterDiscount70 = afterDiscount70;
        this.discount15 = discount15;
        this.afterDiscount15 = afterDiscount15;

        // Apply coupon discount if available
        let couponDiscount = 0;
        if (this.couponData) {
            couponDiscount = Number(this.couponData.discount_amount || 0);
        }
        this.couponDiscount = couponDiscount;
        const afterCouponDiscount = Math.max(0, Math.round((afterDiscount15 - couponDiscount) * 100) / 100);
        this.afterCouponDiscount = afterCouponDiscount;

        // T. Amt = After Coupon Discount + Net rate Items / Combo
        const totalBeforePacking = Math.round((afterCouponDiscount + comboSubtotal) * 100) / 100;
        this.totalBeforePacking = totalBeforePacking;

        // Add packing 5% on T. Amt
        const packingCharge = Math.round(totalBeforePacking * 0.05 * 100) / 100;
        this.packingCharge = packingCharge;
        
        let finalTotal = totalBeforePacking + packingCharge;

        // Apply lucky spin discount if active
        if (this.luckySpinDiscount > 0) {
            finalTotal -= this.luckySpinDiscount;
        }

        this.finalTotal = Math.max(0, Math.round(finalTotal));

        // Qualifying amount for Lucky Wheel threshold:
        // STRICT RULE: Lucky Wheel is ONLY for normal purchases above ₹5,000 (like old style).
        // Combos have separate all-inclusive net offer pricing and DO NOT count towards unlocking the Lucky Wheel.
        const normalPurchasePayable = afterDiscount15 + packingCharge;
        const couponDisc = this.couponData ? (this.couponData.discount_amount || 0) : 0;
        this.qualifyingAmount = Math.max(0, Math.round((normalPurchasePayable - couponDisc) * 100) / 100);

        // Strict 5k threshold check ONLY for normal purchases
        const isEligible = this.qualifyingAmount >= 5000;

        if (!isEligible) {
            // If total amount is less than 5000, automatically remove lucky spin gift & discount!
            const hadLuckyGift = this.cartItems.some(item => item.is_lucky_spin_gift);
            const hadLuckyReward = hadLuckyGift || this.hasSpunWheel || !!this.luckySpinPrize || this.luckySpinDiscount > 0;

            if (hadLuckyReward) {
                // 1. Remove lucky spin gift items from cartItems
                this.cartItems = this.cartItems.filter(item => !item.is_lucky_spin_gift);
                localStorage.setItem('cartItems', JSON.stringify(this.cartItems));

                // 2. Reset spin states
                this.hasSpunWheel = false;
                this.luckySpinPrize = null;
                this.luckySpinDiscount = 0;

                // 3. Clear session storage
                try {
                    sessionStorage.removeItem('lucky_spin_result');
                } catch(e) {}

                // 4. Clear hidden inputs
                const hiddenPrize = document.getElementById('order-lucky-spin-prize');
                const hiddenDisc = document.getElementById('order-lucky-spin-discount');
                if (hiddenPrize) hiddenPrize.value = '';
                if (hiddenDisc) hiddenDisc.value = '0';

                // 5. Hide summary rows
                const spinDiscRow = document.getElementById('lucky-spin-discount-row');
                if (spinDiscRow) spinDiscRow.classList.add('hidden');
                const giftRow = document.getElementById('lucky-spin-gift-row');
                if (giftRow) giftRow.classList.add('hidden');

                // 6. Notify user
                this.showSpinRevokedAlert();
            }
        }

        // Apply 5% Lucky Spin discount ONLY if eligible and won
        if (isEligible && (this.luckySpinPrize === '5% Discount' || (this.luckySpinPrize && this.luckySpinPrize.includes('5%')))) {
            this.luckySpinDiscount = Math.round(this.qualifyingAmount * 0.05 * 100) / 100;
            finalTotal -= this.luckySpinDiscount;
        } else {
            this.luckySpinDiscount = 0;
        }
        
        this.finalTotal = Math.max(0, Math.round(finalTotal * 100) / 100);
        
        this.updateDisplay();
    }
    
    updateDisplay() {
        // Update cart items
        const container = document.getElementById('cart-items-container');
        if (!container) return;

        if (this.cartItems.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-center py-4">Your cart is empty</p>';
            document.getElementById('order-value').textContent = '₹0.00';
            document.getElementById('discount-70').textContent = '-₹0.00';
            document.getElementById('discount-15').textContent = '-₹0.00';
            document.getElementById('coupon-discount').textContent = '-₹0.00';
            document.getElementById('packing-charge').textContent = '₹0.00';
            document.getElementById('final-total').textContent = '₹0.00';
            document.getElementById('cart-subtotal').textContent = '₹0.00';
            this.updateLuckyWheelState();
            this.validateForm();
            return;
        }
        
        let html = '';
        this.cartItems.forEach((item, index) => {
            const name = item.product_name || item.name || 'Product';
            const qty = item.quantity || item.qty || 0;
            const originalPrice = (typeof item.original_price !== 'undefined' && item.original_price !== null && !isNaN(item.original_price) && Number(item.original_price) > 0)
                ? Number(item.original_price)
                : Number(item.rate || item.price || 0);
            const isGift = item.is_lucky_spin_gift || item.is_free_gift;
            const isCombo = !!item.is_combo;
            const unitPrice = isCombo ? Number(item.price || item.rate || originalPrice) : originalPrice;
            const total = isGift ? 0 : (unitPrice * qty);
            
            html += `
                <div class="flex items-center justify-between p-3 ${isGift ? 'bg-amber-50/70 border-2 border-amber-400' : (isCombo ? 'bg-amber-50/40 border border-amber-300' : 'bg-gray-50 border border-gray-200')} rounded-lg">
                    <div class="flex-1">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base">${name}</h4>
                            ${isGift ? '<span class="px-2 py-0.5 bg-amber-400 text-purple-950 font-black text-[10px] uppercase rounded-full">FREE GIFT</span>' : ''}
                            ${isCombo ? '<span class="px-2 py-0.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-[10px] uppercase rounded-full">COMBO OFFER (NET PRICE)</span>' : ''}
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600">
                            ${isGift ? `Qty: ${qty} (Value: ₹${originalPrice.toFixed(2)})` : (isCombo ? `Qty: ${qty} × ₹${unitPrice.toFixed(2)} (Direct Net Offer)` : `Qty: ${qty} × ₹${originalPrice.toFixed(2)}`)}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-extrabold ${isGift ? 'text-emerald-600' : 'text-purple-950'} text-sm sm:text-base">
                            ${isGift ? 'FREE' : `₹${total.toFixed(2)}`}
                        </p>
                        ${!isGift ? `<button type="button" onclick="smartCheckout.removeItem(${index})" class="text-red-600 text-xs font-bold hover:text-red-800 transition-colors">Remove</button>` : `<span class="text-xs text-amber-700 font-bold">Spin Prize</span>`}
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
        
        // Update summary
        const discount70 = this.discount70 || 0;
        const discount15 = this.discount15 || 0;
        const packingCharge = this.packingCharge || 0;
        
        // Update summary elements matching handwritten slip
        const orderValueEl = document.getElementById('order-value');
        if (orderValueEl) orderValueEl.textContent = `₹${(this.regularSubtotal || 0).toFixed(2)}`;

        const d70El = document.getElementById('discount-70');
        if (d70El) d70El.textContent = `-₹${(this.discount70 || 0).toFixed(2)}`;

        const after70El = document.getElementById('after-discount-70');
        if (after70El) after70El.textContent = `₹${(this.afterDiscount70 || 0).toFixed(2)}`;

        const d15El = document.getElementById('discount-15');
        if (d15El) d15El.textContent = `-₹${(this.discount15 || 0).toFixed(2)}`;

        const after15El = document.getElementById('after-discount-15');
        if (after15El) after15El.textContent = `₹${(this.afterDiscount15 || 0).toFixed(2)}`;

        const couponEl = document.getElementById('coupon-discount');
        if (couponEl) couponEl.textContent = `-₹${(this.couponDiscount || 0).toFixed(2)}`;

        const afterCouponEl = document.getElementById('after-coupon-discount');
        if (afterCouponEl) afterCouponEl.textContent = `₹${(this.afterCouponDiscount || 0).toFixed(2)}`;

        const comboSubtotalEl = document.getElementById('combo-subtotal');
        if (comboSubtotalEl) comboSubtotalEl.textContent = `₹${(this.comboSubtotal || 0).toFixed(2)}`;
        const comboRowEl = document.getElementById('combo-subtotal-row');
        if (comboRowEl) {
            if ((this.comboSubtotal || 0) > 0) {
                comboRowEl.classList.remove('hidden');
            } else {
                comboRowEl.classList.add('hidden');
            }
        }

        const totalBeforePackingEl = document.getElementById('total-before-packing');
        if (totalBeforePackingEl) totalBeforePackingEl.textContent = `₹${(this.totalBeforePacking || 0).toFixed(2)}`;

        const packingEl = document.getElementById('packing-charge');
        if (packingEl) packingEl.textContent = `₹${(this.packingCharge || 0).toFixed(2)}`;

        const finalTotalEl = document.getElementById('final-total');
        if (finalTotalEl) finalTotalEl.textContent = `₹${this.finalTotal.toLocaleString('en-IN')}`;

        const cartSubtotalEl = document.getElementById('cart-subtotal');
        if (cartSubtotalEl) cartSubtotalEl.textContent = `₹${((this.regularSubtotal || 0) + (this.comboSubtotal || 0)).toFixed(2)}`;

        // Lucky Spin rows in summary
        const spinDiscRow = document.getElementById('lucky-spin-discount-row');
        const spinDiscVal = document.getElementById('lucky-spin-discount');
        if (this.luckySpinDiscount > 0 && spinDiscRow && spinDiscVal) {
            spinDiscRow.classList.remove('hidden');
            spinDiscVal.textContent = `-₹${this.luckySpinDiscount.toFixed(2)}`;
        } else if (spinDiscRow) {
            spinDiscRow.classList.add('hidden');
        }

        const giftRow = document.getElementById('lucky-spin-gift-row');
        const giftNameEl = document.getElementById('lucky-spin-gift-name');
        if (this.luckySpinPrize && this.luckySpinPrize !== '5% Discount' && !this.luckySpinPrize.includes('Better Luck') && giftRow && giftNameEl) {
            giftRow.classList.remove('hidden');
            giftNameEl.textContent = this.luckySpinPrize;
        } else if (giftRow) {
            giftRow.classList.add('hidden');
        }

        // Sync hidden form inputs
        const hiddenPrize = document.getElementById('order-lucky-spin-prize');
        const hiddenDisc = document.getElementById('order-lucky-spin-discount');
        if (hiddenPrize) hiddenPrize.value = this.luckySpinPrize || '';
        if (hiddenDisc) hiddenDisc.value = this.luckySpinDiscount || 0;

        this.updateLuckyWheelState();
        this.validateForm();
    }

    // Initialize Lucky Wheel
    initLuckyWheel() {
        this.wheelCanvas = document.getElementById('lucky-wheel-canvas');
        if (!this.wheelCanvas) return;
        this.wheelCtx = this.wheelCanvas.getContext('2d');

        // Only restore saved spin result if qualifying normal purchase amount is >= 5000
        const qualAmount = typeof this.qualifyingAmount === 'number' ? this.qualifyingAmount : 0;
        if (qualAmount >= 5000) {
            const saved = sessionStorage.getItem('lucky_spin_result');
            if (saved) {
                try {
                    const parsed = JSON.parse(saved);
                    if (parsed && parsed.prize) {
                        this.hasSpunWheel = true;
                        this.luckySpinPrize = parsed.prize;
                        this.luckySpinDiscount = parsed.discount || 0;
                    }
                } catch(e) {}
            }
        } else {
            // Drop below 5k on load: ensure clean state
            try { sessionStorage.removeItem('lucky_spin_result'); } catch(e) {}
            this.hasSpunWheel = false;
            this.luckySpinPrize = null;
            this.luckySpinDiscount = 0;
            if (this.cartItems.some(it => it.is_lucky_spin_gift)) {
                this.cartItems = this.cartItems.filter(it => !it.is_lucky_spin_gift);
                localStorage.setItem('cartItems', JSON.stringify(this.cartItems));
            }
        }

        // Preload prize images
        this.wheelPrizes.forEach(prize => {
            if (prize.image) {
                const img = new Image();
                img.crossOrigin = "anonymous";
                img.onload = () => {
                    this.loadedWheelImages[prize.id] = img;
                    this.drawLuckyWheel();
                };
                img.src = prize.image;
            }
        });

        // Event listeners for spin buttons
        const centerBtn = document.getElementById('wheel-center-btn');
        const actionBtn = document.getElementById('wheel-action-btn');
        const modalClaimBtn = document.getElementById('modal-claim-btn');

        if (centerBtn) {
            centerBtn.addEventListener('click', () => this.spinWheel());
        }
        if (actionBtn) {
            actionBtn.addEventListener('click', () => this.spinWheel());
        }
        if (modalClaimBtn) {
            modalClaimBtn.addEventListener('click', () => {
                const modal = document.getElementById('lucky-winner-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        }

        this.drawLuckyWheel();
    }

    drawLuckyWheel() {
        if (!this.wheelCanvas || !this.wheelCtx) return;
        const ctx = this.wheelCtx;
        const width = this.wheelCanvas.width;
        const height = this.wheelCanvas.height;
        const centerX = width / 2;
        const centerY = height / 2;
        const radius = width / 2 - 14;

        ctx.clearRect(0, 0, width, height);

        const numSlices = this.wheelPrizes.length;
        const sliceAngle = (2 * Math.PI) / numSlices;

        // Draw 5 Segments
        for (let i = 0; i < numSlices; i++) {
            const prize = this.wheelPrizes[i];
            const startAngle = this.currentWheelRotation + (i * sliceAngle);
            const endAngle = startAngle + sliceAngle;

            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, startAngle, endAngle);
            ctx.closePath();

            // Gradient fill
            const grad = ctx.createRadialGradient(centerX, centerY, 20, centerX, centerY, radius);
            grad.addColorStop(0, prize.accent || prize.color);
            grad.addColorStop(1, prize.color);
            ctx.fillStyle = grad;
            ctx.fill();

            // Golden segment border
            ctx.strokeStyle = '#FBBF24';
            ctx.lineWidth = 2.5;
            ctx.stroke();

            // Draw content inside slice
            ctx.save();
            ctx.translate(centerX, centerY);
            ctx.rotate(startAngle + sliceAngle / 2);

            // Thumbnail / Icon badge
            const badgeDist = radius * 0.65;
            const badgeRadius = 20;

            ctx.save();
            ctx.beginPath();
            ctx.arc(badgeDist, 0, badgeRadius, 0, Math.PI * 2);
            ctx.fillStyle = '#1E093B';
            ctx.fill();
            ctx.lineWidth = 2;
            ctx.strokeStyle = '#FDE047';
            ctx.stroke();

            if (prize.image && this.loadedWheelImages[prize.id]) {
                ctx.clip();
                const img = this.loadedWheelImages[prize.id];
                ctx.drawImage(img, badgeDist - badgeRadius, -badgeRadius, badgeRadius * 2, badgeRadius * 2);
            } else {
                ctx.font = '16px sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillStyle = '#FFFFFF';
                ctx.fillText(prize.icon || '🎁', badgeDist, 0);
            }
            ctx.restore();

            // Text: Name & Worth
            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = '#FFFFFF';
            ctx.font = 'bold 11px sans-serif';
            ctx.shadowColor = 'rgba(0,0,0,0.85)';
            ctx.shadowBlur = 4;
            ctx.fillText(prize.name, radius * 0.33, -6);

            ctx.fillStyle = '#FDE047';
            ctx.font = '800 9.5px sans-serif';
            ctx.fillText(prize.worthText, radius * 0.33, 7);
            ctx.restore();

            ctx.restore();
        }

        // Draw Outer Rim with light bulbs
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius + 2, 0, Math.PI * 2);
        ctx.strokeStyle = '#F59E0B';
        ctx.lineWidth = 10;
        ctx.stroke();

        ctx.beginPath();
        ctx.arc(centerX, centerY, radius + 7, 0, Math.PI * 2);
        ctx.strokeStyle = '#B45309';
        ctx.lineWidth = 2;
        ctx.stroke();

        // 20 Rim Bulbs
        const numBulbs = 20;
        for (let b = 0; b < numBulbs; b++) {
            const bulbAngle = (b * 2 * Math.PI) / numBulbs;
            const bulbX = centerX + (radius + 2) * Math.cos(bulbAngle);
            const bulbY = centerY + (radius + 2) * Math.sin(bulbAngle);

            ctx.beginPath();
            ctx.arc(bulbX, bulbY, 3.5, 0, Math.PI * 2);
            const bulbColors = ['#FEF08A', '#FFFFFF', '#F87171', '#34D399'];
            ctx.fillStyle = bulbColors[b % bulbColors.length];
            ctx.fill();
        }

        // Center hub circle
        ctx.beginPath();
        ctx.arc(centerX, centerY, 42, 0, Math.PI * 2);
        ctx.fillStyle = '#1E093B';
        ctx.fill();
        ctx.lineWidth = 3;
        ctx.strokeStyle = '#FBBF24';
        ctx.stroke();
    }

    updateLuckyWheelState() {
        const qualAmount = typeof this.qualifyingAmount === 'number' ? this.qualifyingAmount : 0;
        const isEligible = qualAmount >= 5000;

        const lockedBanner = document.getElementById('wheel-locked-banner');
        const unlockedBanner = document.getElementById('wheel-unlocked-banner');
        const wonBanner = document.getElementById('wheel-won-banner');
        const overlay = document.getElementById('wheel-locked-overlay');
        const actionBtn = document.getElementById('wheel-action-btn');
        const actionBtnText = document.getElementById('wheel-action-btn-text');
        const centerBtnLabel = document.getElementById('center-btn-label');
        const statusDot = document.getElementById('wheel-status-dot');
        const statusText = document.getElementById('wheel-status-text');
        const curAmt = document.getElementById('wheel-current-amount');
        const remAmt = document.getElementById('wheel-remaining-amount');
        const pctEl = document.getElementById('wheel-progress-pct');
        const barEl = document.getElementById('wheel-progress-bar');

        if (curAmt) curAmt.textContent = `₹${qualAmount.toFixed(2)}`;
        const remaining = Math.max(0, 5000 - qualAmount);
        if (remAmt) remAmt.textContent = `₹${remaining.toFixed(2)}`;
        const pct = Math.min(100, Math.round((qualAmount / 5000) * 100));
        if (pctEl) pctEl.textContent = `${pct}%`;
        if (barEl) barEl.style.width = `${pct}%`;

        if (!isEligible) {
            // Locked (Under ₹5,000)
            if (lockedBanner) lockedBanner.classList.remove('hidden');
            if (unlockedBanner) unlockedBanner.classList.add('hidden');
            if (wonBanner) wonBanner.classList.add('hidden');
            if (overlay) overlay.classList.remove('hidden');
            if (statusDot) {
                statusDot.className = 'w-2 h-2 rounded-full bg-amber-400';
            }
            if (statusText) statusText.textContent = 'Locked (Under ₹5,000)';
            if (actionBtn) actionBtn.disabled = true;
            if (actionBtnText) actionBtnText.textContent = `LOCKED (Add ₹${remaining.toFixed(2)})`;
            if (centerBtnLabel) centerBtnLabel.textContent = 'LOCKED';

            if (!this.isSpinning) {
                this.currentWheelAngle = 0;
                this.drawLuckyWheel();
            }
        } else if (this.hasSpunWheel) {
            // Already Spun & Eligible (>= ₹5,000)
            if (lockedBanner) lockedBanner.classList.add('hidden');
            if (unlockedBanner) unlockedBanner.classList.add('hidden');
            if (wonBanner) wonBanner.classList.remove('hidden');
            if (overlay) overlay.classList.add('hidden');
            if (statusDot) {
                statusDot.className = 'w-2 h-2 rounded-full bg-emerald-400';
            }
            if (statusText) statusText.textContent = 'Prize Claimed ✓';
            if (actionBtn) actionBtn.disabled = true;
            if (actionBtnText) actionBtnText.textContent = `WON: ${this.luckySpinPrize}`;
            if (centerBtnLabel) centerBtnLabel.textContent = 'CLAIMED';

            const prizeNameText = document.getElementById('won-prize-name-text');
            const prizeDescText = document.getElementById('won-prize-desc-text');
            if (prizeNameText) prizeNameText.textContent = this.luckySpinPrize;
            if (prizeDescText) {
                if (this.luckySpinPrize === '5% Discount' || (this.luckySpinPrize && this.luckySpinPrize.includes('5%'))) {
                    prizeDescText.textContent = `Extra 5% discount (₹${this.luckySpinDiscount.toFixed(2)}) applied to your final bill!`;
                } else if (this.luckySpinPrize.includes('Better Luck')) {
                    prizeDescText.textContent = `Better luck next time! Happy Diwali wishes from Radhe Crackers!`;
                } else {
                    prizeDescText.textContent = `FREE cracker gift item added to your package!`;
                }
            }
        } else {
            // Unlocked and Ready (>= ₹5,000)
            if (lockedBanner) lockedBanner.classList.add('hidden');
            if (unlockedBanner) unlockedBanner.classList.remove('hidden');
            if (wonBanner) wonBanner.classList.add('hidden');
            if (overlay) overlay.classList.add('hidden');
            if (statusDot) {
                statusDot.className = 'w-2 h-2 rounded-full bg-emerald-400 animate-ping';
            }
            if (statusText) statusText.textContent = 'Unlocked (Orders > 5k)';
            if (actionBtn) actionBtn.disabled = this.isSpinning;
            if (actionBtnText) actionBtnText.textContent = 'SPIN TO WIN 🎯';
            if (centerBtnLabel) centerBtnLabel.textContent = 'SPIN';
        }
    }

    spinWheel() {
        const qualAmount = this.qualifyingAmount || this.finalTotal || 0;
        if (qualAmount < 5000) {
            alert('The Lucky Wheel unlocks exclusively for orders above ₹5,000! Please add more crackers to your cart.');
            return;
        }
        if (this.hasSpunWheel) {
            alert(`You have already spun the wheel and won: ${this.luckySpinPrize}!`);
            return;
        }
        if (this.isSpinning) return;

        this.isSpinning = true;
        const actionBtn = document.getElementById('wheel-action-btn');
        if (actionBtn) actionBtn.disabled = true;

        // Choose winning prize (random among 5 items)
        const prizeIndex = Math.floor(Math.random() * this.wheelPrizes.length);
        const prize = this.wheelPrizes[prizeIndex];

        // Top pointer needle is at 270 degrees (-PI/2) in standard canvas coordinates
        const sliceAngle = (2 * Math.PI) / this.wheelPrizes.length;
        const sliceCenterAngle = (prizeIndex * sliceAngle) + (sliceAngle / 2);
        
        const targetNeedleAngle = -Math.PI / 2;
        let targetRotation = targetNeedleAngle - sliceCenterAngle;

        // Normalize
        const twoPI = 2 * Math.PI;
        while (targetRotation < 0) targetRotation += twoPI;

        // Add 6 full revolutions (6 * 2*PI)
        const totalRotations = 6 * twoPI;
        const finalTargetAngle = this.currentWheelRotation + totalRotations + (targetRotation - (this.currentWheelRotation % twoPI));

        const startAngle = this.currentWheelRotation;
        const totalDelta = finalTargetAngle - startAngle;
        const duration = 5200; // 5.2 seconds
        const startTime = performance.now();

        let lastTickAngle = startAngle;
        const tickThreshold = sliceAngle / 2;

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing: easeOutQuart
            const ease = 1 - Math.pow(1 - progress, 4);
            this.currentWheelRotation = startAngle + (totalDelta * ease);

            // Play tick sound when passing segment boundaries
            if (Math.abs(this.currentWheelRotation - lastTickAngle) >= tickThreshold) {
                this.playTickSound();
                lastTickAngle = this.currentWheelRotation;
            }

            this.drawLuckyWheel();

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                this.isSpinning = false;
                this.hasSpunWheel = true;
                this.luckySpinPrize = prize.name;

                // Play win sound
                this.playWinSound();

                // Save to session
                sessionStorage.setItem('lucky_spin_result', JSON.stringify({
                    prize: prize.name,
                    prize_id: prize.id,
                    type: prize.type
                }));

                // Handle reward
                if (prize.type === 'discount') {
                    this.calculateTotals();
                } else if (prize.type === 'product') {
                    // Inject free gift item into cartItems if not already present
                    const exists = this.cartItems.some(it => it.is_lucky_spin_gift);
                    if (!exists) {
                        this.cartItems.push({
                            product_id: prize.productId,
                            product_name: `🎁 ${prize.fullName || prize.name} (Free Gift)`,
                            content: '1 Gift Pcs',
                            rate: 0,
                            original_price: prize.originalPrice,
                            quantity: 1,
                            total: 0,
                            is_lucky_spin_gift: true,
                            is_free_gift: true
                        });
                        localStorage.setItem('cartItems', JSON.stringify(this.cartItems));
                    }
                    this.calculateTotals();
                } else {
                    this.calculateTotals();
                }

                this.showWinnerModal(prize);
                this.updateLuckyWheelState();
            }
        };

        requestAnimationFrame(animate);
    }

    showWinnerModal(prize) {
        const modal = document.getElementById('lucky-winner-modal');
        if (!modal) return;

        const titleEl = document.getElementById('modal-prize-title');
        const subEl = document.getElementById('modal-prize-subtitle');
        const nameEl = document.getElementById('modal-prize-name');
        const valEl = document.getElementById('modal-prize-val');
        const statusEl = document.getElementById('modal-prize-status');
        const imgEl = document.getElementById('modal-prize-img');
        const emojiEl = document.getElementById('modal-prize-emoji');

        if (prize.type === 'none') {
            if (titleEl) titleEl.textContent = 'Better Luck Next Time!';
            if (subEl) subEl.textContent = 'Thank you for shopping with Radhe Crackers!';
            if (nameEl) nameEl.textContent = 'Diwali Festive Greetings';
            if (valEl) valEl.textContent = 'Wishing you a joyful celebration!';
            if (statusEl) statusEl.textContent = '✨ Best prices & quality guaranteed';
            if (emojiEl) emojiEl.textContent = '🍀';
            if (imgEl) imgEl.classList.add('hidden');
        } else if (prize.type === 'discount') {
            if (titleEl) titleEl.textContent = 'Congratulations! 5% Discount!';
            if (subEl) subEl.textContent = 'Extra 5% discount unlocked on your entire order!';
            if (nameEl) nameEl.textContent = '5% Extra Diwali Discount';
            if (valEl) valEl.textContent = `-₹${this.luckySpinDiscount.toFixed(2)} deducted from bill`;
            if (statusEl) statusEl.textContent = '✓ Automatically applied to total';
            if (emojiEl) emojiEl.textContent = '🏷️';
            if (imgEl) imgEl.classList.add('hidden');
        } else {
            if (titleEl) titleEl.textContent = '🎉 Congratulations! You Won!';
            if (subEl) subEl.textContent = 'You have won a free fireworks gift!';
            if (nameEl) nameEl.textContent = prize.fullName || prize.name;
            if (valEl) valEl.textContent = `${prize.worthText} (FREE GIFT)`;
            if (statusEl) statusEl.textContent = '✓ Added to your cart & bill (Rate: ₹0.00)';
            if (emojiEl) emojiEl.textContent = '🎁';
            if (imgEl) {
                if (prize.image) {
                    imgEl.src = prize.image;
                    imgEl.classList.remove('hidden');
                } else {
                    imgEl.classList.add('hidden');
                }
            }
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    playTickSound() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(700, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(150, ctx.currentTime + 0.04);
            gain.gain.setValueAtTime(0.12, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.04);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.05);
        } catch(e) {}
    }

    playWinSound() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            const notes = [523.25, 659.25, 783.99, 1046.50];
            notes.forEach((freq, idx) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + (idx * 0.1));
                gain.gain.setValueAtTime(0.18, ctx.currentTime + (idx * 0.1));
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + (idx * 0.1) + 0.35);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + (idx * 0.1));
                osc.stop(ctx.currentTime + (idx * 0.1) + 0.4);
            });
        } catch(e) {}
    }
    
    async applyCoupon() {
        const code = document.getElementById('coupon-code').value.trim();
        const applyBtn = document.getElementById('apply-coupon');
        const couponInput = document.getElementById('coupon-code');
        
        if (!code) {
            this.showCouponStatus('Please enter a coupon code', 'error');
            return;
        }

        // Calculate the current order total before applying coupon
        const currentTotal = this.finalTotal;
        if (currentTotal <= 0) {
            this.showCouponStatus('Please add items to your cart before applying a coupon', 'error');
            return;
        }
        
        // Disable input and button while processing
        couponInput.disabled = true;
        applyBtn.disabled = true;
        
        try {
            // Get fresh CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token not found. Please refresh the page and try again.');
            }
            
            const response = await fetch('/api/coupons/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    code: code,
                    order_amount: currentTotal
                })
            });
            
            if (!response.ok) {
                if (response.status === 419) {
                    throw new Error('Session expired. Please refresh the page and try again.');
                } else if (response.status === 422) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Invalid coupon code.');
                } else {
                    throw new Error(`Server error (${response.status}). Please try again.`);
                }
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.couponData = {
                    code: data.coupon.code,
                    discount_amount: data.discount_amount,
                    new_total: data.new_total
                };
                
                // Store in session storage
                sessionStorage.setItem('appliedCoupon', JSON.stringify(this.couponData));
                
                this.calculateTotals();
                this.showCouponStatus(`Coupon applied! Discount: ₹${data.discount_amount.toFixed(2)}`, 'success');
                
                // Hide apply button and keep input disabled
                applyBtn.style.display = 'none';
                
                // Add remove coupon button
                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'Remove Coupon';
                removeBtn.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm';
                removeBtn.onclick = () => this.removeCoupon();
                applyBtn.parentNode.appendChild(removeBtn);
            } else {
                this.showCouponStatus(data.message, 'error');
                // Re-enable input and button on error
                couponInput.disabled = false;
                applyBtn.disabled = false;
            }
        } catch (error) {
            this.showCouponStatus('Error applying coupon. Please try again.', 'error');
            // Re-enable input and button on error
            couponInput.disabled = false;
            applyBtn.disabled = false;
        }
    }
    
    removeCoupon() {
        const couponInput = document.getElementById('coupon-code');
        const applyBtn = document.getElementById('apply-coupon');
        
        // Clear coupon data
        this.couponData = null;
        sessionStorage.removeItem('appliedCoupon');
        
        // Reset UI
        couponInput.value = '';
        couponInput.disabled = false;
        applyBtn.style.display = 'block';
        applyBtn.disabled = false;
        
        // Remove the remove button
        const removeBtn = applyBtn.parentNode.querySelector('button:last-child');
        if (removeBtn && removeBtn !== applyBtn) {
            removeBtn.remove();
        }
        
        // Recalculate totals
        this.calculateTotals();
        this.showCouponStatus('Coupon removed', 'success');
    }
    
    showCouponStatus(message, type) {
        const statusDiv = document.getElementById('coupon-status');
        const className = type === 'success' ? 'bg-green-100 border-green-300 text-green-800' : 'bg-red-100 border-red-300 text-red-800';
        
        statusDiv.className = `p-3 rounded-lg border ${className}`;
        statusDiv.innerHTML = message;
        statusDiv.classList.remove('hidden');
        
        setTimeout(() => {
            statusDiv.classList.add('hidden');
        }, 5000);
    }
    
    removeItem(index) {
        // Prevent manual removal of free gift items via this button
        if (this.cartItems[index] && (this.cartItems[index].is_lucky_spin_gift || this.cartItems[index].is_free_gift)) {
            return;
        }

        this.cartItems.splice(index, 1);
        localStorage.setItem('cartItems', JSON.stringify(this.cartItems));
        this.calculateTotals();
    }

    showSpinRevokedAlert() {
        const revokedBanner = document.getElementById('wheel-revoked-banner');
        const remAmtEl = document.getElementById('revoked-rem-amt');
        const qualAmount = this.qualifyingAmount || this.finalTotal || 0;
        const diff = Math.max(0, 5000 - qualAmount).toFixed(2);
        
        if (remAmtEl) remAmtEl.textContent = diff;
        if (revokedBanner) {
            revokedBanner.classList.remove('hidden');
            if (this.revokedBannerTimeout) clearTimeout(this.revokedBannerTimeout);
            this.revokedBannerTimeout = setTimeout(() => {
                revokedBanner.classList.add('hidden');
            }, 8000);
        }

        this.showFloatingToast(`⚠️ Cart dropped below ₹5,000. Free spin item removed. Add ₹${diff} more to unlock again!`, 'warning');
    }

    showFloatingToast(message, type = 'info') {
        let container = document.getElementById('floating-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'floating-toast-container';
            container.className = 'fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm pointer-events-none';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        const bgClass = type === 'warning' ? 'bg-amber-950/95 border-amber-500 text-amber-100 shadow-amber-500/20' :
                        type === 'success' ? 'bg-emerald-950/95 border-emerald-500 text-emerald-100 shadow-emerald-500/20' :
                        'bg-purple-950/95 border-purple-500 text-purple-100 shadow-purple-500/20';
        
        toast.className = `pointer-events-auto p-3.5 rounded-xl border shadow-xl flex items-start gap-2.5 text-xs sm:text-sm font-medium transition-all duration-300 transform translate-y-2 opacity-0 ${bgClass}`;
        toast.innerHTML = `
            <span class="text-base flex-shrink-0">⚠️</span>
            <div class="flex-1 leading-snug">${message}</div>
            <button type="button" class="text-white/60 hover:text-white font-bold ml-1" onclick="this.parentElement.remove()">&times;</button>
        `;

        container.appendChild(toast);
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
        });

        setTimeout(() => {
            toast.classList.add('translate-y-2', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 6000);
    }
    
    validateForm() {
        const form = document.getElementById('customer-form');
        const submitBtn = document.getElementById('place-order-btn');
        const isValid = form.checkValidity();
        
        submitBtn.disabled = !isValid || this.cartItems.length === 0 || this.isProcessing;
    }
    
    async submitOrder() {
        if (this.isProcessing) return;
        
        const form = document.getElementById('customer-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        if (this.cartItems.length === 0) {
            alert('Your cart is empty. Please add items before placing an order.');
            return;
        }
        
        this.isProcessing = true;
        this.updateButtonState(true);
        
        // Clear coupon data from session storage when order is submitted
        sessionStorage.removeItem('appliedCoupon');
        
        // Prepare form data
        const formData = new FormData(form);
        formData.append('items', JSON.stringify(this.cartItems));
        formData.append('coupon_code', this.couponData ? this.couponData.code : '');
        formData.append('coupon_discount', this.couponData ? this.couponData.discount_amount : 0);
        formData.append('lucky_spin_prize', this.luckySpinPrize || '');
        formData.append('lucky_spin_discount', this.luckySpinDiscount || 0);
        formData.append('total', this.finalTotal);
        formData.append('clear_cart', 'true');
        
        try {
            // Get fresh CSRF token
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                const tokenInput = document.querySelector('input[name="_token"]');
                if (tokenInput) {
                    csrfToken = tokenInput.value;
                }
            }
            if (!csrfToken) {
                throw new Error('CSRF token not found. Please refresh the page and try again.');
            }
            
            const response = await fetch('{{ route("smart-checkout.submit") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            // Check if response is ok
            if (!response.ok) {
                if (response.status === 419) {
                    throw new Error('Session expired. Please refresh the page and try again.');
                } else if (response.status === 422) {
                    const errorData = await response.json();
                    let msg = errorData.message || 'Validation error. Please check your information.';
                    if (errorData.errors) {
                        const firstKey = Object.keys(errorData.errors)[0];
                        if (firstKey && errorData.errors[firstKey].length > 0) {
                            msg = errorData.errors[firstKey][0];
                        }
                    }
                    throw new Error(msg);
                } else {
                    let errMsg = `Server error (${response.status}). Please try again.`;
                    try {
                        const errData = await response.json();
                        if (errData.message) errMsg = errData.message;
                    } catch(e) {}
                    throw new Error(errMsg);
                }
            }
            
            const result = await response.json();
            
            if (result.success) {
                // Clear all session data
                this.clearPreviousSessionData();
                localStorage.removeItem('cartItems');
                sessionStorage.removeItem('lucky_spin_result');
                
                // Show success message
                this.showSuccessMessage('Order placed successfully! PDF Bill has been sent to your WhatsApp.');

                // Redirect after brief delay
                setTimeout(() => {
                    window.location.href = result.redirect_url;
                }, 1500);
            } else {
                throw new Error(result.message || 'Error placing order. Please try again.');
            }
        } catch (error) {
            console.error('Order submission error:', error);
            this.showErrorMessage(error.message || 'Network error. Please check your connection and try again.');
        } finally {
            this.isProcessing = false;
            this.updateButtonState(false);
        }
    }
    
    // saveDraft() {
    //     const formData = new FormData(document.getElementById('customer-form'));
    //     const draft = {
    //         customer: Object.fromEntries(formData),
    //         cart: this.cartItems,
    //         coupon: this.couponData,
    //         timestamp: new Date().toISOString()
    //     };
        
    //     localStorage.setItem('checkout-draft', JSON.stringify(draft));
    //     alert('Draft saved successfully!');
    // }
    
    updateButtonState(loading) {
        const btn = document.getElementById('place-order-btn');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');
        
        if (loading) {
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
        } else {
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        }
    }
    
    showSuccessMessage(message) {
        this.showMessage(message, 'success');
    }
    
    showErrorMessage(message) {
        this.showMessage(message, 'error');
    }
    
    showMessage(message, type) {
        // Create or update message container
        let messageContainer = document.getElementById('order-message');
        if (!messageContainer) {
            messageContainer = document.createElement('div');
            messageContainer.id = 'order-message';
            messageContainer.className = 'fixed top-4 right-4 z-50 max-w-sm';
            document.body.appendChild(messageContainer);
        }
        
        const className = type === 'success' 
            ? 'bg-green-100 border-green-300 text-green-800' 
            : 'bg-red-100 border-red-300 text-red-800';
        
        messageContainer.innerHTML = `
            <div class="p-4 rounded-lg border ${className} shadow-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        ${type === 'success' 
                            ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                            : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
                        }
                    </svg>
                    <span class="font-medium">${message}</span>
                </div>
            </div>
        `;
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (messageContainer) {
                messageContainer.remove();
            }
        }, 5000);
    }
    
    async refreshCSRFToken() {
        try {
            const response = await fetch('/api/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                if (csrfMeta && data.csrf_token) {
                    csrfMeta.setAttribute('content', data.csrf_token);
                }
                const tokenInput = document.querySelector('input[name="_token"]');
                if (tokenInput && data.csrf_token) {
                    tokenInput.value = data.csrf_token;
                }
            }
        } catch (error) {
            console.warn('Failed to refresh CSRF token:', error);
        }
    }
}

// Initialize smart checkout
const smartCheckout = new SmartCheckout();
</script>
@endsection 