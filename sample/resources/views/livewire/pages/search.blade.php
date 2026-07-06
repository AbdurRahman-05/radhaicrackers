<div class="relative">
    <!-- Search Input -->
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        
        <input 
            wire:model.debounce.300ms="search"
            type="text" 
            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-colors"
            placeholder="Search for crackers, bombs, rockets..."
        >
        
        @if($search)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button 
                    wire:click="clearSearch"
                    type="button" 
                    class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <!-- Search Results Dropdown -->
    @if($showResults && count($results) > 0)
        <div class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-96 overflow-y-auto">
            <div class="py-2">
                @foreach($results as $result)
                    <div 
                        wire:click="selectProduct({{ $result['id'] }})"
                        class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors"
                    >
                        <div class="flex items-center space-x-3">
                            <!-- Product Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-lg">{{ $result['icon'] }}</span>
                                </div>
                            </div>
                            
                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $result['name'] }}
                                </h4>
                                <p class="text-xs text-gray-500">{{ $result['category'] }}</p>
                                @if($result['description'])
                                    <p class="text-xs text-gray-600 truncate">{{ $result['description'] }}</p>
                                @endif
                            </div>
                            
                            <!-- Price -->
                            <div class="flex-shrink-0 text-right">
                                <div class="flex items-center space-x-1">
                                    @if($result['original_price'])
                                        <span class="text-xs text-gray-400 line-through">
                                            ₹{{ number_format($result['original_price'], 0) }}
                                        </span>
                                    @endif
                                    <span class="text-sm font-bold text-gray-600">
                                        ₹{{ number_format($result['price'], 0) }}
                                    </span>
                                </div>
                                @if($result['discount'])
                                    <div class="text-xs text-red-500 font-semibold">
                                        -{{ $result['discount'] }}%
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <!-- View All Results -->
                <div class="border-t border-gray-200 px-4 py-3">
                    <a 
                        href="{{ route('shop') }}?search={{ urlencode($search) }}" 
                        class="text-sm text-gray-600 hover:text-gray-700 font-medium flex items-center justify-center"
                    >
                        View all results for "{{ $search }}"
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @elseif($showResults && strlen($search) >= 2)
        <div class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
            <div class="px-4 py-8 text-center">
                <div class="text-4xl mb-3">🔍</div>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">No results found</h3>
                <p class="text-xs text-gray-600">Try searching with different keywords</p>
            </div>
        </div>
    @endif
</div> 