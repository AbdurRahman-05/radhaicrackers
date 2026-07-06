<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i class="fas fa-ticket-alt text-orange-500 mr-2"></i>
        Apply Coupon Code
    </h3>
    @if($success)
        <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded mb-3">
            <strong>Coupon Applied: {{ $coupon_code }}</strong><br>
            Discount: ₹{{ number_format($discount, 2) }}<br>
            New Total: ₹{{ number_format($new_total, 2) }}
        </div>
    @endif
    @if($error)
        <div class="bg-red-100 border border-red-300 text-red-800 p-3 rounded mb-3">
            {{ $error }}
        </div>
    @endif
    <div class="flex space-x-2">
        <input type="text" wire:model="code" class="flex-1 border rounded px-3 py-2" placeholder="Enter coupon code">
        <button type="button" wire:click="applyCoupon" class="bg-purple-700 text-white px-4 py-2 rounded">Apply</button>
        @if($success)
            <button type="button" wire:click="removeCoupon" class="bg-red-500 text-white px-3 py-2 rounded">Remove</button>
        @endif
    </div>
    <div class="mt-2 text-xs text-gray-500">Only one coupon can be used per order.</div>
</div> 