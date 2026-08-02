@extends('layouts.app')

@section('title', 'Track Your Order - Radhe Crackers')

@section('content')
<div class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Search Card -->
        <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 mb-8 border border-gray-100">
            <div class="text-center mb-6">
                <span class="inline-block bg-purple-100 text-purple-950 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                    📦 LIVE ORDER TRACKING
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">Track Your Order Status</h1>
                <p class="text-sm text-gray-600">Enter your Order ID or Mobile Number to check real-time delivery and dispatch updates</p>
            </div>

            <form method="POST" action="{{ route('track-order.track') }}" class="max-w-xl mx-auto space-y-4">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" 
                           id="tracking_number" 
                           name="tracking_number" 
                           class="flex-1 px-4 py-3.5 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-semibold text-gray-900 text-sm sm:text-base placeholder-gray-400"
                           placeholder="Enter Order ID (e.g. 1024) or Mobile Number"
                           required
                           value="{{ old('tracking_number', $trackingNumber ?? '') }}">
                    
                    <button type="submit" 
                            class="px-8 py-3.5 text-white font-extrabold text-sm sm:text-base rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 bg-[#2D0B5A] hover:bg-purple-900 active:scale-95">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Track Order
                    </button>
                </div>
            </form>

            @if(isset($error))
                <div class="mt-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-center font-medium text-sm">
                    ⚠️ {{ $error }}
                </div>
            @endif
        </div>

        <!-- Tracking Results Section -->
        @if(isset($orders) && $orders->count() > 0)
            <div class="space-y-6">
                <h2 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                    <span>📋 Order Results</span>
                    <span class="bg-purple-600 text-white text-xs font-black px-2.5 py-0.5 rounded-full">{{ $orders->count() }} Found</span>
                </h2>

                @foreach($orders as $order)
                    @php
                        $statusClass = [
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-300',
                            'dispatched' => 'bg-purple-100 text-purple-800 border-purple-300',
                            'completed' => 'bg-green-100 text-green-800 border-green-300',
                            'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                        ][strtolower($order->status)] ?? 'bg-gray-100 text-gray-800';

                        $paymentClass = [
                            'paid' => 'bg-green-100 text-green-800 border-green-300',
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                            'failed' => 'bg-red-100 text-red-800 border-red-300',
                        ][strtolower($order->payment_status)] ?? 'bg-gray-100 text-gray-800';

                        $statusSteps = ['pending', 'confirmed', 'dispatched', 'completed'];
                        $currentStepIndex = array_search(strtolower($order->status), $statusSteps);
                        if ($currentStepIndex === false) $currentStepIndex = 0;
                    @endphp

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <!-- Order Header Bar -->
                        <div class="bg-gradient-to-r from-[#2D0B5A] to-[#1E093B] text-white p-5 flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">📦</span>
                                    <h3 class="text-lg font-black tracking-wide">Order #{{ $order->id }}</h3>
                                </div>
                                <p class="text-xs text-purple-200 mt-0.5">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase border {{ $statusClass }}">
                                    Status: {{ strtolower($order->status ?? '') === 'dispatched' ? 'Dispatched (Out for Delivery)' : ucfirst($order->status) }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase border {{ $paymentClass }}">
                                    Payment: {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar Timeline -->
                        @if(strtolower($order->status) !== 'cancelled')
                        <div class="p-6 bg-purple-50/50 border-b border-gray-100">
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-purple-900 mb-4">Delivery Progress Timeline</h4>
                            <div class="grid grid-cols-4 gap-2 relative">
                                @php
                                    $steps = [
                                        ['title' => 'Order Placed', 'icon' => '📝'],
                                        ['title' => 'Confirmed', 'icon' => '✅'],
                                        ['title' => 'Out for Delivery / Dispatched', 'icon' => '🚚'],
                                        ['title' => 'Delivered', 'icon' => '🎉']
                                    ];
                                @endphp

                                @foreach($steps as $idx => $step)
                                    @php $isDone = $idx <= $currentStepIndex; @endphp
                                    <div class="flex flex-col items-center text-center relative z-10">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm mb-1.5 transition-all shadow-md {{ $isDone ? 'bg-purple-700 text-white ring-4 ring-purple-200 scale-105' : 'bg-gray-200 text-gray-400' }}">
                                            <span>{{ $step['icon'] }}</span>
                                        </div>
                                        <span class="text-[11px] font-bold leading-tight {{ $isDone ? 'text-purple-900' : 'text-gray-400' }}">
                                            {{ $step['title'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Main Order Details Grid -->
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Delivery & Vehicle Details Card -->
                            <div class="bg-gradient-to-br from-amber-50/50 to-orange-50/40 p-4 sm:p-5 rounded-xl border-2 border-amber-200/70 space-y-3">
                                <h4 class="text-xs font-black uppercase tracking-wider text-amber-900 flex items-center gap-1.5 border-b border-amber-200 pb-2">
                                    <span>🚚 Delivery & Transport Details</span>
                                </h4>

                                <div class="space-y-2 text-xs text-gray-800">
                                    <div class="flex items-start justify-between">
                                        <span class="text-gray-500 font-semibold">Delivery Method:</span>
                                        <span class="font-extrabold uppercase {{ $order->delivery_type === 'delivery' ? 'text-green-700' : 'text-amber-700' }}">
                                            {{ $order->delivery_type === 'delivery' ? '🚚 Home/Transport Delivery' : '🏢 Takeaway (Godown Pickup)' }}
                                        </span>
                                    </div>

                                    @if($order->delivery_type === 'delivery' || $order->transport_provider || $order->transport_details)
                                        <div class="flex items-start justify-between bg-white p-2.5 rounded-lg border border-amber-200">
                                            <span class="text-gray-600 font-bold">Transport Provider:</span>
                                            <span class="font-black text-purple-950 text-right">
                                                {{ $order->transport_provider ?: 'Assigned Lorry Transport' }}
                                            </span>
                                        </div>

                                        <div class="flex items-start justify-between bg-white p-2.5 rounded-lg border border-amber-200">
                                            <span class="text-gray-600 font-bold">Vehicle / LR Details:</span>
                                            <span class="font-black text-purple-950 text-right">
                                                {{ $order->transport_details ?: 'Vehicle Assigned & In Transit' }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="flex items-start justify-between">
                                        <span class="text-gray-500 font-semibold">Delivery Destination:</span>
                                        <span class="font-bold text-gray-900 text-right max-w-[200px]">
                                            {{ $order->delivery_point ?: 'Sivakasi Main Office' }}
                                        </span>
                                    </div>

                                    <div class="flex items-start justify-between pt-1 border-t border-amber-200/50">
                                        <span class="text-gray-500 font-semibold">Address:</span>
                                        <span class="font-bold text-gray-900 text-right max-w-[220px]">
                                            {{ implode(', ', array_filter([$order->customer_city, $order->customer_district, $order->customer_state])) }}
                                            @if($order->pin_code) - {{ $order->pin_code }} @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer & Payment Summary Card -->
                            <div class="bg-gray-50 p-4 sm:p-5 rounded-xl border border-gray-200 space-y-3">
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-700 flex items-center justify-between border-b border-gray-200 pb-2">
                                    <span>👤 Customer & Payment</span>
                                    <span class="text-purple-700 font-black">₹{{ number_format($order->total, 2) }}</span>
                                </h4>

                                <div class="space-y-2 text-xs text-gray-800">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 font-semibold">Customer Name:</span>
                                        <span class="font-bold text-gray-900">{{ $order->customer_name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 font-semibold">Mobile Number:</span>
                                        <span class="font-bold text-gray-900">{{ $order->customer_mobile }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 font-semibold">Total Amount:</span>
                                        <span class="font-black text-orange-600 text-sm">₹{{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 font-semibold">Payment Received:</span>
                                        <span class="font-bold text-green-700">₹{{ number_format($order->receive_amount ?? (in_array(strtolower($order->status), ['confirmed','dispatched','completed']) ? $order->total : 0), 2) }}</span>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-gray-200">
                                    <a href="{{ route('user.orders.invoice_pdf', $order->id) }}" 
                                       target="_blank"
                                       class="w-full inline-flex items-center justify-center gap-2 bg-purple-700 hover:bg-purple-800 text-white font-extrabold text-xs py-2.5 rounded-lg shadow transition-colors">
                                        <svg class="w-4 h-4 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download Order Bill / GST Invoice
                                    </a>
                                </div>
                            </div>

                        </div>

                        <!-- Items Breakdown Accordion/Table -->
                        @if($order->items && count($order->items) > 0)
                        <div class="px-6 pb-6 border-t border-gray-100 pt-4">
                            <h4 class="text-xs font-black uppercase tracking-wider text-gray-600 mb-3 flex items-center gap-1.5">
                                <span>🛍️ Purchased Items ({{ count($order->items) }})</span>
                            </h4>
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="w-full text-left text-xs text-gray-700">
                                    <thead class="bg-gray-100 text-gray-800 font-bold uppercase">
                                        <tr>
                                            <th class="p-2.5">Item Name</th>
                                            <th class="p-2.5 text-center">Qty</th>
                                            <th class="p-2.5 text-right">Price</th>
                                            <th class="p-2.5 text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td class="p-2.5 font-semibold text-gray-900">{{ $item->product_name }}</td>
                                                <td class="p-2.5 text-center font-bold">{{ $item->quantity }}</td>
                                                <td class="p-2.5 text-right">₹{{ number_format($item->price, 2) }}</td>
                                                <td class="p-2.5 text-right font-bold">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection