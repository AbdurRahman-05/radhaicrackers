<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">My Orders</h2>
        @if($orders->count() > 0)
        <button wire:click="downloadPDF" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Download Complete PDF Report
        </button>
        @endif
    </div>
    
    @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coupon</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name ?: 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_mobile ?: 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">₹{{ number_format($order->total_amount ?? $order->total, 2) }}</div>
                            @if($order->discount_amount > 0)
                            <div class="text-xs text-green-600">-₹{{ number_format($order->discount_amount, 2) }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status === 'dispatched') bg-purple-100 text-purple-800
                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if(($order->payment_status ?? $order->payment->status ?? null) === 'paid') bg-green-100 text-green-800
                                @elseif(($order->payment_status ?? $order->payment->status ?? null) === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->payment_status ?? $order->payment->status ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $order->coupon_code ?: 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ Str::limit($order->notes ?: 'No notes', 30) }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('order.show', $order->id) }}" class="text-blue-600 hover:text-blue-900 mr-2" title="View Details">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('order.pdf', $order->id) }}" class="text-green-600 hover:text-green-900" title="Download PDF">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Summary Statistics -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="text-sm font-medium text-blue-600">Total Orders</div>
                <div class="text-2xl font-bold text-blue-900">{{ $orders->count() }}</div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <div class="text-sm font-medium text-green-600">Total Amount</div>
                <div class="text-2xl font-bold text-green-900">₹{{ number_format($orders->sum('total_amount') ?? $orders->sum('total'), 2) }}</div>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="text-sm font-medium text-yellow-600">Pending Orders</div>
                <div class="text-2xl font-bold text-yellow-900">{{ $orders->where('status', 'pending')->count() }}</div>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <div class="text-sm font-medium text-purple-600">Completed Orders</div>
                <div class="text-2xl font-bold text-purple-900">{{ $orders->where('status', 'completed')->count() }}</div>
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <div class="text-4xl mb-4">📦</div>
            <p class="text-gray-600">No orders yet</p>
            <p class="text-sm text-gray-500 mb-4">Start shopping to see your orders here</p>
            <a href="{{ route('order.form') }}" 
               class="inline-flex items-center bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                <span class="mr-2">🛒</span>
                Place Your First Order
            </a>
        </div>
    @endif
</div>
