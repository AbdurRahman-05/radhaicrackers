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
                        {{ strtolower($order->status ?? '') === 'dispatched' ? 'Dispatched (Out for Delivery)' : ucfirst($order->status) }}
                    </span>
                    @if(strtolower($order->status ?? '') === 'dispatched')
                        <div class="mt-2 p-2 bg-purple-100 border border-purple-300 text-purple-900 rounded-lg flex items-center gap-2 text-xs font-semibold">
                            <span>🚚</span> Order Dispatched - Out for Delivery
                        </div>
                    @endif
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
                        @foreach($order->items_json ?? [] as $item)
                        @php
                            $itemName = is_array($item) ? ($item['product_name'] ?? '-') : ($item->product_name ?? '-');
                            $itemQty = is_array($item) ? ($item['quantity'] ?? 0) : ($item->quantity ?? 0);
                            $itemPrice = (float)(is_array($item) ? ($item['price'] ?? $item['rate'] ?? 0) : ($item->price ?? 0));
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $itemName }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $itemQty }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($itemPrice, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹{{ number_format($itemPrice * $itemQty, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Order Summary -->
            <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end">
                <div class="w-full md:w-80 space-y-2 text-sm">
                    @php
                        $regularSubtotal = 0;
                        $comboSubtotal = 0;
                        if (isset($order->items_json) && is_iterable($order->items_json)) {
                            foreach ($order->items_json as $item) {
                                $itemArr = is_object($item) ? (array)$item : $item;
                                $pId = (int)($itemArr['product_id'] ?? 0);
                                $pName = (string)($itemArr['product_name'] ?? '');
                                $isCombo = !empty($itemArr['is_combo']) || ($pId >= 999000 && $pId <= 999999) || str_contains(strtoupper($pName), 'COMBO');
                                $quantity = (int)($itemArr['quantity'] ?? 0);
                                if ($isCombo) {
                                    $comboPrice = (float)($itemArr['price'] ?? $itemArr['rate'] ?? 0);
                                    $pNameUpper = strtoupper($pName);
                                    if ($comboPrice <= 0 || ($comboPrice > 10000 && str_contains($pNameUpper, '3K'))) {
                                        if (str_contains($pNameUpper, '3K')) $comboPrice = 3000;
                                        elseif (str_contains($pNameUpper, '5K')) $comboPrice = 5000;
                                        elseif (str_contains($pNameUpper, '8K')) $comboPrice = 8000;
                                        elseif (str_contains($pNameUpper, '10K')) $comboPrice = 10000;
                                    }
                                    $comboSubtotal += $comboPrice * $quantity;
                                } else {
                                    $originalPrice = (float)($itemArr['original_price'] ?? $itemArr['rate'] ?? $itemArr['price'] ?? 0);
                                    $regularSubtotal += $originalPrice * $quantity;
                                }
                            }
                        }
                        $discount70 = isset($order->discount_70_percent) && (float)$order->discount_70_percent > 0 ? (float)$order->discount_70_percent : round($regularSubtotal * 0.70, 2);
                        $afterDiscount70 = round($regularSubtotal - $discount70, 2);
                        $specialDiscount15 = isset($order->special_discount_15_percent) && (float)$order->special_discount_15_percent > 0 ? (float)$order->special_discount_15_percent : round($afterDiscount70 * 0.15, 2);
                        $afterSpecial15 = round($afterDiscount70 - $specialDiscount15, 2);
                        
                        $couponDiscount = (float)($order->coupon_discount ?? 0);
                        $afterCoupon = max(0, round($afterSpecial15 - $couponDiscount, 2));
                        $spinDiscount = (float)($order->lucky_spin_discount ?? 0);
                        $netBeforeCombos = max(0, round($afterCoupon - $spinDiscount, 2));

                        // 9. Total Amount = (After Coupon / Spin) + Net Rate Items
                        $totalAmount = round($netBeforeCombos + $comboSubtotal, 2);

                        // 10. Add Package 5% = 5% on Total Amount
                        $packing = isset($order->packing_charge_5_percent) && (float)$order->packing_charge_5_percent > 0 && abs((float)$order->packing_charge_5_percent - round($totalAmount * 0.05, 2)) < 5
                            ? (float)$order->packing_charge_5_percent
                            : (($totalAmount > 0) ? round($totalAmount * 0.05, 2) : 0);

                        $calculatedNetPayable = max(0, round($totalAmount + $packing));
                        $netPayable = isset($order->total_amount) && (float)$order->total_amount > 0 && abs((float)$order->total_amount - $calculatedNetPayable) <= 2
                            ? (float)$order->total_amount
                            : (isset($order->total) && (float)$order->total > 0 && abs((float)$order->total - $calculatedNetPayable) <= 2
                                ? (float)$order->total
                                : $calculatedNetPayable);

                        $receivedAmount = (isset($order->receive_amount) && is_numeric($order->receive_amount)) ? (float)$order->receive_amount : 0;
                        if ($receivedAmount == 0 && $order->status === 'confirmed' && (($order->payment_status ?? '') === 'paid' || ($order->payment->status ?? '') === 'paid')) {
                            $receivedAmount = $netPayable;
                        }
                        $balanceDue = max(0, $netPayable - $receivedAmount);
                    @endphp

                    <div class="flex justify-between items-center text-gray-700">
                        <span>Sub Total</span>
                        <span>₹{{ number_format($regularSubtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-700">
                        <span>Discount 70%</span>
                        <span>-₹{{ number_format($discount70, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-700">
                        <span>After Discount</span>
                        <span>₹{{ number_format($afterDiscount70, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-700">
                        <span>Spl Discount 15%</span>
                        <span>-₹{{ number_format($specialDiscount15, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-700">
                        <span>After Spl Discount</span>
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
                    @if($comboSubtotal > 0)
                        <div class="flex justify-between items-center text-purple-700 font-medium">
                            <span>Net Rate Items</span>
                            <span>₹{{ number_format($comboSubtotal, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center text-gray-900 font-bold border-t pt-1">
                        <span>Total Amount</span>
                        <span>₹{{ number_format($totalAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-700">
                        <span>Add Package 5%</span>
                        <span>₹{{ number_format($packing, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-base font-bold text-gray-900 border-t pt-2 mt-1">
                        <span>Net Payable Amount</span>
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
