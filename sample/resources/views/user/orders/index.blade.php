@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold mb-2">Total Orders </h1>
        </div>
        <div class="mb-4 flex gap-2">
            <a href="{{ route('user.orders.export.csv') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Export CSV
            </a>
            @if(isset($orders) && count($orders) > 0)
                <a href="{{ route('user.orders.invoice_pdf_all') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                    Download All Orders Invoice PDF
                </a>
                <a href="{{ route('user.orders.pdf', $orders->first()->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Download PDF (Latest Order)
                </a>
            @else
                <button class="bg-blue-300 text-white px-4 py-2 rounded-lg cursor-not-allowed" disabled>Download PDF</button>
            @endif
        </div>
    </div>
    <!-- Orders Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receive Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">State</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">District</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Point</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pin Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coupon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verify Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product(s)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">#{{ $order->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name ?? $order->customer_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">₹{{ number_format($order->total_amount ?? $order->total, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->receive_amount ?? '' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
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
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if(($order->payment_status ?? $order->payment->status ?? null) === 'paid') bg-green-100 text-green-800
                            @elseif(($order->payment_status ?? $order->payment->status ?? null) === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->payment_status ?? $order->payment->status ?? 'N/A') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->notes ?: 'No notes' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_mobile }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_state }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_district }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_city }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->delivery_point }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->pin_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->coupon_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->verify_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->items && count($order->items) > 0)
                            {{ collect($order->items)->pluck('product_name')->join(', ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->items && count($order->items) > 0)
                            {{ collect($order->items)->pluck('content')->join(', ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->items && count($order->items) > 0)
                            {{ collect($order->items)->pluck('rate')->map(fn($v) => '₹'.number_format($v,2))->join(', ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->items && count($order->items) > 0)
                            {{ collect($order->items)->pluck('quantity')->join(', ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->items && count($order->items) > 0)
                            {{ collect($order->items)->pluck('total')->map(fn($v) => '₹'.number_format($v,2))->join(', ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('user.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('user.orders.pdf', $order->id) }}" class="ml-2 text-green-600 hover:text-green-900" title="Download PDF">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </a>
                        <a href="{{ route('user.orders.invoice_pdf', $order->id) }}" class="ml-2 text-purple-600 hover:text-purple-900" title="Download Invoice PDF">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="19" class="px-6 py-4 text-center text-gray-500">No orders found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 