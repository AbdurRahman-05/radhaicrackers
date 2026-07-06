@extends('layouts.app')

@section('title', 'Order Details - Cracker Shop')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('message'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('message') }}
            </div>
        @endif
        <div class="mb-8">
            <a href="{{ route('user.orders') }}" class="text-orange-600 hover:text-orange-800 mb-4 inline-block">
                ← Back to Orders
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
            <p class="text-gray-600">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Order ID</p>
                            <p class="font-semibold text-gray-900">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Order Date</p>
                            <p class="font-semibold text-gray-900">{{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'dispatched' => 'bg-purple-100 text-purple-800',
                                    'completed' => 'bg-green-100 text-green-800'
                                ];
                                $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-sm font-medium {{ $color }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Amount</p>
                            <p class="font-semibold text-gray-900">₹{{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm text-gray-600">Order Notes</p>
                            <p class="text-gray-900">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items</h2>
                    @php
                        $items = is_array($order->items_json) && count($order->items_json) ? $order->items_json : $order->items;
                        $subtotal = 0;
                    @endphp
                    <div class="space-y-4">
                        @foreach($items as $item)
                            @php
                                $itemName = is_array($item) ? ($item['product_name'] ?? '-') : $item->product_name;
                                $itemQty = is_array($item) ? ($item['quantity'] ?? 0) : $item->quantity;
                                $itemPrice = is_array($item) ? ($item['rate'] ?? ($item['price'] ?? 0)) : $item->price;
                                $itemTotal = is_array($item) ? ($item['total'] ?? ($item['subtotal'] ?? 0)) : $item->subtotal;
                                $subtotal += $itemTotal;
                            @endphp
                            <div class="flex justify-between items-center border-b border-gray-200 pb-4 last:border-b-0">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $itemName }}</h3>
                                    <p class="text-sm text-gray-600">Quantity: {{ $itemQty }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">₹{{ number_format($itemTotal, 2) }}</p>
                                    <p class="text-sm text-gray-600">₹{{ number_format($itemPrice, 2) }} each</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Subtotal</span>
                            <span class="text-lg font-semibold text-gray-900">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        @php
                            $discount_70 = round($subtotal * 0.7, 2);
                            $after_70 = $subtotal - $discount_70;
                            $discount_15 = round($after_70 * 0.15, 2);
                            $after_15 = $after_70 - $discount_15;
                            $packing = round($after_15 * 0.05, 2);
                            $net_amount = round($after_15 + $packing, 2);
                        @endphp
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-gray-700">Discount (70%)</span>
                            <span class="text-gray-700">-₹{{ number_format($discount_70, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-gray-700">After Discount</span>
                            <span class="text-gray-700">₹{{ number_format($after_70, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-gray-700">Special Discount (15%)</span>
                            <span class="text-gray-700">-₹{{ number_format($discount_15, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-gray-700">After Spl. Discount</span>
                            <span class="text-gray-700">₹{{ number_format($after_15, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-gray-700">Packing (5%)</span>
                            <span class="text-gray-700">₹{{ number_format($packing, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-lg font-semibold text-gray-900">Net Amount</span>
                            <span class="text-lg font-semibold text-gray-900">₹{{ number_format($net_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Payment Information -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                    
                    @if($order->payment)
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Payment Status</p>
                                @php
                                    $paymentColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'verified' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800'
                                    ];
                                    $paymentColor = $paymentColors[$order->payment->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-sm font-medium {{ $paymentColor }}">
                                    {{ ucfirst($order->payment->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">UPI ID</p>
                                <p class="font-medium text-gray-900">{{ $order->payment->upi_id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Transaction ID</p>
                                <p class="font-medium text-gray-900">{{ $order->payment->transaction_id }}</p>
                            </div>
                            @if($order->payment->verified_at)
                                <div>
                                    <p class="text-sm text-gray-600">Verified At</p>
                                    <p class="font-medium text-gray-900">{{ $order->payment->verified_at->format('M d, Y h:i A') }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-600 mb-4">No payment information available</p>
                            <p class="text-sm text-gray-500">Please complete your UPI payment and provide transaction details</p>
                        </div>
                    @endif
                </div>

                <!-- Order Timeline -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Timeline</h3>
                    
                    <div class="space-y-4">
                        @foreach($order->logs as $log)
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ ucfirst($log->status) }}</p>
                                    <p class="text-sm text-gray-600">{{ $log->created_at->format('M d, Y h:i A') }}</p>
                                    @if($log->notes)
                                        <p class="text-sm text-gray-500">{{ $log->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('order.pdf', $order->id) }}" 
                           class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors text-center block">
                            Download PDF
                        </a>
                        <a href="https://wa.me/919876543210" target="_blank" 
                           class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors text-center block">
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 