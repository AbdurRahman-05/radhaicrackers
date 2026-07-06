<div class="p-6 bg-white rounded-lg shadow-md">
    @if($order)
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h2>
            <p class="text-gray-600">Order placed on {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
                <div class="mb-2">
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
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-500">Payment Status:</span>
                    <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if(($order->payment_status ?? $order->payment->status ?? null) === 'paid') bg-green-100 text-green-800
                        @elseif(($order->payment_status ?? $order->payment->status ?? null) === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->payment_status ?? $order->payment->status ?? 'N/A') }}
                    </span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-500">Total Amount:</span>
                    <span class="ml-2 text-sm font-bold text-gray-900">₹{{ number_format($order->total_amount ?? $order->total, 2) }}</span>
                </div>
                @if($order->notes)
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-500">Notes:</span>
                    <span class="ml-2 text-sm text-gray-900">{{ $order->notes }}</span>
                </div>
                @endif
            </div>
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Information</h3>
                <div class="mb-2"><span class="text-sm font-medium text-gray-500">Name:</span> <span class="ml-2 text-sm text-gray-900">{{ $order->customer_name ?? '-' }}</span></div>
                <div class="mb-2"><span class="text-sm font-medium text-gray-500">Mobile:</span> <span class="ml-2 text-sm text-gray-900">{{ $order->customer_mobile ?? '-' }}</span></div>
                <div class="mb-2"><span class="text-sm font-medium text-gray-500">Email:</span> <span class="ml-2 text-sm text-gray-900">{{ $order->customer_email ?? '-' }}</span></div>
                <div class="mb-2"><span class="text-sm font-medium text-gray-500">State:</span> <span class="ml-2 text-sm text-gray-900">{{ $order->customer_state ?? '-' }}</span></div>
                <div class="mb-2"><span class="text-sm font-medium text-gray-500">District:</span> <span class="ml-2 text-sm text-gray-900">{{ $order->customer_district ?? '-' }}</span></div>
                <div class="mb-2"><span class="text-sm font-medium text-gray-500">City:</span> <span class="ml-2 text-sm text-gray-900">{{ $order->customer_city ?? '-' }}</span></div>
                <div class="mb-2"><span class="text-sm font-medium text-gray-500">Delivery Point:</span> <span class="ml-2 text-sm text-gray-900">{{ $order->delivery_point ?? '-' }}</span></div>
                <div class="mb-2"><span class="text-sm font-medium text-gray-500">Pin Code:</span> <span class="ml-2 text-sm text-gray-900">{{ $order->pin_code ?? '-' }}</span></div>
            </div>
        </div>
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
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($order->logs && $order->logs->count() > 0)
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order History</h3>
            <div class="space-y-3">
                @foreach($order->logs->sortByDesc('created_at') as $log)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $log->notes }}</div>
                        <div class="text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @else
        <div class="text-center py-12">
            <div class="text-gray-500">Order not found</div>
            <a href="{{ route('user.orders') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Back to Orders
            </a>
        </div>
    @endif
</div>
