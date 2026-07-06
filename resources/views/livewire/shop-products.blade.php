<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                <input type="checkbox" value="{{ $product->id }}" wire:model="selectedProducts">
                {{ $product->item_name }}
            </div>
        @endforeach
    </div>
    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
