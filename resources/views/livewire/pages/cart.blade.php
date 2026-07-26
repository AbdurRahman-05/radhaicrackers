<div>
    <!-- Floating Sticky Cart Pill Button -->
    <div class="fixed bottom-6 right-6 z-40">
        <button 
            wire:click="toggleCart"
            class="bg-gradient-to-r from-amber-400 via-orange-500 to-orange-600 hover:from-amber-500 hover:to-orange-700 text-white rounded-full px-4 sm:px-5 py-2.5 shadow-2xl hover:shadow-orange-500/50 transition-all duration-300 hover:scale-105 flex items-center gap-2.5 border-2 border-white cursor-pointer"
            title="Click to view full cart items and checkout"
        >
            <!-- Cart Icon -->
            <svg class="w-6 h-6 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
            </svg>

            <!-- Outer Total Cost Always Displayed -->
            <div class="flex items-center gap-2">
                <span class="font-extrabold text-sm sm:text-base text-white tracking-wide">
                    ₹{{ number_format($total, 2) }}
                </span>
                
                <!-- White Badge Circle for Item Count -->
                <span class="bg-white text-orange-600 font-extrabold text-xs sm:text-sm min-w-[24px] h-6 px-1.5 rounded-full flex items-center justify-center shadow-md">
                    {{ $itemCount }}
                </span>
            </div>
        </button>
    </div>

    <!-- Cart Sidebar -->
    <div class="fixed inset-0 z-50 overflow-hidden {{ $showCart ? 'block' : 'hidden' }}">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="toggleCart"></div>
        
        <!-- Cart Panel -->
        <div class="absolute inset-y-0 right-0 max-w-full flex">
            <div class="w-screen max-w-md">
                <div class="h-full flex flex-col bg-white shadow-xl">
                    <!-- Cart Header -->
                    <div class="flex-1 py-6 overflow-y-auto px-4 sm:px-6">
                        <div class="flex items-start justify-between mb-6">
                            <h2 class="text-lg font-medium text-gray-900">Shopping Cart</h2>
                            <button 
                                wire:click="toggleCart"
                                class="text-gray-400 hover:text-gray-500 transition-colors"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Cart Items -->
                        @if(count($cartItems) > 0)
                            <div class="flow-root">
                                <ul class="-my-6 divide-y divide-gray-200">
                                    @foreach($cartItems as $item)
                                        <li class="py-6 flex">
                                            <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                                <span class="text-2xl">{{ $item['icon'] }}</span>
                                            </div>
                                            <div class="ml-4 flex-1 flex flex-col">
                                                <div>
                                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                                        <h3 class="text-sm">{{ $item['name'] }}</h3>
                                                        <p class="ml-4 text-sm font-bold text-gray-600">
                                                            ₹{{ number_format($item['price'] * $item['quantity'], 0) }}
                                                        </p>
                                                    </div>
                                                    <p class="mt-1 text-sm text-gray-500">{{ $item['category'] }}</p>
                                                </div>
                                                <div class="flex-1 flex items-end justify-between text-sm">
                                                    <div class="flex items-center space-x-2">
                                                        <label class="text-gray-500">Qty:</label>
                                                        <select 
                                                            wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)"
                                                            class="border border-gray-300 rounded px-2 py-1 text-sm"
                                                        >
                                                            @for($i = 1; $i <= 10; $i++)
                                                                <option value="{{ $i }}" {{ $item['quantity'] == $i ? 'selected' : '' }}>
                                                                    {{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <button 
                                                        wire:click="removeItem({{ $item['id'] }})"
                                                        class="text-gray-600 hover:text-gray-500 transition-colors"
                                                    >
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <!-- Empty Cart -->
                            <div class="text-center py-12">
                                <div class="text-6xl mb-4">🛒</div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                                <p class="text-gray-500 mb-6">Add some amazing fireworks to get started!</p>
                                <button 
                                    wire:click="toggleCart"
                                    class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-gray-600 hover:to-gray-700 transition-all duration-200"
                                >
                                    Start Shopping
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Cart Footer -->
                    @if(count($cartItems) > 0)
                        <div class="border-t border-gray-200 py-6 px-4 sm:px-6">
                            <div class="flex justify-between text-base font-medium text-gray-900 mb-4">
                                <p>Subtotal</p>
                                <p>₹{{ number_format($total, 0) }}</p>
                            </div>
                            <p class="mt-0.5 text-sm text-gray-500 mb-6">
                                Shipping and taxes will be calculated at checkout.
                            </p>
                            <div class="space-y-3">
                                <button 
                                    wire:click="checkout"
                                    class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-gray-600 hover:to-gray-700 transition-all duration-200"
                                >
                                    Proceed to Checkout
                                </button>
                                <button 
                                    wire:click="clearCart"
                                    class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-200 transition-all duration-200"
                                >
                                    Clear Cart
                                </button>
                            </div>
                            <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                                <p>
                                    or
                                    <button 
                                        wire:click="toggleCart"
                                        class="text-gray-600 font-medium hover:text-gray-500 transition-colors"
                                    >
                                        Continue Shopping
                                    </button>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> 