<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Special Offers</h3>
        <p class="text-sm text-gray-600">Limited time deals on premium fireworks</p>
    </div>

    <!-- Offers List -->
    <div class="flex-1 overflow-y-auto">
        @if(count($offers) > 0)
            <div class="space-y-4">
                @foreach($offers as $offer)
                    <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start space-x-3">
                            <!-- Product Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">{{ $offer['icon'] }}</span>
                                </div>
                            </div>
                            
                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 mb-1 truncate">
                                    {{ $offer['name'] }}
                                </h4>
                                @if($offer['description'])
                                    <p class="text-xs text-gray-600 mb-2">{{ $offer['description'] }}</p>
                                @endif
                                
                                <!-- Price and Discount -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        @if($offer['original_price'])
                                            <span class="text-xs text-gray-400 line-through">
                                                ₹{{ number_format($offer['original_price'], 0) }}
                                            </span>
                                        @endif
                                        <span class="text-lg font-bold text-gray-600">
                                            ₹{{ number_format($offer['price'], 0) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Discount Badge -->
                                    <div class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        -{{ $offer['discount'] }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <div class="mt-3">
                            <a href="{{ route('order.form') }}" 
                               class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white text-sm font-semibold py-2 px-4 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-200 text-center block">
                                Order Now
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="text-4xl mb-4">🎆</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Special Offers</h3>
                <p class="text-sm text-gray-600">Check back later for amazing deals!</p>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="bg-gradient-to-r from-yellow-50 to-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-gray-900 mb-2">🎉 Special Promotion</h4>
            <p class="text-xs text-gray-600 mb-3">
                Free shipping on orders over ₹199 across India!
            </p>
            <a href="{{ route('order.form') }}" 
               class="w-full bg-gradient-to-r from-blue-800 to-gray-600 text-white text-sm font-semibold py-2 px-4 rounded-lg hover:from-gray-600 hover:to-blue-600 transition-all duration-200 text-center block">
                Shop Now
            </a>
        </div>
    </div>
</div> 