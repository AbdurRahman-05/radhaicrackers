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
                                {{ strtolower($order->status ?? '') === 'dispatched' ? 'Dispatched (Out for Delivery)' : ucfirst($order->status) }}
                            </span>
                            @if(strtolower($order->status ?? '') === 'dispatched')
                                <div class="mt-2 text-xs font-semibold text-purple-800 flex items-center gap-1">
                                    <span>🚚</span> Dispatched - Out for Delivery
                                </div>
                            @endif
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
                        $regularSubtotal = 0;
                        $comboSubtotal = 0;
                        if (is_iterable($items)) {
                            foreach ($items as $item) {
                                $pId = is_array($item) ? (int)($item['product_id'] ?? 0) : (int)($item->product_id ?? 0);
                                $pName = is_array($item) ? (string)($item['product_name'] ?? '') : (string)($item->product_name ?? '');
                                $isCombo = is_array($item) ? (!empty($item['is_combo']) || ($pId >= 999000 && $pId <= 999999) || str_contains(strtoupper($pName), 'COMBO')) : (!empty($item->is_combo) || ($pId >= 999000 && $pId <= 999999) || str_contains(strtoupper($pName), 'COMBO'));
                                $quantity = is_array($item) ? ($item['quantity'] ?? 0) : ($item->quantity ?? 0);
                                if ($isCombo) {
                                    $cRate = is_array($item) ? ($item['rate'] ?? ($item['price'] ?? 0)) : ($item->rate ?? ($item->price ?? 0));
                                    $comboSubtotal += $cRate * $quantity;
                                } else {
                                    $rRate = is_array($item) ? ($item['original_price'] ?? ($item['rate'] ?? ($item['price'] ?? 0))) : ($item->original_price ?? ($item->rate ?? ($item->price ?? 0)));
                                    $regularSubtotal += $rRate * $quantity;
                                }
                            }
                        }
                    @endphp
                    <div class="space-y-4">
                        @foreach($items as $item)
                            @php
                                $itemName = is_array($item) ? ($item['product_name'] ?? '-') : $item->product_name;
                                $itemQty = is_array($item) ? ($item['quantity'] ?? 0) : $item->quantity;
                                $itemPrice = is_array($item) ? ($item['rate'] ?? ($item['price'] ?? 0)) : $item->price;
                                $itemTotal = is_array($item) ? ($item['total'] ?? ($item['subtotal'] ?? 0)) : $item->subtotal;
                            @endphp
                            <div class="flex justify-between items-center border-b border-gray-200 pb-4 last:border-b-0">
                                <div>
                                    <h3 class="font-medium text-gray-900">
                                        {!! html_entity_decode($itemName) !!}
                                    </h3>
                                    <p class="text-sm text-gray-600">Quantity: {{ $itemQty }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">₹{{ number_format($itemTotal, 2) }}</p>
                                    <p class="text-sm text-gray-600">₹{{ number_format($itemPrice, 2) }} each</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-200 pt-4 mt-4 space-y-2 text-sm">
                        @php
                            $discount70 = isset($order->discount_70_percent) && (float)$order->discount_70_percent > 0 ? (float)$order->discount_70_percent : round($regularSubtotal * 0.70, 2);
                            $afterDiscount70 = isset($order->amount_after_70_discount) && (float)$order->amount_after_70_discount > 0 ? (float)$order->amount_after_70_discount : round($regularSubtotal - $discount70, 2);
                            $specialDiscount15 = isset($order->special_discount_15_percent) && (float)$order->special_discount_15_percent > 0 ? (float)$order->special_discount_15_percent : round($afterDiscount70 * 0.15, 2);
                            $afterSpecial15 = isset($order->amount_after_15_discount) && (float)$order->amount_after_15_discount > 0 ? (float)$order->amount_after_15_discount : round($afterDiscount70 - $specialDiscount15, 2);
                            $couponDiscount = (float)($order->coupon_discount ?? 0);
                            $afterCoupon = isset($order->amount_after_coupon) && (float)$order->amount_after_coupon > 0 ? (float)$order->amount_after_coupon : max(0, round($afterSpecial15 - $couponDiscount, 2));
                            $totalBeforePacking = isset($order->total_before_packing) && (float)$order->total_before_packing > 0 ? (float)$order->total_before_packing : round($afterCoupon + $comboSubtotal, 2);
                            $packing = isset($order->packing_charge_5_percent) && (float)$order->packing_charge_5_percent > 0 ? (float)$order->packing_charge_5_percent : round($totalBeforePacking * 0.05, 2);
                            $spinDiscount = (float)($order->lucky_spin_discount ?? 0);
                            $netPayable = isset($order->total_amount) && (float)$order->total_amount > 0 ? (float)$order->total_amount : (isset($order->total) && (float)$order->total > 0 ? (float)$order->total : max(0, round($totalBeforePacking + $packing - $spinDiscount)));

                            $receivedAmount = (isset($order->receive_amount) && is_numeric($order->receive_amount)) ? (float)$order->receive_amount : 0;
                            if ($receivedAmount == 0 && $order->status === 'confirmed' && (($order->payment_status ?? '') === 'paid' || ($order->payment->status ?? '') === 'paid')) {
                                $receivedAmount = $netPayable;
                            }
                            $balanceDue = max(0, $netPayable - $receivedAmount);
                        @endphp

                        <div class="flex justify-between items-center text-gray-700">
                            <span>SubTotal</span>
                            <span>₹{{ number_format($regularSubtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-700">
                            <span>Discount (70%)</span>
                            <span>-₹{{ number_format($discount70, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-700">
                            <span>After Discount</span>
                            <span>₹{{ number_format($afterDiscount70, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-700">
                            <span>Spl Discount (15%)</span>
                            <span>-₹{{ number_format($specialDiscount15, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-700">
                            <span>After Spl. Discount</span>
                            <span>₹{{ number_format($afterSpecial15, 2) }}</span>
                        </div>
                        @if($couponDiscount > 0 || !empty($order->coupon_code))
                            <div class="flex justify-between items-center text-gray-700">
                                <span>Coupon Discount @if(!empty($order->coupon_code))({{ $order->coupon_code }})@endif</span>
                                <span>-₹{{ number_format($couponDiscount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-700">
                                <span>After Coupon Discount</span>
                                <span>₹{{ number_format($afterCoupon, 2) }}</span>
                            </div>
                        @endif
                        @if($comboSubtotal > 0)
                            <div class="flex justify-between items-center text-gray-700">
                                <span>Net rate Items / Combo</span>
                                <span>₹{{ number_format($comboSubtotal, 2) }}</span>
                            </div>
                        @endif
                        @if($order->lucky_spin_prize)
                            <div class="flex justify-between items-center text-amber-800 font-medium">
                                <span>🎡 Lucky Spin Prize</span>
                                <span>{{ $order->lucky_spin_prize }}</span>
                            </div>
                        @endif
                        @if($spinDiscount > 0)
                            <div class="flex justify-between items-center text-emerald-700 font-medium">
                                <span>🎡 Lucky Spin Disc (5%)</span>
                                <span>-₹{{ number_format($spinDiscount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center font-semibold text-gray-800">
                            <span>T. Amt</span>
                            <span>₹{{ number_format($totalBeforePacking, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-700">
                            <span>Add packing 5%</span>
                            <span>₹{{ number_format($packing, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-bold text-gray-900 border-t pt-2 mt-1">
                            <span>Net Amt / Payable Amt</span>
                            <span>₹{{ number_format($netPayable, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm font-semibold text-gray-800">
                            <span>Received Amt</span>
                            <span>₹{{ number_format($receivedAmount, 2) }}</span>
                        </div>
                        @if($balanceDue > 0)
                            <div class="flex justify-between items-center text-sm font-bold text-red-600">
                                <span>Balance Due</span>
                                <span>₹{{ number_format($balanceDue, 2) }}</span>
                            </div>
                        @endif
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