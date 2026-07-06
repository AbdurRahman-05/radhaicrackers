<div>
    @if ($showSuccess)
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ $message }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button wire:click="$set('showSuccess', false)" class="text-green-400 hover:text-green-600">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Product Selection -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-6">Available Products</h2>
                
                @if (empty($items))
                    <div class="text-center py-8">
                        <div class="text-4xl mb-4">📦</div>
                        <p class="text-gray-600">No products available at the moment.</p>
                    </div>
                @else
                    @php
                        $categories = collect($items)->groupBy('category');
                    @endphp
                    
                    @foreach($categories as $category => $categoryItems)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                                {{ $category }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($categoryItems as $item)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow bg-white">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-semibold text-gray-900 text-sm">{{ $item['item_name'] }}</h4>
                                        </div>
                                        @if(isset($item['description']) && $item['description'])
                                            <div class="text-xs text-gray-500 mb-1">{{ $item['description'] }}</div>
                                        @endif
                                        
                                        <div class="mb-3">
                                            @if(isset($item['original_price']) && $item['original_price'])
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <span class="line-through text-gray-400 text-sm">₹{{ number_format($item['original_price'], 0) }}</span>
                                                    @if(isset($item['discount_percentage']) && $item['discount_percentage'])
                                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">
                                                            -{{ $item['discount_percentage'] }}%
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                            <span class="text-lg font-bold text-orange-600">₹{{ number_format($item['price'], 0) }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <p class="text-xs text-gray-600">Available: {{ $item['quantity'] }} units</p>
                                            <button wire:click="addItem({{ $item['id'] }})" 
                                                    class="bg-orange-500 text-white py-2 px-4 rounded-md hover:bg-orange-600 transition-colors text-sm">
                                                Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Cart Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-xl font-semibold mb-6">Your Order</h2>
                
                @if (empty($selectedItems))
                    <div class="text-center py-8">
                        <div class="text-4xl mb-4">🛒</div>
                        <p class="text-gray-600">Your cart is empty</p>
                        <p class="text-sm text-gray-500">Add some products to get started</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($selectedItems as $item)
                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 text-sm">{{ $item['product_name'] }}</h4>
                                    <p class="text-sm text-gray-600">₹{{ number_format($item['price'], 0) }} each</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})" 
                                            class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300">
                                        -
                                    </button>
                                    <span class="w-8 text-center">{{ $item['quantity'] }}</span>
                                    <button wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})" 
                                            class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300">
                                        +
                                    </button>
                                    <button wire:click="removeItem({{ $item['id'] }})" 
                                            class="ml-2 text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center text-lg font-semibold">
                                <span>Total:</span>
                                <span class="text-orange-600">₹{{ number_format($total, 0) }}</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Order Notes (Optional)</label>
                            <textarea wire:model="notes" id="notes" rows="3" 
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                                      placeholder="Any special instructions or notes..."></textarea>
                        </div>

                        <button wire:click="placeOrder" 
                                class="w-full bg-orange-500 text-white py-3 px-4 rounded-md hover:bg-orange-600 transition-colors font-semibold">
                            Place Order
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Success Modal -->
    <div id="orderSuccessModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <div class="text-center">
                    <div class="text-4xl mb-4">🎉</div>
                    <h3 class="text-lg font-semibold mb-2">Order Placed Successfully!</h3>
                    <p class="text-gray-600 mb-4">Your order has been placed and we'll send you a WhatsApp confirmation.</p>
                    <div class="space-y-3">
                        <a href="{{ route('user.dashboard') }}" 
                           class="block w-full bg-orange-500 text-white py-2 px-4 rounded-md hover:bg-orange-600 transition-colors">
                            View Orders
                        </a>
                        <button onclick="closeOrderModal()" 
                                class="block w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 transition-colors">
                            Continue Shopping
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('order-placed', (data) => {
                document.getElementById('orderSuccessModal').classList.remove('hidden');
            });
        });

        function closeOrderModal() {
            document.getElementById('orderSuccessModal').classList.add('hidden');
        }
    </script>
</div> 