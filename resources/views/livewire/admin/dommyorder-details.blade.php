<div class="p-6 bg-white rounded-lg shadow-md">
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif
    
    @if (session()->has('info'))
        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
            <i class="fas fa-info-circle mr-2"></i>
            {{ session('info') }}
        </div>
    @endif
    
    @if($order)
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h2>
            <p class="text-gray-600">Order Details & Management</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 mt-4 sm:mt-0">
            <button wire:click="generatePDF" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </button>
            <a href="{{ route('admin.orders') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Back to Orders</a>
        </div>
    </div>

    <!-- Order Status Section -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Order Status</h3>
        </div>

        <!-- Display Current Values -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                <div class="px-3 py-2 bg-white border border-gray-300 rounded-md">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                        @elseif($order->status === 'dispatched') bg-purple-100 text-purple-800
                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                <div class="px-3 py-2 bg-white border border-gray-300 rounded-md">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($order->payment_status === 'paid') bg-green-100 text-green-800
                        @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <div class="px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900">
                    {{ $order->notes ?: 'No notes added' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Order Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Customer Information -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-500">Name:</span>
                    <span class="ml-2 text-sm text-gray-900">{{ $order->user->name }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Phone:</span>
                    <span class="ml-2 text-sm text-gray-900">{{ $order->user->phone }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Email:</span>
                    <span class="ml-2 text-sm text-gray-900">{{ $order->user->email ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Order Date:</span>
                    <span class="ml-2 text-sm text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-500">Order ID:</span>
                    <span class="ml-2 text-sm text-gray-900">#{{ $order->id }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Status:</span>
                    <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                        @elseif($order->status === 'dispatched') bg-purple-100 text-purple-800
                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Payment Status:</span>
                    <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($order->payment_status === 'paid') bg-green-100 text-green-800
                        @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Total Amount:</span>
                    <span class="ml-2 text-sm font-bold text-gray-900">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
                @if($order->notes)
                <div>
                    <span class="text-sm font-medium text-gray-500">Notes:</span>
                    <span class="ml-2 text-sm text-gray-900">{{ $order->notes }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(is_array($order->items))
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item['product_name'] ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['quantity'] ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($item['price'] ?? $item['rate'] ?? 0, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹{{ number_format(($item['price'] ?? $item['rate'] ?? 0) * ($item['quantity'] ?? 0), 2) }}</td>
                        </tr>
                        @endforeach
                    @else
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->product_name ?? '-' }}</div>
                        </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($item->price ?? $item->rate ?? 0, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹{{ number_format(($item->price ?? $item->rate ?? 0) * ($item->quantity ?? 0), 2) }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- WhatsApp Actions -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">WhatsApp Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button wire:click="sendWhatsAppSummary" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                </svg>
                Send Summary
            </button>
            <button wire:click="sendDispatchNotification" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Dispatch Notification
            </button>
            <button wire:click="sendPaymentReminder" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Payment Reminder
            </button>
        </div>
    </div>

    <!-- Order Logs -->
    @if($order->logs->count() > 0)
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order History</h3>
        <div class="space-y-3">
            @foreach($order->logs->sortByDesc('created_at') as $log)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $log->notes }}</div>
                    <div class="text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="text-xs text-gray-500">
                    @if($log->changed_by)
                    Admin ID: {{ $log->changed_by }}
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($showWhatsAppLink)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="whatsappModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-4">WhatsApp Link Generated</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 mb-4">Click the link below to open WhatsApp:</p>
                    <a href="{{ $whatsappLink }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                        Open WhatsApp
                    </a>
                </div>
                <div class="items-center px-4 py-3">
                    <button wire:click="$set('showWhatsAppLink', false)" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @else
    <div class="text-center py-12">
        <div class="text-gray-500">Order not found</div>
        <a href="{{ route('admin.orders') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Back to Orders
        </a>
    </div>
    @endif
</div>

<script>
document.addEventListener('livewire:load', function () {
    // Simple Livewire initialization
    console.log('Livewire OrderDetails component loaded');
});
</script>

 