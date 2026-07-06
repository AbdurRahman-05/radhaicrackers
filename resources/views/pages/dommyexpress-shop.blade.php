@extends('layouts.app')

@section('title', 'Express Shop - Radhe Crackers')

@section('content')
<div class="bg-white py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-2" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li><a href="/" class="hover:underline">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-700">Express Shop</li>
            </ol>
        </nav>
        
        <!-- Page Title -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 text-center">Express Shop</h1>
        
        @if(empty($stockData))
            <div class="text-center py-12">
                <div class="text-4xl mb-4">🎆</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Products Available</h3>
                <p class="text-gray-600">Check back later for amazing fireworks!</p>
            </div>
        @else
            @foreach($stockData as $category => $stocks)
                <!-- {{ $category }} Category -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $category }}</h2>
                    
                    <!-- Product Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($stocks as $stock)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($stock->image)
                                                <img src="{{ asset('storage/' . $stock->image) }}" 
                                                     alt="{{ $stock->item_name }}" 
                                                     class="w-16 h-16 object-cover rounded-lg">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <span class="text-2xl">
                                                        @switch($stock->category)
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
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $stock->item_name }}</div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $stock->description ?: 'No description available' }}</div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                @if($stock->original_price && $stock->original_price > $stock->price)
                                                    <span class="line-through text-gray-400 mr-2">₹{{ number_format($stock->original_price, 2) }}</span>
                                                    <span class="font-semibold text-red-600">₹{{ number_format($stock->price, 2) }}</span>
                                                    @if($stock->discount_percentage)
                                                        <div class="text-xs text-red-500">-{{ $stock->discount_percentage }}% OFF</div>
                                                    @endif
                                                @else
                                                    <span class="font-semibold">₹{{ number_format($stock->price, 2) }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <button type="button" 
                                                        onclick="updateQuantity({{ $stock->id }}, -1)" 
                                                        class="w-8 h-8 text-white rounded-full flex items-center justify-center hover:bg-gray-300"  style="background-color:rgb(182, 113, 33);">
                                                    -
                                                </button>
                                                <span id="quantity-{{ $stock->id }}" class="w-8 text-center">0</span>
                                                <button type="button" 
                                                        onclick="updateQuantity({{ $stock->id }}, 1)" 
                                                        class="w-8 h-8 text-white rounded-full flex items-center justify-center hover:bg-gray-300"  style="background-color:rgb(182, 113, 33);">
                                                    +
                                                </button>
                                                <button type="button" 
                                                        onclick="removeItem({{ $stock->id }})" 
                                                        class="ml-2 text-red-500 hover:text-red-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">Available: {{ $stock->quantity }}</div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900" id="total-{{ $stock->id }}">₹0.00</div>
                                        </td>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
            
            <!-- Order Summary -->
            <div class="mt-8 border-t border-gray-200 pt-6">
                <div class="flex justify-between items-center">
                    <div class="text-lg font-semibold text-gray-900">Total: ₹<span id="cart-total">0.00</span></div>
                    <button onclick="proceedToCheckout()" class="text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-colors font-semibold" style="background-color: #1E093B;">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Store product data
const products = {
    @foreach($stockData as $category => $stocks)
        @foreach($stocks as $stock)
            {{ $stock->id }}: {
                id: {{ $stock->id }},
                name: "{{ $stock->item_name }}",
                price: {{ $stock->price }},
                available: {{ $stock->quantity }},
                quantity: 0
            },
        @endforeach
    @endforeach
};

// Update quantity for a product
function updateQuantity(productId, change) {
    const product = products[productId];
    const newQuantity = Math.max(0, Math.min(product.available, product.quantity + change));
    
    if (newQuantity !== product.quantity) {
        product.quantity = newQuantity;
        
        // Update display
        document.getElementById(`quantity-${productId}`).textContent = newQuantity;
        document.getElementById(`total-${productId}`).textContent = `₹${(newQuantity * product.price).toFixed(2)}`;
        
        // Update cart total
        updateCartTotal();
    }
}

// Remove item from cart
function removeItem(productId) {
    updateQuantity(productId, -products[productId].quantity);
}

// Update cart total
function updateCartTotal() {
    const total = Object.values(products).reduce((sum, product) => {
        return sum + (product.quantity * product.price);
    }, 0);
    
    document.getElementById('cart-total').textContent = total.toFixed(2);
}

// Proceed to checkout
function proceedToCheckout() {
    const selectedProducts = Object.values(products).filter(product => product.quantity > 0);
    
    if (selectedProducts.length === 0) {
        alert('Please select at least one product to proceed.');
        return;
    }
    
    // Create URL with selected items
    const items = selectedProducts.map(product => `${product.id}:${product.quantity}`).join(',');
    window.location.href = `{{ route('checkout.form') }}?items=${items}`;
}

// Initialize cart total
updateCartTotal();
</script>
@endsection 