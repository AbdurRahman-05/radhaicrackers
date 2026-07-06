@extends('layouts.app')
@section('title', 'Order Details')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('message'))
        <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('d/m/Y \a\t H:i') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
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

        <!-- Order Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold mb-3">Customer Information</h3>
                <div class="space-y-2 text-sm">
                    <div><strong>Name:</strong> {{ $order->customer_name ?: 'N/A' }}</div>
                    <div><strong>Mobile:</strong> {{ $order->customer_mobile ?: 'N/A' }}</div>
                    <div><strong>Email:</strong> {{ $order->customer_email ?: 'N/A' }}</div>
                    <div><strong>State:</strong> {{ $order->customer_state ?: 'N/A' }}</div>
                    <div><strong>District:</strong> {{ $order->customer_district ?: 'N/A' }}</div>
                    <div><strong>City:</strong> {{ $order->customer_city ?: 'N/A' }}</div>
                    <div><strong>Delivery Point:</strong> {{ $order->delivery_point ?: 'N/A' }}</div>
                    <div><strong>Pin Code:</strong> {{ $order->pin_code ?: 'N/A' }}</div>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-3">Order Summary</h3>
                <div class="space-y-2 text-sm">
                    <div><strong>Total Amount:</strong> ₹{{ number_format($order->total_amount ?? $order->total, 2) }}</div>
                    <div><strong>Payment Status:</strong> 
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->payment_status ?? 'N/A') }}
                        </span>
                    </div>
                    @if($order->coupon_code)
                        <div><strong>Coupon Code:</strong> {{ $order->coupon_code }}</div>
                    @endif
                    @if($order->coupon_discount)
                        <div><strong>Coupon Discount:</strong> -₹{{ number_format($order->coupon_discount, 2) }}</div>
                    @endif
                    <div><strong>Final Amount After Coupon:</strong> ₹{{ number_format(($order->total_amount ?? $order->total) - ($order->coupon_discount ?? 0), 2) }}</div>
                    @if($order->verify_code)
                        <div><strong>Verify Code:</strong> {{ $order->verify_code }}</div>
                    @endif
                    @if($order->notes)
                        <div><strong>Notes:</strong> {{ $order->notes }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3">Order Items</h3>
            @if($order->items && count($order->items) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
        <thead class="bg-gray-50">
            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
            </tr>
        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($order->items as $item)
            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item['product_name'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item['content'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ₹{{ number_format($item['rate'] ?? $item['price'] ?? 0, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item['quantity'] ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ₹{{ number_format($item['total'] ?? $item['subtotal'] ?? 0, 2) }}
                                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No items found for this order.</p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('user.orders') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                Back to Orders
            </a>
            <a href="{{ route('user.orders.pdf', $order->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                Download PDF
            </a>
            <a href="{{ route('user.orders.invoice_pdf', $order->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                Download Invoice
            </a>
        </div>
    </div>
</div>
@endsection 