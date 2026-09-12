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
                    {{ strtolower($order->status ?? '') === 'dispatched' ? 'Dispatched (Out for Delivery)' : ucfirst($order->status) }}
                </span>
            </div>
        </div>

        @if(strtolower($order->status ?? '') === 'dispatched')
            <div class="mb-6 p-4 bg-purple-100 border border-purple-300 text-purple-900 rounded-lg flex items-center gap-3 shadow-sm">
                <span class="text-3xl">🚚</span>
                <div>
                    <div class="font-bold text-base">Order Dispatched - Out for Delivery</div>
                    <div class="text-xs text-purple-800 font-medium">Your order has been dispatched and is currently out for delivery to your location/delivery point.</div>
                </div>
            </div>
        @endif

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
                    <div><strong>Delivery Type:</strong> 
                        <span class="font-bold {{ $order->delivery_type === 'delivery' ? 'text-green-700' : 'text-amber-700' }}">
                            {{ $order->delivery_type === 'delivery' ? '🚚 Delivery (Transport)' : '🏢 Takeaway (Godown Pickup)' }}
                        </span>
                    </div>
                    <div class="mt-3 p-3.5 bg-gradient-to-br from-amber-50 to-orange-50/70 border-2 border-amber-300 rounded-xl text-amber-950 text-xs space-y-1.5 shadow-sm">
                        <div class="font-black uppercase tracking-wider text-amber-900 flex items-center gap-1.5 text-[11px] border-b border-amber-200 pb-1">
                            <span>🚚 Transport & Logistics Details</span>
                        </div>
                        <div>🚚 <strong>Transport Provider:</strong> <span class="font-extrabold text-purple-950">{{ $order->transport_provider ?: 'Assigned Lorry Transport' }}</span></div>
                        <div>🚛 <strong>Vehicle / LR Details:</strong> <span class="font-extrabold text-purple-950">{{ $order->transport_details ?: 'Vehicle En Route / LR Assigned' }}</span></div>
                        <div>📍 <strong>Delivery Point:</strong> <span class="font-bold">{{ $order->delivery_point ?: 'Main Branch' }}</span></div>
                    </div>
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
                        $regularSubtotal = 0;
                        $comboSubtotal = 0;
                        if (isset($order->items) && is_iterable($order->items)) {
                            foreach ($order->items as $item) {
                                if (!empty($item['is_lucky_spin_gift'])) {
                                    continue; // Skip free gifts from calculating subtotal
                                }
                                $pId = (int)($item['product_id'] ?? 0);
                                $pName = (string)($item['product_name'] ?? '');
                                $isCombo = !empty($item['is_combo']) || ($pId >= 999000 && $pId <= 999999) || str_contains(strtoupper($pName), 'COMBO');
                                $quantity = $item['quantity'] ?? 0;
                                if ($isCombo) {
                                    $comboPrice = $item['rate'] ?? $item['price'] ?? $item['original_price'] ?? 0;
                                    $comboSubtotal += $comboPrice * $quantity;
                                } else {
                                    $originalPrice = $item['original_price'] ?? $item['rate'] ?? $item['price'] ?? 0;
                                    $regularSubtotal += $originalPrice * $quantity;
                                }
                            }
                        }
                        
                        $discount70 = round($regularSubtotal * 0.70, 2);
                        $afterDiscount = round($regularSubtotal - $discount70, 2);
                        $specialDiscount = round($afterDiscount * 0.15, 2);
                        $afterSpecial = round($afterDiscount - $specialDiscount, 2);
                        $couponDiscount = (float)($order->coupon_discount ?? 0);
                        $afterCoupon = max(0, round($afterSpecial - $couponDiscount, 2));
                        $totalBeforePacking = round($afterCoupon + $comboSubtotal, 2);
                        $packing = isset($order->packing_charge_5_percent) && (float)$order->packing_charge_5_percent > 0 ? (float)$order->packing_charge_5_percent : round($totalBeforePacking * 0.05, 2);
                        $spinDiscount = (float)($order->lucky_spin_discount ?? 0);
                        $netPayable = max(0, round($totalBeforePacking + $packing - $spinDiscount));
                        if (!empty($order->total_amount) && (float)$order->total_amount > 0) {
                            $netPayable = (float)$order->total_amount;
                        } elseif (!empty($order->total) && (float)$order->total > 0) {
                            $netPayable = (float)$order->total;
                        }

                        $receivedAmount = (isset($order->receive_amount) && is_numeric($order->receive_amount)) ? (float)$order->receive_amount : 0;
                        if ($receivedAmount == 0 && $order->status === 'confirmed' && (($order->payment_status ?? '') === 'paid' || ($order->payment->status ?? '') === 'paid')) {
                            $receivedAmount = $netPayable;
                        }
                        $balanceDue = max(0, $netPayable - $receivedAmount);
                    @endphp
                    
                    <div><strong>SubTotal:</strong> ₹{{ number_format($regularSubtotal, 2) }}</div>
                    <div><strong>Discount (70%):</strong> -₹{{ number_format($discount70, 2) }}</div>
                    <div><strong>After Discount:</strong> ₹{{ number_format($afterDiscount, 2) }}</div>
                    <div><strong>Spl Discount (15%):</strong> -₹{{ number_format($specialDiscount, 2) }}</div>
                    <div><strong>After Spl. Discount:</strong> ₹{{ number_format($afterSpecial, 2) }}</div>
                    @if($couponDiscount > 0 || !empty($order->coupon_code))
                        <div><strong>Coupon Discount @if(!empty($order->coupon_code))({{ $order->coupon_code }})@endif:</strong> -₹{{ number_format($couponDiscount, 2) }}</div>
                        <div><strong>After Coupon Discount:</strong> ₹{{ number_format($afterCoupon, 2) }}</div>
                    @endif
                    @if($comboSubtotal > 0)
                        <div><strong>Net rate Items / Combo:</strong> ₹{{ number_format($comboSubtotal, 2) }}</div>
                    @endif
                    @if($order->lucky_spin_prize)
                        <div class="mt-1 p-2 bg-amber-50 border border-amber-300 rounded-lg flex items-center justify-between">
                            <span class="font-bold text-amber-900">🎡 Lucky Spin Prize:</span>
                            <span class="font-extrabold text-amber-950">{{ $order->lucky_spin_prize }}</span>
                        </div>
                    @endif
                    @if($spinDiscount > 0)
                        <div class="text-emerald-700 font-bold"><strong>🎡 Lucky Spin Disc (5%):</strong> -₹{{ number_format($spinDiscount, 2) }}</div>
                    @endif
                    <div><strong>T. Amt:</strong> ₹{{ number_format($totalBeforePacking, 2) }}</div>
                    <div><strong>Add packing 5%:</strong> ₹{{ number_format($packing, 2) }}</div>
                    <div class="text-base font-bold text-gray-900 border-t pt-1"><strong>Net Amt / Payable Amt:</strong> ₹{{ number_format($netPayable, 2) }}</div>
                    <div><strong>Received Amt:</strong> ₹{{ number_format($receivedAmount, 2) }}</div>
                    @if($balanceDue > 0)
                        <div class="text-red-600 font-bold"><strong>Balance Due:</strong> ₹{{ number_format($balanceDue, 2) }}</div>
                    @endif
                    <div><strong>Payment Status:</strong> 
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->payment_status ?? 'N/A') }}
                        </span>
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
                            @php
                                $isGift = !empty($item['is_lucky_spin_gift']);
                                $itemPrice = $isGift ? 0 : (float)($item['rate'] ?? $item['price'] ?? 0);
                                $itemQty = (int)($item['quantity'] ?? 0);
                            @endphp
                            <tr class="{{ $isGift ? 'bg-amber-50/50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {!! html_entity_decode($item['product_name'] ?? 'N/A') !!}
                                    @if($isGift)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-200 text-amber-900 border border-amber-300">
                                            🎁 Free Gift (Lucky Spin)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($isGift)
                                        <span class="text-amber-800 font-bold">FREE (₹0.00)</span>
                                    @else
                                        ₹{{ number_format($itemPrice, 2) }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $itemQty }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($isGift)
                                        <span class="text-amber-800 font-bold">₹0.00</span>
                                    @else
                                        ₹{{ number_format($itemPrice * $itemQty, 2) }}
                                    @endif
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
            <a href="{{ route('user.orders') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors font-medium text-sm">
                ← Back to Orders
            </a>
            <a href="{{ route('user.orders.invoice_pdf', $order->id) }}" target="_blank" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors font-medium text-sm">
                📄 Download Invoice PDF
            </a>
        </div>
    </div>
</div>
@endsection 