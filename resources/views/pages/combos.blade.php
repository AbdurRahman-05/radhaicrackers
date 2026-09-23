@extends('layouts.app')

@section('title', 'Diwali Value Combo Offers - Radhe Crackers Sivakasi')

@section('content')
<div class="bg-gray-50 min-h-screen pb-24">
    <!-- Festive Combo Hero Banner -->
    <div class="relative bg-gradient-to-b from-[#120424] via-[#1E093B] to-[#120424] py-4 sm:py-6 border-b border-amber-500/20 shadow-xl overflow-hidden">
        <!-- Background subtle sparkle glow -->
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#F59E0B 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="max-w-7xl mx-auto px-3 sm:px-4 relative z-10">
            <!-- Graphical Combo Packs Banner -->
            <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl border-2 border-amber-400/40 bg-gray-950 transition-all duration-300 hover:border-amber-400/70 hover:shadow-amber-500/10">
                <img src="{{ asset('images/radhe_crackers_images_2026/combo packs banner.png') }}" 
                     alt="Radhe Crackers Diwali Combo Packs - ₹3,000, ₹5,000, ₹8,000" 
                     class="w-full h-auto object-cover block"
                     loading="eager">
            </div>

            <!-- Quick Navigation Action Pills -->
            <div class="mt-4 sm:mt-5 flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                <span class="text-xs font-bold text-amber-300 uppercase tracking-wider flex items-center gap-1">
                    <span>⚡</span> Jump to Combo:
                </span>
                <a href="#card-combo_3k" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full bg-gradient-to-r from-amber-600 to-yellow-600 hover:from-amber-500 hover:to-yellow-500 text-white font-extrabold text-xs sm:text-sm shadow-md transition-all hover:scale-105 active:scale-95 border border-amber-300/40">
                    <span>🧨</span> ₹3,000 Family Pack
                </a>
                <a href="#card-combo_5k" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold text-xs sm:text-sm shadow-md transition-all hover:scale-105 active:scale-95 border border-blue-300/40">
                    <span>🔥</span> ₹5,000 Mega Festive (Popular)
                </a>
                <a href="#card-combo_8k" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-extrabold text-xs sm:text-sm shadow-md transition-all hover:scale-105 active:scale-95 border border-purple-300/40">
                    <span>👑</span> ₹8,000 Grand Royal
                </a>
            </div>

            <!-- 4 Trust Badges from Flyer -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5 sm:gap-3 mt-4">
                <div class="bg-white/10 backdrop-blur-md border border-amber-400/20 rounded-xl py-2 px-3 flex items-center justify-center gap-2 text-xs sm:text-sm font-bold text-amber-200 shadow-sm">
                    <span class="text-base">✨</span>
                    <span>ONLY BRANDED ITEMS</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-amber-400/20 rounded-xl py-2 px-3 flex items-center justify-center gap-2 text-xs sm:text-sm font-bold text-amber-200 shadow-sm">
                    <span class="text-base">🛡️</span>
                    <span>PREMIUM QUALITY</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-amber-400/20 rounded-xl py-2 px-3 flex items-center justify-center gap-2 text-xs sm:text-sm font-bold text-amber-200 shadow-sm">
                    <span class="text-base">🤝</span>
                    <span>TRUSTED BRANDS</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-amber-400/20 rounded-xl py-2 px-3 flex items-center justify-center gap-2 text-xs sm:text-sm font-bold text-amber-200 shadow-sm">
                    <span class="text-base">👍</span>
                    <span>BEST VALUE</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb & Logged-In User Row (matching Quotation / Estimate) -->
    <div class="max-w-7xl mx-auto px-4 mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <nav class="text-xs sm:text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="list-reset flex items-center gap-2">
                <li><a href="{{ route('home') }}" class="hover:underline text-gray-600">Home</a></li>
                <li><span>/</span></li>
                <li class="text-amber-800 font-bold">Diwali Combos</li>
            </ol>
        </nav>
        @auth
            <div class="text-xs text-gray-700 font-medium flex items-center gap-1.5 bg-amber-50 border border-amber-300/70 px-3 py-1 rounded-full shadow-sm w-fit">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Welcome, <strong class="text-gray-900">{{ auth()->user()->name ?? 'Customer' }}</strong> @if(!empty(auth()->user()->mobile))({{ auth()->user()->mobile }})@endif</span>
            </div>
        @endauth
    </div>

    <!-- Pricing Policy Notification Banner -->
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="bg-gradient-to-r from-amber-50 via-orange-50 to-amber-50 border border-amber-300 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 text-xl font-bold shadow-sm">
                    🏷️
                </div>
                <div>
                    <h3 class="font-extrabold text-amber-950 text-sm sm:text-base">
                        Special All-Inclusive Combo Offer Pricing
                    </h3>
                    <p class="text-xs text-amber-800/90 leading-relaxed">
                        These combo packs are fully all-inclusive with direct factory offer prices. Delivery & packaging charges are already included in the combo pack price — no additional packing fees added at checkout.
                    </p>
                </div>
            </div>
            <a href="{{ route('express-shop') }}" class="inline-flex items-center gap-1 text-xs font-bold text-amber-900 bg-amber-200 hover:bg-amber-300 px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                <span>Want individual items? Browse Quotation</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>

    <!-- Combos Grid -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            @foreach($combos as $comboId => $combo)
                <div class="bg-white rounded-3xl shadow-xl border {{ $combo['highlight'] ? 'border-amber-400 ring-2 ring-amber-400/40' : 'border-gray-200' }} overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 relative scroll-mt-6" id="card-{{ $combo['id'] }}">
                    
                    <!-- Top Ribbon -->
                    @if($combo['highlight'])
                        <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 text-white text-center py-1.5 px-4 text-xs font-black uppercase tracking-widest shadow">
                            ⭐ {{ $combo['badge'] }} ⭐
                        </div>
                    @else
                        <div class="bg-[#1E093B] text-amber-300 text-center py-1.5 px-4 text-xs font-extrabold uppercase tracking-wider">
                            {{ $combo['badge'] }}
                        </div>
                    @endif

                    <!-- Card Header -->
                    <div class="p-6 text-center border-b border-gray-100 bg-gradient-to-b from-gray-50 to-white">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold tracking-wide uppercase mb-2 {{ $combo['highlight'] ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-gray-100 text-gray-700' }}">
                            {{ $combo['tagline'] }}
                        </span>
                        <h2 class="text-2xl font-black text-gray-900 mb-2">
                            {{ $combo['name'] }}
                        </h2>
                        <p class="text-xs text-gray-500 line-clamp-2 px-2">
                            {{ $combo['description'] }}
                        </p>

                        <!-- Big Price Display -->
                        <div class="mt-5 p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80">
                            <div class="flex items-center justify-center gap-3 text-xs text-gray-500 font-semibold mb-1">
                                <span>TOTAL VALUE:</span>
                                <span class="line-through text-red-500 font-bold text-sm">₹{{ number_format($combo['original_price']) }}</span>
                            </div>
                            <div class="text-3xl sm:text-4xl font-black text-[#1E093B] tracking-tight">
                                <span class="text-lg font-bold text-amber-600">₹</span>{{ number_format($combo['offer_price']) }}
                            </div>
                            <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-600 text-white text-xs font-black tracking-wider uppercase shadow-sm">
                                <span>SAVE ₹{{ number_format($combo['savings']) }}</span>
                                <span class="text-[10px] bg-green-700 px-1.5 py-0.5 rounded">LIMITED OFFER</span>
                            </div>
                            <div class="mt-2">
                                <span class="inline-block text-[11px] font-bold text-amber-900 bg-amber-100/90 border border-amber-300/80 px-2.5 py-0.5 rounded-full">
                                    ✨ All-Inclusive Price • Delivery & Packing Included
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Items Quick Highlights -->
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between text-xs font-bold text-gray-700 pb-2 border-b border-gray-100">
                                <span class="flex items-center gap-1.5">
                                    <span class="text-base">📦</span>
                                    <span>Exact Excel Items Included:</span>
                                </span>
                                <span class="text-amber-700 font-extrabold">{{ $combo['total_products'] }} Varieties</span>
                            </div>

                            <!-- Sample 5 items preview -->
                            <ul class="text-xs text-gray-600 space-y-1.5 divide-y divide-gray-50">
                                @foreach(array_slice($combo['items'], 0, 5) as $previewItem)
                                    <li class="pt-1.5 flex items-center justify-between">
                                        <span class="font-medium text-gray-800 truncate max-w-[200px]">{{ $previewItem['no'] }}. {{ $previewItem['name'] }}</span>
                                        <span class="text-gray-500 font-semibold flex-shrink-0">{{ $previewItem['pack'] }} &times; {{ $previewItem['qty'] }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <button type="button" 
                                    onclick="openComboModal('{{ $combo['id'] }}')" 
                                    class="w-full text-center py-2.5 px-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-[#1E093B] font-bold text-xs transition-colors flex items-center justify-center gap-2 cursor-pointer mt-3">
                                <span>📋 View Complete {{ $combo['total_products'] }} Items List</span>
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>

                        <!-- Add to Cart & Buy Controls -->
                        <div class="pt-4 border-t border-gray-100 space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-gray-600">Pack Quantity:</span>
                                    <span id="status-badge-{{ $combo['id'] }}" class="hidden text-[10px] font-extrabold text-emerald-700 bg-emerald-100 border border-emerald-300 px-2 py-0.5 rounded-full">In Cart: 1</span>
                                </div>
                                <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden shadow-sm bg-white transition-all" id="qty-box-{{ $combo['id'] }}">
                                    <button type="button" 
                                            id="btn-minus-{{ $combo['id'] }}" 
                                            onclick="adjustComboQty('{{ $combo['id'] }}', -1)" 
                                            class="px-3 py-1.5 text-gray-300 font-black text-sm active:scale-95 transition disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer" 
                                            disabled 
                                            title="Decrease quantity">&minus;</button>
                                    <input type="number" 
                                           id="qty-{{ $combo['id'] }}" 
                                           value="0" 
                                           min="0" 
                                           max="99" 
                                           class="w-12 text-center text-sm font-bold border-none focus:ring-0 p-0 text-gray-400 bg-transparent" 
                                           readonly>
                                    <button type="button" 
                                            id="btn-plus-{{ $combo['id'] }}" 
                                            onclick="adjustComboQty('{{ $combo['id'] }}', 1)" 
                                            class="px-3 py-1.5 text-amber-700 hover:bg-amber-100 font-black text-sm active:scale-95 transition cursor-pointer" 
                                            title="Click to directly add to cart">&plus;</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" 
                                        id="btn-add-{{ $combo['id'] }}" 
                                        onclick="addComboBtnClicked('{{ $combo['id'] }}')" 
                                        class="w-full py-3 px-3 rounded-xl font-bold text-xs sm:text-sm text-white transition-all shadow-md active:scale-95 flex items-center justify-center gap-1.5 bg-[#B67121] hover:bg-orange-600 cursor-pointer">
                                    <span id="btn-add-text-{{ $combo['id'] }}">🛒 Add to Cart</span>
                                </button>
                                <button type="button" 
                                        onclick="buyComboNow('{{ $combo['id'] }}')" 
                                        class="w-full py-3 px-3 rounded-xl font-bold text-xs sm:text-sm text-white transition-all shadow-md active:scale-95 flex items-center justify-center gap-1.5 bg-[#1E093B] hover:bg-[#2D0B5A] cursor-pointer">
                                    <span>⚡ Buy Now</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modals for Full Exact Item Lists -->
    @foreach($combos as $comboId => $combo)
        <div id="modal-{{ $combo['id'] }}" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title-{{ $combo['id'] }}" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-70 transition-opacity" onclick="closeComboModal('{{ $combo['id'] }}')"></div>

            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-3xl sm:w-full border border-amber-300 flex flex-col max-h-[90vh]">
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-4 text-white flex items-center justify-between" style="background-color: #1E093B;">
                        <div>
                            <div class="text-xs font-bold text-amber-300 uppercase tracking-widest">
                                {{ $combo['tagline'] }}
                            </div>
                            <h3 class="text-xl sm:text-2xl font-black text-white" id="modal-title-{{ $combo['id'] }}">
                                {{ $combo['name'] }}
                            </h3>
                        </div>
                        <button type="button" onclick="closeComboModal('{{ $combo['id'] }}')" class="text-gray-300 hover:text-white p-1 rounded-lg hover:bg-white/10 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Modal Price Strip -->
                    <div class="bg-amber-50 px-6 py-3 border-b border-amber-200 flex flex-wrap items-center justify-between gap-3 text-xs sm:text-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-gray-500 font-semibold">Total Value: <span class="line-through text-red-500 font-bold">₹{{ number_format($combo['original_price']) }}</span></span>
                            <span class="text-amber-900 font-extrabold text-base">Offer: ₹{{ number_format($combo['offer_price']) }}</span>
                        </div>
                        <span class="bg-green-600 text-white font-extrabold text-xs px-2.5 py-1 rounded-full">
                            Save ₹{{ number_format($combo['savings']) }} Limited Offer
                        </span>
                    </div>

                    <!-- Search within items -->
                    <div class="px-6 pt-4 pb-2">
                        <div class="relative">
                            <input type="text" 
                                   id="search-{{ $combo['id'] }}" 
                                   placeholder="Search products in this combo..." 
                                   oninput="filterComboItems('{{ $combo['id'] }}')"
                                   class="w-full text-xs sm:text-sm pl-9 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>

                    <!-- Scrollable Table of All Items -->
                    <div class="flex-1 overflow-y-auto px-6 py-2">
                        <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-3 py-2.5 text-left font-bold text-gray-600 w-12">NO</th>
                                    <th class="px-4 py-2.5 text-left font-bold text-gray-600">PRODUCT NAME</th>
                                    <th class="px-4 py-2.5 text-left font-bold text-gray-600">PACK CONTENT</th>
                                    <th class="px-3 py-2.5 text-center font-bold text-gray-600 w-16">QTY</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white" id="table-body-{{ $combo['id'] }}">
                                @foreach($combo['items'] as $item)
                                    <tr class="hover:bg-amber-50/50 transition combo-row">
                                        <td class="px-3 py-2 text-gray-500 font-bold">{{ $item['no'] }}</td>
                                        <td class="px-4 py-2 font-bold text-gray-800 item-name">{{ $item['name'] }}</td>
                                        <td class="px-4 py-2 text-gray-600">{{ $item['pack'] }}</td>
                                        <td class="px-3 py-2 text-center font-extrabold text-amber-700 bg-amber-50/40">{{ $item['qty'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-xs text-gray-500">
                            Total: <strong class="text-gray-800">{{ $combo['total_products'] }} Products • {{ $combo['total_qty'] }} Qty</strong>
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <button type="button" onclick="closeComboModal('{{ $combo['id'] }}')" class="w-full sm:w-auto px-4 py-2 text-xs font-bold text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 transition">
                                Close
                            </button>
                            <button type="button" onclick="addComboToCart('{{ $combo['id'] }}', true)" class="w-full sm:w-auto px-5 py-2 text-xs font-bold text-white bg-[#1E093B] hover:bg-[#2D0B5A] rounded-xl transition shadow">
                                Buy This Combo (₹{{ number_format($combo['offer_price']) }})
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Detailed Cart Summary Drawer / Floating Pill Widget (Synchronized with Site Cart) -->
<div class="fixed bottom-4 right-4 z-50 max-w-sm w-[calc(100vw-32px)] sm:w-96 font-sans flex flex-col items-end gap-2 pointer-events-none" id="cart-summary-wrapper" style="display: none;">
    <!-- Floating Sticky Cart Pill Button -->
    <button onclick="toggleCartDrawer()" id="cart-badge-trigger" class="pointer-events-auto flex items-center gap-3 bg-gradient-to-r from-amber-500 via-orange-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-full px-4 sm:px-5 py-2.5 shadow-2xl hover:shadow-orange-500/50 transition-all duration-300 hover:scale-105 border-2 border-white cursor-pointer ml-auto" title="Click to view cart items and checkout">
        <svg class="w-6 h-6 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
        </svg>
        <span id="cart-badge-total" class="font-extrabold text-sm sm:text-base text-white tracking-wide">₹0.00</span>
        <span class="bg-white text-orange-600 font-extrabold text-xs sm:text-sm min-w-[28px] h-7 px-1.5 rounded-full flex items-center justify-center shadow-md" id="cart-badge-count">0</span>
    </button>

    <!-- Cart Drawer Panel -->
    <div id="cart-summary-panel" class="hidden pointer-events-auto w-full bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all duration-300 flex flex-col max-h-[500px]">
        <div class="px-4 py-3 text-white flex items-center justify-between" style="background-color: #1E093B;">
            <div class="flex items-center gap-2">
                <span class="text-xl">🛒</span>
                <span class="font-bold text-sm sm:text-base">Cart Items (<span id="cart-items-count">0</span>)</span>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="clearCart()" class="text-xs text-red-300 hover:text-red-200 transition font-semibold uppercase tracking-wider">Clear</button>
                <button onclick="toggleCartDrawer()" class="text-gray-300 hover:text-white transition" title="Minimize">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3 divide-y divide-gray-50 max-h-60" id="cart-items-list">
            <!-- Populated dynamically via JS -->
        </div>

        <div class="bg-gray-50 border-t border-gray-100 p-4 space-y-2">
            <div class="flex justify-between text-xs sm:text-sm text-gray-600">
                <span>Items Subtotal:</span>
                <span id="summary-subtotal">₹0.00</span>
            </div>
            <div class="flex justify-between items-center text-xs sm:text-sm font-medium" id="packing-fee-row">
                <span id="packing-label" class="text-gray-600">Delivery & Packing:</span>
                <span id="summary-packing" class="text-orange-600">₹0.00</span>
            </div>
            <hr class="border-gray-200">
            <div class="flex justify-between text-base sm:text-lg font-extrabold text-gray-900">
                <span>Total Amount:</span>
                <span id="summary-total">₹0.00</span>
            </div>

            <div class="grid grid-cols-2 gap-2 mt-2">
                <button onclick="generateEstimate()" id="summary-estimate-btn" class="text-white py-3 rounded-xl text-xs sm:text-sm font-bold shadow transition-colors flex items-center justify-center bg-[#B67121] hover:bg-orange-600">
                    Estimate PDF
                </button>
                <button onclick="proceedToCheckout()" class="text-white py-3 rounded-xl text-xs sm:text-sm font-bold shadow transition-colors flex items-center justify-center gap-1 bg-[#1E093B] hover:bg-opacity-90">
                    <span>Checkout</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const comboData = @json($combos);

function openComboModal(comboId) {
    const modal = document.getElementById('modal-' + comboId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeComboModal(comboId) {
    const modal = document.getElementById('modal-' + comboId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

function filterComboItems(comboId) {
    const query = (document.getElementById('search-' + comboId)?.value || '').toLowerCase().trim();
    const rows = document.querySelectorAll('#table-body-' + comboId + ' tr');
    rows.forEach(row => {
        const nameText = row.querySelector('.item-name')?.textContent?.toLowerCase() || '';
        if (nameText.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function updateComboCardVisualState(comboId, qty) {
    const input = document.getElementById('qty-' + comboId);
    const minusBtn = document.getElementById('btn-minus-' + comboId);
    const badge = document.getElementById('status-badge-' + comboId);
    const qtyBox = document.getElementById('qty-box-' + comboId);
    const addBtnText = document.getElementById('btn-add-text-' + comboId);

    if (input) {
        input.value = qty;
        if (qty > 0) {
            input.classList.remove('text-gray-400');
            input.classList.add('text-gray-900', 'font-black');
        } else {
            input.classList.remove('text-gray-900', 'font-black');
            input.classList.add('text-gray-400', 'font-bold');
        }
    }

    if (minusBtn) {
        if (qty > 0) {
            minusBtn.disabled = false;
            minusBtn.classList.remove('text-gray-300', 'cursor-not-allowed', 'opacity-30');
            minusBtn.classList.add('text-gray-700', 'hover:bg-gray-100');
        } else {
            minusBtn.disabled = true;
            minusBtn.classList.remove('text-gray-700', 'hover:bg-gray-100');
            minusBtn.classList.add('text-gray-300', 'cursor-not-allowed', 'opacity-30');
        }
    }

    if (badge) {
        if (qty > 0) {
            badge.textContent = `In Cart: ${qty}`;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    if (qtyBox) {
        if (qty > 0) {
            qtyBox.classList.add('border-amber-500', 'ring-2', 'ring-amber-400/40', 'bg-amber-50/20');
            qtyBox.classList.remove('border-gray-300');
        } else {
            qtyBox.classList.remove('border-amber-500', 'ring-2', 'ring-amber-400/40', 'bg-amber-50/20');
            qtyBox.classList.add('border-gray-300');
        }
    }

    if (addBtnText) {
        if (qty > 0) {
            addBtnText.textContent = `✓ In Cart (${qty}) • Add +`;
        } else {
            addBtnText.textContent = `🛒 Add to Cart`;
        }
    }
}

function adjustComboQty(comboId, delta) {
    const combo = comboData[comboId];
    if (!combo) return;

    let cart = getCart();
    const existingIndex = cart.findIndex(item => item.product_id === combo.numeric_id || item.product_id === combo.id || item.is_combo_id === combo.id);
    let currentQty = existingIndex > -1 ? parseInt(cart[existingIndex].quantity || 0) : 0;

    let newQty = currentQty + delta;
    if (newQty < 0) newQty = 0;
    if (newQty > 99) newQty = 99;

    if (newQty === currentQty && delta < 0 && currentQty === 0) {
        return;
    }

    if (newQty === 0) {
        if (existingIndex > -1) {
            cart.splice(existingIndex, 1);
            saveCart(cart);
            showToast(`Removed ${combo.name} from cart`);
        }
    } else {
        if (existingIndex > -1) {
            cart[existingIndex].quantity = newQty;
            cart[existingIndex].total = newQty * combo.offer_price;
        } else {
            cart.push({
                product_id: combo.numeric_id,
                is_combo_id: combo.id,
                product_name: combo.name,
                content: `${combo.total_products} Products (${combo.total_qty} Items)`,
                rate: combo.offer_price,
                price: combo.offer_price,
                original_price: combo.offer_price,
                is_combo: true,
                quantity: newQty,
                total: combo.offer_price * newQty
            });
        }
        saveCart(cart);

        if (delta > 0 && currentQty === 0) {
            showToast(`Added 1x ${combo.name} directly to your cart! 🎆`);
            const panel = document.getElementById('cart-summary-panel');
            if (panel && panel.classList.contains('hidden')) {
                toggleCartDrawer();
            }
        } else if (delta > 0) {
            showToast(`Updated ${combo.name} quantity to ${newQty}! 🎆`);
        } else {
            showToast(`Updated ${combo.name} quantity to ${newQty}`);
        }
    }

    syncCartUI();
}

function addComboBtnClicked(comboId) {
    adjustComboQty(comboId, 1);
}

function buyComboNow(comboId) {
    const combo = comboData[comboId];
    if (!combo) return;

    let cart = getCart();
    const existingIndex = cart.findIndex(item => item.product_id === combo.numeric_id || item.product_id === combo.id || item.is_combo_id === combo.id);

    if (existingIndex === -1) {
        cart.push({
            product_id: combo.numeric_id,
            is_combo_id: combo.id,
            product_name: combo.name,
            content: `${combo.total_products} Products (${combo.total_qty} Items)`,
            rate: combo.offer_price,
            price: combo.offer_price,
            original_price: combo.offer_price,
            is_combo: true,
            quantity: 1,
            total: combo.offer_price
        });
        saveCart(cart);
    }
    proceedToCheckout();
}

function addComboToCart(comboId, proceedNow = false) {
    if (proceedNow) {
        buyComboNow(comboId);
    } else {
        adjustComboQty(comboId, 1);
    }
}

function getCart() {
    return JSON.parse(localStorage.getItem('cartItems') || '[]');
}

function saveCart(cart) {
    localStorage.setItem('cartItems', JSON.stringify(cart));
    syncCartUI();
}

function toggleCartDrawer() {
    const panel = document.getElementById('cart-summary-panel');
    if (panel) {
        panel.classList.toggle('hidden');
    }
}

function clearCart() {
    if (confirm('Are you sure you want to clear your cart?')) {
        localStorage.removeItem('cartItems');
        syncCartUI();
    }
}

function removeCartItem(index) {
    let cart = getCart();
    if (index >= 0 && index < cart.length) {
        cart.splice(index, 1);
        saveCart(cart);
    }
}

function updateCartItemQty(index, newQty) {
    let cart = getCart();
    if (index >= 0 && index < cart.length) {
        newQty = parseInt(newQty);
        if (newQty <= 0) {
            cart.splice(index, 1);
        } else {
            cart[index].quantity = newQty;
            const unitPrice = Number(cart[index].price || cart[index].rate || cart[index].original_price || 0);
            cart[index].total = unitPrice * newQty;
        }
        saveCart(cart);
    }
}

function syncCartUI() {
    const cart = getCart();
    const wrapper = document.getElementById('cart-summary-wrapper');
    const badgeTotal = document.getElementById('cart-badge-total');
    const badgeCount = document.getElementById('cart-badge-count');
    const itemsCount = document.getElementById('cart-items-count');
    const itemsList = document.getElementById('cart-items-list');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryPacking = document.getElementById('summary-packing');
    const summaryTotal = document.getElementById('summary-total');

    // Sync all combo cards on page with localStorage cart
    if (typeof comboData === 'object' && comboData !== null) {
        Object.keys(comboData).forEach(comboId => {
            const combo = comboData[comboId];
            const cartItem = (cart && Array.isArray(cart)) ? cart.find(item => item.product_id === combo.numeric_id || item.product_id === combo.id || item.is_combo_id === combo.id) : null;
            const qty = cartItem ? parseInt(cartItem.quantity || 0) : 0;
            updateComboCardVisualState(comboId, qty);
        });
    }

    if (!cart || cart.length === 0) {
        if (wrapper) wrapper.style.display = 'none';
        const panel = document.getElementById('cart-summary-panel');
        if (panel) panel.classList.add('hidden');
        return;
    }

    if (wrapper) wrapper.style.display = 'flex';

    let totalItemsQty = 0;
    let regularPayableSubtotal = 0;
    let comboPayableSubtotal = 0;

    if (itemsList) itemsList.innerHTML = '';

    cart.forEach((item, index) => {
        const qty = parseInt(item.quantity || 1);
        totalItemsQty += qty;

        // Combos are consumed at net price (no 70% + 15% reduction)
        // Regular items: original_price gets discounted to rate
        let unitPayable = 0;
        if (item.is_combo) {
            unitPayable = Number(item.price || item.rate || item.original_price || 0);
            comboPayableSubtotal += unitPayable * qty;
        } else {
            // For regular items, effective rate is original_price * 0.255
            const orig = Number(item.original_price || item.rate || 0);
            unitPayable = orig * 0.255;
            regularPayableSubtotal += unitPayable * qty;
        }

        const linePayable = unitPayable * qty;

        if (itemsList) {
            const itemRow = document.createElement('div');
            itemRow.className = 'py-2.5 text-xs sm:text-sm';
            itemRow.innerHTML = `
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 pr-1 text-left">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="font-semibold text-gray-900 leading-tight block text-left">${item.product_name || item.name}</span>
                            ${item.is_combo ? '<span class="px-1.5 py-0.2 bg-amber-100 text-amber-900 border border-amber-300 font-extrabold text-[9px] uppercase rounded-full">Combo Pack</span>' : ''}
                        </div>
                        ${item.content ? `<div class="text-[10px] text-gray-500 mt-0.5">${item.content}</div>` : ''}
                    </div>
                    <div class="text-right font-extrabold text-gray-900 flex-shrink-0 text-xs sm:text-sm">
                        ₹${linePayable.toFixed(2)}
                    </div>
                </div>
                <div class="flex items-center justify-between mt-1.5 pt-1">
                    <span class="text-gray-500 text-[11px] sm:text-xs">${qty} pcs × ₹${unitPayable.toFixed(2)}</span>
                    <div class="flex items-center space-x-1 sm:space-x-1.5">
                        <button type="button" 
                                onclick="updateCartItemQty(${index}, ${qty - 1})" 
                                class="w-6 h-6 sm:w-7 sm:h-7 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm hover:opacity-90 active:scale-95 transition-all shadow-sm flex-shrink-0 cursor-pointer select-none" 
                                style="background-color:rgb(182, 113, 33);"
                                title="Decrease quantity">
                            -
                        </button>
                        <input type="number" 
                               min="0" 
                               value="${qty}" 
                               onchange="updateCartItemQty(${index}, parseInt(this.value) || 0)" 
                               onkeydown="if(event.key==='Enter'){this.blur();}" 
                               class="w-10 sm:w-11 h-6 sm:h-7 text-center bg-gray-50 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-yellow-500 text-xs font-bold text-gray-900 p-0 shadow-inner" 
                               style="-moz-appearance: textfield; appearance: textfield; font-size: 13px;">
                        <button type="button" 
                                onclick="updateCartItemQty(${index}, ${qty + 1})" 
                                class="w-6 h-6 sm:w-7 sm:h-7 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm hover:opacity-90 active:scale-95 transition-all shadow-sm flex-shrink-0 cursor-pointer select-none" 
                                style="background-color:rgb(182, 113, 33);"
                                title="Increase quantity">
                            +
                        </button>
                        <button type="button" 
                                onclick="removeCartItem(${index})" 
                                class="ml-1 text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors flex-shrink-0 cursor-pointer" 
                                title="Remove item">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            itemsList.appendChild(itemRow);
        }
    });

    const payableItemsSubtotal = regularPayableSubtotal + comboPayableSubtotal;
    const packing = Math.round(payableItemsSubtotal * 0.05 * 100) / 100;
    const finalTotal = Math.round(payableItemsSubtotal + packing);

    if (badgeTotal) badgeTotal.textContent = '₹' + Math.round(finalTotal).toLocaleString('en-IN');
    if (badgeCount) badgeCount.textContent = totalItemsQty;
    if (itemsCount) itemsCount.textContent = totalItemsQty;
    if (summarySubtotal) summarySubtotal.textContent = '₹' + payableItemsSubtotal.toFixed(2);
    if (summaryPacking) {
        const packingLabel = document.getElementById('packing-label');
        if (packingLabel) packingLabel.textContent = 'Add packing 5%:';
        summaryPacking.textContent = '₹' + packing.toFixed(2);
    }
    if (summaryTotal) summaryTotal.textContent = '₹' + Math.round(finalTotal).toLocaleString('en-IN');
}

function proceedToCheckout() {
    window.location.href = "{{ route('smart-checkout.show') }}";
}

function generateEstimate() {
    let cart = [];
    try {
        cart = JSON.parse(localStorage.getItem('cartItems')) || [];
    } catch(e) {}

    if (!cart || cart.length === 0) {
        alert('Please select at least one product or combo pack.');
        return;
    }

    const itemsForPdf = cart.map(item => {
        const pId = parseInt(item.product_id || item.id || 0);
        const name = item.product_name || item.name || '';
        const isGift = !!(item.is_lucky_spin_gift || item.is_free_gift);
        const isCombo = !!(item.is_combo || (pId >= 999000 && pId <= 999999) || (typeof name === 'string' && name.toUpperCase().includes('COMBO')));
        const unitPrice = Number(item.price || item.rate || 0);
        const origPrice = Number(item.original_price || (isCombo ? unitPrice : (unitPrice > 0 ? Math.round(unitPrice / 0.255) : unitPrice)));
        const qty = parseInt(item.quantity || 1);

        return {
            product_id: pId,
            product_name: name,
            description: item.description || (isCombo ? 'Diwali Value Combo Pack' : ''),
            content: item.content || '',
            rate: unitPrice,
            original_price: origPrice,
            discount_percentage: item.discount_percentage || (isCombo ? 0 : 70),
            special_discount_percentage: item.special_discount_percentage || (isCombo ? 0 : 15),
            quantity: qty,
            total: isCombo ? (unitPrice * qty) : (origPrice * qty),
            is_combo: isCombo,
            is_lucky_spin_gift: isGift,
            is_free_gift: isGift
        };
    });

    const summaryBtn = document.getElementById('summary-estimate-btn');
    if (summaryBtn) summaryBtn.disabled = true;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('express-shop.estimate-pdf') }}";
    form.target = '_blank';

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = "{{ csrf_token() }}";
    form.appendChild(csrfInput);

    const itemsInput = document.createElement('input');
    itemsInput.type = 'hidden';
    itemsInput.name = 'items';
    itemsInput.value = JSON.stringify(itemsForPdf);
    form.appendChild(itemsInput);

    const customerInput = document.createElement('input');
    customerInput.type = 'hidden';
    customerInput.name = 'customer';
    customerInput.value = JSON.stringify({
        name: '',
        mobile: '',
        email: '',
        city: '',
        state: '',
        pin_code: ''
    });
    form.appendChild(customerInput);

    try {
        const rawSpin = sessionStorage.getItem('lucky_spin_result') || localStorage.getItem('lucky_spin_result');
        if (rawSpin) {
            const spin = JSON.parse(rawSpin);
            if (spin && spin.prize) {
                const prizeInput = document.createElement('input');
                prizeInput.type = 'hidden';
                prizeInput.name = 'lucky_spin_prize';
                prizeInput.value = spin.prize;
                form.appendChild(prizeInput);

                if (spin.type === 'discount' || spin.prize === '5% Discount') {
                    const discInput = document.createElement('input');
                    discInput.type = 'hidden';
                    discInput.name = 'lucky_spin_discount';
                    discInput.value = spin.discount || 0;
                    form.appendChild(discInput);
                }
            }
        }
    } catch(e) {}

    document.body.appendChild(form);
    form.submit();

    setTimeout(() => {
        document.body.removeChild(form);
        if (summaryBtn) summaryBtn.disabled = false;
    }, 1000);
}

function showToast(msg) {
    const existing = document.getElementById('combo-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'combo-toast';
    toast.className = 'fixed top-20 right-4 z-50 bg-[#1E093B] text-white px-5 py-3 rounded-2xl shadow-2xl border border-amber-400 font-bold text-sm flex items-center gap-2 transform transition-all duration-300 translate-y-2 opacity-0';
    toast.innerHTML = `<span>🎆</span><span>${msg}</span>`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
    }, 10);

    setTimeout(() => {
        toast.classList.add('translate-y-2', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Sync UI on load
document.addEventListener('DOMContentLoaded', function() {
    syncCartUI();
});
</script>
@endsection
