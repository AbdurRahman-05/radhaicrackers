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

        <!-- Order Details Grid: 2x2 -->
        <div class="grid grid-cols-1 md:grid-cols-2 md:grid-rows-2 gap-6 mb-8">
            <!-- Left Top: Customer Information -->
            <div class="md:row-start-1 md:col-start-1">
                <h3 class="text-lg font-semibold mb-3">Customer Information:</h3>
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
            <!-- Right Top: QR Code -->
            <div class="md:row-start-1 md:col-start-2 flex flex-col items-center justify-center">
                <img src="/images/tmp-qr3.jpg" alt="TMB QR Code" class="w-64 h-64 object-contain border rounded mb-2">
                <div class="text-center text-xs text-gray-700 mb-2">
                    <div>Or pay to UPI ID: <b>radhecrakers@tmb</b></div>
                </div>
            </div>
            <!-- Left Bottom: Order Summary -->
            <div class="md:row-start-2 md:col-start-1">
                <h3 class="text-lg font-semibold mb-3">Order Summary:</h3>
                <div class="space-y-2 text-sm">
                    @php
                        // Calculate original order value from items
                        $originalOrderValue = 0;
                        if (isset($order->items) && is_iterable($order->items)) {
                            foreach ($order->items as $item) {
                                // Use original price if available, otherwise use current price
                                $originalPrice = $item['original_price'] ?? $item['rate'] ?? $item['price'] ?? 0;
                                $quantity = $item['quantity'] ?? 0;
                                $originalOrderValue += $originalPrice * $quantity;
                            }
                        }
                        
                        // Calculate discounts
                        $discount70 = round($originalOrderValue * 0.70, 2);
                        $afterDiscount = $originalOrderValue - $discount70;
                        $specialDiscount = round($afterDiscount * 0.15, 2);
                        $afterSpecial = $afterDiscount - $specialDiscount;
                        $packing = round($afterSpecial * 0.05, 2);
                        $netAmount = $afterSpecial + $packing;
                        $couponDiscount = $order->coupon_discount ?? 0;
                        $finalAmount = $netAmount - $couponDiscount;
                    @endphp
                    
                    <div><strong>Order Value:</strong> ₹{{ number_format($originalOrderValue, 2) }}</div>
                    <div><strong>Discount (70%):</strong> -₹{{ number_format($discount70, 2) }}</div>
                    <div><strong>After Discount:</strong> ₹{{ number_format($afterDiscount, 2) }}</div>
                    <div><strong>Special Disc (15%):</strong> -₹{{ number_format($specialDiscount, 2) }}</div>
                    <div><strong>After Spl. Disc:</strong> ₹{{ number_format($afterSpecial, 2) }}</div>
                    <div><strong>Packing (5%):</strong> ₹{{ number_format($packing, 2) }}</div>
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
                    @if($couponDiscount > 0)
                        <div><strong>Coupon Discount:</strong> -₹{{ number_format($couponDiscount, 2) }}</div>
                    @endif
                    <div><strong>Final Amount:</strong> ₹{{ number_format($finalAmount, 2) }}</div>
                    <div><strong>Receive Amount:</strong>
                        @if($order->status === 'confirmed' && ($order->payment_status === 'paid' || ($order->payment->status ?? null) === 'paid'))
                            ₹{{ number_format($order->total_amount ?? $order->total ?? $finalAmount, 2) }}
                        @elseif(isset($order->receive_amount) && is_numeric($order->receive_amount) && $order->receive_amount > 0)
                            ₹{{ number_format($order->receive_amount, 2) }}
                        @else
                            -
                        @endif
                    </div>
                    @if($order->verify_code)
                        <div><strong>Verify Code:</strong> {{ $order->verify_code }}</div>
                    @endif
                    @if($order->notes)
                        <div><strong>Notes:</strong> {{ $order->notes }}</div>
                    @endif
                </div>
            </div>
            <!-- Right Bottom: Bank Details -->
            <div class="md:row-start-2 md:col-start-2 flex flex-col items-center justify-center">
                <div class="bg-gray-50 border rounded p-3 text-xs text-left w-full max-w-xs">
                    <div><b>Account Name:</b> ARUNPANDIAN A</div>
                    <div><b>Account Number:</b> 231100050309953</div>
                    <div><b>Branch:</b> THIRUTHANGAL</div>
                    <div><b>Account Type:</b> Savings Account</div>
                    <div><b>IFSC Code:</b> TMBL0000231</div>
                    <div><b>MICR Code:</b> 626060004</div>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
            </tr>
        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($order->items as $item)
            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                   {!! html_entity_decode($item['product_name'] ?? 'N/A') !!}

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
            <a href="{{ route('user.orders.invoice_pdf', $order->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                Download Invoice
            </a>
        </div>
    </div>
</div>
@endsection 