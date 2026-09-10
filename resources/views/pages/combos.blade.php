@extends('layouts.app')

@section('title', 'Diwali Value Combo Offers - Radhe Crackers Sivakasi')

@section('content')
<div class="bg-gray-50 min-h-screen pb-24">
    <!-- Festive Hero Header -->
    <div class="relative overflow-hidden text-white py-12 md:py-16 shadow-lg" style="background: radial-gradient(circle at 50% 30%, #3B1270 0%, #1E093B 70%, #120424 100%);">
        <!-- Decorative Glow & Sparks -->
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#F59E0B 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
            <!-- Top Badges -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 font-bold text-xs sm:text-sm uppercase tracking-wider mb-4 shadow-sm">
                <span>🪔</span>
                <span>Exclusive Diwali Value Offers • Only Branded Items</span>
                <span>🎆</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-3">
                Radhe Crackers <span class="text-amber-400">Combo Packs</span>
            </h1>

            <!-- Tamil Greeting Tagline from Flyer -->
            <p class="text-base sm:text-lg md:text-xl font-medium text-amber-200/95 max-w-3xl mx-auto mb-6">
                "பட்டாசு ஒளியில் உங்கள் சந்தோஷம் இன்னும் பிரகாசமாகட்டும்!"
            </p>

            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl mx-auto mb-8">
                Carefully curated family celebration combos with exact excel item lists. Direct factory pricing with zero hidden costs. What you see is exactly what you get!
            </p>

            <!-- 4 Trust Badges from Flyer -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-w-4xl mx-auto">
                <div class="bg-white/10 backdrop-blur-sm border border-amber-400/20 rounded-xl py-2.5 px-3 flex items-center justify-center gap-2 text-xs sm:text-sm font-bold text-amber-200">
                    <span class="text-base">✨</span>
                    <span>ONLY BRANDED ITEMS</span>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-amber-400/20 rounded-xl py-2.5 px-3 flex items-center justify-center gap-2 text-xs sm:text-sm font-bold text-amber-200">
                    <span class="text-base">🛡️</span>
                    <span>PREMIUM QUALITY</span>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-amber-400/20 rounded-xl py-2.5 px-3 flex items-center justify-center gap-2 text-xs sm:text-sm font-bold text-amber-200">
                    <span class="text-base">🤝</span>
                    <span>TRUSTED BRANDS</span>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-amber-400/20 rounded-xl py-2.5 px-3 flex items-center justify-center gap-2 text-xs sm:text-sm font-bold text-amber-200">
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
                <div class="bg-white rounded-3xl shadow-xl border {{ $combo['highlight'] ? 'border-amber-400 ring-2 ring-amber-400/40' : 'border-gray-200' }} overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 relative" id="card-{{ $combo['id'] }}">
                    
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
                                <span class="text-xs font-bold text-gray-600">Pack Quantity:</span>
                                <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden shadow-sm bg-white">
                                    <button type="button" onclick="adjustComboQty('{{ $combo['id'] }}', -1)" class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 font-black text-sm active:scale-95 transition">&minus;</button>
                                    <input type="number" id="qty-{{ $combo['id'] }}" value="1" min="1" max="99" class="w-12 text-center text-sm font-black border-none focus:ring-0 p-0 text-gray-800" readonly>
                                    <button type="button" onclick="adjustComboQty('{{ $combo['id'] }}', 1)" class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 font-black text-sm active:scale-95 transition">&plus;</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" 
                                        onclick="addComboToCart('{{ $combo['id'] }}', false)" 
                                        class="w-full py-3 px-3 rounded-xl font-bold text-xs sm:text-sm text-white transition-all shadow-md active:scale-95 flex items-center justify-center gap-1.5 bg-[#B67121] hover:bg-orange-600">
                                    <span>🛒 Add to Cart</span>
                                </button>
                                <button type="button" 
                                        onclick="addComboToCart('{{ $combo['id'] }}', true)" 
                                        class="w-full py-3 px-3 rounded-xl font-bold text-xs sm:text-sm text-white transition-all shadow-md active:scale-95 flex items-center justify-center gap-1.5 bg-[#1E093B] hover:bg-[#2D0B5A]">
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

            <div class="mt-2">
                <button onclick="proceedToCheckout()" class="w-full text-white py-3 rounded-xl text-xs sm:text-sm font-bold shadow transition-colors flex items-center justify-center gap-1 bg-[#1E093B] hover:bg-opacity-90">
                    <span>Proceed to Checkout</span>
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

function adjustComboQty(comboId, delta) {
    const input = document.getElementById('qty-' + comboId);
    if (!input) return;
    let val = parseInt(input.value || 1) + delta;
    val = Math.max(1, Math.min(99, val));
    input.value = val;
}

function getCart() {
    return JSON.parse(localStorage.getItem('cartItems') || '[]');
}

function saveCart(cart) {
    localStorage.setItem('cartItems', JSON.stringify(cart));
    syncCartUI();
}

function addComboToCart(comboId, proceedNow = false) {
    const combo = comboData[comboId];
    if (!combo) return;

    const qtyInput = document.getElementById('qty-' + comboId);
    const qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;

    let cart = getCart();
    
    // Check if combo already in cart
    const existingIndex = cart.findIndex(item => item.product_id === combo.numeric_id || item.product_id === combo.id || item.is_combo_id === combo.id);

    if (existingIndex > -1) {
        if (proceedNow) {
            cart[existingIndex].quantity = qty;
        } else {
            cart[existingIndex].quantity += qty;
        }
        cart[existingIndex].total = cart[existingIndex].quantity * combo.offer_price;
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
            quantity: qty,
            total: combo.offer_price * qty
        });
    }

    saveCart(cart);

    if (proceedNow) {
        proceedToCheckout();
    } else {
        // Visual confirmation toast
        showToast(`Added ${qty}x ${combo.name} to your cart!`);
        // Expand drawer temporarily
        const panel = document.getElementById('cart-summary-panel');
        if (panel && panel.classList.contains('hidden')) {
            toggleCartDrawer();
        }
    }
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

    if (!cart || cart.length === 0) {
        if (wrapper) wrapper.style.display = 'none';
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
            itemRow.className = 'pt-2 flex items-center justify-between text-xs gap-2';
            itemRow.innerHTML = `
                <div class="flex-1 truncate">
                    <div class="font-bold text-gray-800 truncate">${item.product_name || item.name}</div>
                    <div class="text-[10px] text-gray-500">${item.content || (item.is_combo ? 'Diwali Combo Pack' : '')}</div>
                    <div class="font-extrabold text-amber-700">₹${linePayable.toFixed(2)}</div>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <button onclick="updateCartItemQty(${index}, ${qty - 1})" class="w-5 h-5 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold flex items-center justify-center">&minus;</button>
                    <span class="font-bold text-xs w-5 text-center">${qty}</span>
                    <button onclick="updateCartItemQty(${index}, ${qty + 1})" class="w-5 h-5 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold flex items-center justify-center">&plus;</button>
                    <button onclick="removeCartItem(${index})" class="text-red-500 hover:text-red-700 ml-1 font-bold">&times;</button>
                </div>
            `;
            itemsList.appendChild(itemRow);
        }
    });

    const payableItemsSubtotal = regularPayableSubtotal + comboPayableSubtotal;
    // No delivery/packing fee for combos! 5% packing applies ONLY to regular products
    const packing = regularPayableSubtotal * 0.05;
    const finalTotal = payableItemsSubtotal + packing;

    if (badgeTotal) badgeTotal.textContent = '₹' + Math.round(finalTotal).toLocaleString('en-IN');
    if (badgeCount) badgeCount.textContent = totalItemsQty;
    if (itemsCount) itemsCount.textContent = totalItemsQty;
    if (summarySubtotal) summarySubtotal.textContent = '₹' + payableItemsSubtotal.toFixed(2);
    if (summaryPacking) {
        const packingLabel = document.getElementById('packing-label');
        if (packing === 0 && comboPayableSubtotal > 0) {
            if (packingLabel) packingLabel.textContent = 'Delivery & Packing:';
            summaryPacking.innerHTML = '<span class="text-emerald-700 font-extrabold bg-emerald-50 border border-emerald-300 px-2.5 py-0.5 rounded-full text-xs">All-Inclusive</span>';
        } else if (packing > 0 && comboPayableSubtotal > 0) {
            if (packingLabel) packingLabel.textContent = 'Packing (+5% regular items):';
            summaryPacking.textContent = '₹' + packing.toFixed(2);
        } else {
            if (packingLabel) packingLabel.textContent = 'Delivery/Packing Fee (+5%):';
            summaryPacking.textContent = '₹' + packing.toFixed(2);
        }
    }
    if (summaryTotal) summaryTotal.textContent = '₹' + Math.round(finalTotal).toLocaleString('en-IN');
}

function proceedToCheckout() {
    window.location.href = "{{ route('smart-checkout.show') }}";
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
