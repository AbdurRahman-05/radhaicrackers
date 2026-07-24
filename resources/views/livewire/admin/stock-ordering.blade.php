<div>
    <!-- Include SortableJS CDN for smooth cursor drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <div class="p-6 bg-white rounded-lg shadow-sm">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Stock Ordering by Category</h2>
                <p class="text-xs text-gray-500 mt-1">Click and drag items with your mouse cursor to re-order products within each category</p>
            </div>
            <span class="text-xs font-semibold text-purple-700 bg-purple-50 px-3 py-1.5 rounded-full border border-purple-200">
                <i class="fas fa-hand-pointer mr-1"></i> Cursor Drag & Drop Enabled
            </span>
        </div>

        <!-- Category Quick Navigation -->
        <div class="mb-8 bg-gray-50 p-4 rounded-xl border border-gray-200">
            <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Jump to Category:</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($categoryGroups as $category => $data)
                    <a href="#category-{{ Str::slug($category) }}" 
                       class="flex items-center px-3 py-1 bg-white border border-gray-300 hover:border-purple-500 hover:text-purple-700 rounded-full text-xs font-medium transition shadow-sm">
                        <span class="text-purple-600 font-bold mr-1.5">#{{ $data['order'] }}</span>
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </div>

        @foreach($categoryGroups as $category => $data)
            <div class="mb-8" id="category-{{ Str::slug($category) }}">
                <h3 class="text-base font-bold mb-3 bg-[#1E093B] text-white px-4 py-2.5 rounded-lg flex items-center justify-between shadow-sm">
                    <div class="flex items-center">
                        <span class="bg-purple-800 text-white text-xs px-2 py-0.5 rounded font-bold mr-2">Cat #{{ $data['order'] }}</span>
                        <span>{{ $category }}</span>
                    </div>
                    <span class="text-xs font-normal opacity-80">{{ count($data['items']) }} Products</span>
                </h3>

                <div 
                    x-data="{
                        initSortable() {
                            if (typeof Sortable !== 'undefined') {
                                new Sortable($el, {
                                    animation: 150,
                                    handle: '.drag-handle',
                                    ghostClass: 'bg-purple-100',
                                    opacity: 0.6,
                                    onEnd: () => {
                                        let itemIds = Array.from($el.children).map(el => el.getAttribute('data-id'));
                                        $wire.updateOrder(@js($category), itemIds);
                                    }
                                });
                            }
                        }
                    }"
                    x-init="initSortable()"
                    class="space-y-2"
                >
                    @foreach($data['items'] as $item)
                        <div 
                            data-id="{{ $item['id'] }}"
                            wire:key="stock-item-{{ $item['id'] }}"
                            class="flex items-center gap-4 p-3 bg-white border border-gray-200 rounded-lg hover:border-purple-300 hover:shadow-md transition group"
                        >
                            <!-- Cursor Drag Handle -->
                            <div class="drag-handle cursor-grab active:cursor-grabbing p-1.5 text-gray-400 hover:text-purple-700 transition" title="Click and drag with mouse cursor">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                </svg>
                            </div>

                            <!-- Product Name -->
                            <div class="flex items-center gap-4 flex-1">
                                <span class="flex-1 font-semibold text-gray-900 text-sm select-none">{{ $item['item_name'] }}</span>
                            </div>
                            
                            <!-- Order Number Display/Edit -->
                            <div class="flex items-center gap-2">
                                @if($editingItem === $item['id'])
                                    <div class="flex items-center gap-2">
                                        <input type="number" 
                                               wire:model="editingOrder"
                                               class="w-20 px-2 py-1 border border-purple-400 rounded text-xs text-center font-bold focus:outline-none"
                                               min="1">
                                        <button wire:click="saveOrder" class="text-green-600 hover:text-green-800 p-1" title="Save">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button wire:click="cancelEdit" class="text-red-600 hover:text-red-800 p-1" title="Cancel">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs font-bold text-gray-600 bg-gray-100 px-2.5 py-1 rounded">
                                        Order: {{ $item['order_within_category'] }}
                                    </span>
                                    <button wire:click="startEditing({{ $item['id'] }}, {{ $item['order_within_category'] }})"
                                            class="text-blue-600 hover:text-blue-800 p-1" title="Type custom order position">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
