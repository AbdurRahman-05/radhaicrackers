<div>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-6">Stock Ordering by Category</h2>

        <!-- Category Quick Navigation -->
        <div class="mb-8 bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-3">Jump to Category:</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($categoryGroups as $category => $data)
                    <a href="#category-{{ Str::slug($category) }}" 
                       class="flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-sm transition">
                        <span class="text-gray-500 mr-2">{{ $data['order'] }}</span>
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </div>

        @foreach($categoryGroups as $category => $data)
            <div class="mb-8" id="category-{{ Str::slug($category) }}">
                <h3 class="text-xl font-semibold mb-4 sticky top-0 bg-gray-100 p-3 rounded flex items-center">
                    <span class="text-gray-500 mr-3">{{ $data['order'] }}</span>
                    {{ $category }}
                </h3>
                <div 
                    wire:sortable="updateOrder('{{ $category }}')"
                    wire:sortable.options="{ animation: 150 }"
                    class="space-y-2"
                >
                    @foreach($data['items'] as $item)
                        <div 
                            wire:key="stock-{{ $item['id'] }}"
                            wire:sortable.item="{{ $item['id'] }}"
                            class="flex items-center gap-4 p-3 bg-white rounded shadow hover:shadow-md transition-shadow"
                        >
                            <div wire:sortable.handle class="cursor-move">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                </svg>
                            </div>
                            <div class="flex items-center gap-4 flex-1">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                         alt="{{ $item['item_name'] }}" 
                                         class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <span class="flex-1">{{ $item['item_name'] }}</span>
                            </div>
                            
                            <!-- Order Number Display/Edit -->
                            <div class="flex items-center gap-2">
                                @if($editingItem === $item['id'])
                                    <div class="flex items-center gap-2">
                                        <input type="number" 
                                               wire:model="editingOrder"
                                               class="w-20 px-2 py-1 border rounded"
                                               min="1">
                                        <button wire:click="saveOrder"
                                                class="text-green-600 hover:text-green-700">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button wire:click="cancelEdit"
                                                class="text-red-600 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">
                                        Order: {{ $item['order_within_category'] }}
                                    </span>
                                    <button wire:click="startEditing({{ $item['id'] }}, {{ $item['order_within_category'] }})"
                                            class="text-blue-600 hover:text-blue-700 ml-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
</div>
