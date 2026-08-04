@extends('layouts.app')
@section('title', 'My Orders & Tracking')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- Top Header & Stats -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 flex items-center gap-2">
                <span>📦 Order Tracking & History</span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">Track real-time delivery status, transport details, and invoices on a single screen.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- View Mode Switcher -->
            <div class="inline-flex rounded-xl bg-gray-200 p-1 border border-gray-300 shadow-inner">
                <button type="button" id="btnViewCards" onclick="switchView('cards')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-black transition-all bg-[#2D0B5A] text-white shadow-md">
                    <span>🎴 Cards (Single Screen)</span>
                </button>
                <button type="button" id="btnViewTable" onclick="switchView('table')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-gray-700 hover:text-gray-900 transition-all">
                    <span>📋 Table</span>
                </button>
            </div>

            <!-- Export CSV -->
            <a href="{{ route('user.orders.export.csv') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm px-4 py-2 rounded-xl shadow-md transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-gradient-to-br from-purple-900 to-[#1E093B] text-white p-3.5 sm:p-4 rounded-xl shadow-md border border-purple-800">
            <div class="text-[11px] font-bold text-purple-200 uppercase tracking-wider">Total Orders</div>
            <div class="text-xl sm:text-2xl font-black text-yellow-400 mt-0.5">{{ $totalOrders ?? count($orders) }}</div>
        </div>
        <div class="bg-amber-50 border border-amber-200 text-amber-900 p-3.5 sm:p-4 rounded-xl shadow-sm">
            <div class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Pending Orders</div>
            <div class="text-xl sm:text-2xl font-black text-amber-900 mt-0.5">{{ $pendingOrders ?? 0 }}</div>
        </div>
        <div class="bg-blue-50 border border-blue-200 text-blue-900 p-3.5 sm:p-4 rounded-xl shadow-sm">
            <div class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Confirmed</div>
            <div class="text-xl sm:text-2xl font-black text-blue-900 mt-0.5">{{ $confirmedOrders ?? 0 }}</div>
        </div>
        <div class="bg-purple-50 border border-purple-200 text-purple-900 p-3.5 sm:p-4 rounded-xl shadow-sm">
            <div class="text-[11px] font-bold text-purple-700 uppercase tracking-wider">Dispatched</div>
            <div class="text-xl sm:text-2xl font-black text-purple-900 mt-0.5">{{ $dispatchedOrders ?? 0 }}</div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 mb-6">
        <form method="GET" action="{{ route('user.orders') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Search Order ID, Mobile, Name..." class="px-3 py-2 border border-gray-300 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-medium">
            
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-purple-500 font-medium">
                <option value="">All Statuses</option>
                <option value="pending" @selected(request('status')=='pending')>Pending</option>
                <option value="confirmed" @selected(request('status')=='confirmed')>Confirmed</option>
                <option value="dispatched" @selected(request('status')=='dispatched')>Dispatched</option>
                <option value="completed" @selected(request('status')=='completed')>Completed</option>
                <option value="cancelled" @selected(request('status')=='cancelled')>Cancelled</option>
            </select>

            <select name="payment" class="px-3 py-2 border border-gray-300 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-purple-500 font-medium">
                <option value="">All Payments</option>
                <option value="paid" @selected(request('payment')=='paid')>Paid</option>
                <option value="pending" @selected(request('payment')=='pending')>Pending</option>
                <option value="failed" @selected(request('payment')=='failed')>Failed</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2 border border-gray-300 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-purple-500">
            
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-[#2D0B5A] hover:bg-purple-900 text-white font-extrabold text-xs sm:text-sm py-2 rounded-xl shadow transition-all">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status', 'payment', 'date_from', 'date_to']))
                    <a href="{{ route('user.orders') }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold text-xs rounded-xl flex items-center justify-center">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- CARDS VIEW (DEFAULT - NO HORIZONTAL SCROLL) -->
    <div id="cardsView" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($orders as $order)
            @php
                $statusColor = [
                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                    'confirmed' => 'bg-blue-100 text-blue-800 border-blue-300',
                    'dispatched' => 'bg-purple-100 text-purple-900 border-purple-300',
                    'completed' => 'bg-green-100 text-green-800 border-green-300',
                    'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                ][strtolower($order->status)] ?? 'bg-gray-100 text-gray-800';

                $paymentColor = [
                    'paid' => 'bg-green-100 text-green-800 border-green-300',
                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                    'failed' => 'bg-red-100 text-red-800 border-red-300',
                ][strtolower($order->payment_status ?? $order->payment->status ?? '')] ?? 'bg-gray-100 text-gray-800';
            @endphp

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden flex flex-col justify-between hover:shadow-xl transition-all duration-200">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-[#2D0B5A] via-[#1E093B] to-[#4A1584] text-white p-4 flex flex-wrap items-center justify-between gap-3 border-b border-purple-900">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">📦</span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base sm:text-lg font-black text-yellow-300 tracking-tight">Order #{{ $order->id }}</h3>
                            </div>
                            <div class="text-[11px] text-purple-200 font-medium">
                                {{ $order->created_at ? $order->created_at->format('d/m/Y \a\t h:i A') : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase border shadow-sm {{ $statusColor }}">
                            {{ strtolower($order->status ?? '') === 'dispatched' ? '🚚 Dispatched' : ucfirst($order->status) }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase border shadow-sm {{ $paymentColor }}">
                            {{ ucfirst($order->payment_status ?? $order->payment->status ?? 'Pending') }}
                        </span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-4 sm:p-5 space-y-4 flex-1">
                    
                    <!-- Transport & Delivery Highlights Box -->
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50/60 p-3.5 rounded-xl border-2 border-amber-300/80 shadow-sm space-y-2">
                        <div class="flex items-center justify-between border-b border-amber-200/80 pb-1.5">
                            <span class="text-xs font-black uppercase tracking-wider text-amber-900 flex items-center gap-1.5">
                                <span>🚚 Transport & Delivery Details</span>
                            </span>
                            <span class="text-[11px] font-bold uppercase px-2 py-0.5 rounded {{ $order->delivery_type === 'delivery' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }}">
                                {{ $order->delivery_type === 'delivery' ? '🚚 Lorry Transport' : '🏢 Godown Pickup' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            <div class="bg-white p-2 rounded-lg border border-amber-200/90 shadow-2xs">
                                <div class="text-[10px] font-bold text-gray-500 uppercase">Transport Provider</div>
                                <div class="font-extrabold text-purple-950 text-xs mt-0.5">
                                    {{ $order->transport_provider ?: 'Lorry Transport Assigned' }}
                                </div>
                            </div>
                            <div class="bg-white p-2 rounded-lg border border-amber-200/90 shadow-2xs">
                                <div class="text-[10px] font-bold text-gray-500 uppercase">Vehicle / LR Details</div>
                                <div class="font-extrabold text-purple-950 text-xs mt-0.5">
                                    {{ $order->transport_details ?: 'LR Number / Vehicle En Route' }}
                                </div>
                            </div>
                        </div>

                        <div class="text-xs space-y-1 pt-1 text-gray-800">
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-semibold">Delivery Point:</span>
                                <span class="font-bold text-gray-900">{{ $order->delivery_point ?: 'Main Branch' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-semibold">Destination Address:</span>
                                <span class="font-bold text-gray-900 text-right">
                                    {{ implode(', ', array_filter([$order->customer_city, $order->customer_district, $order->customer_state])) }}
                                    @if($order->pin_code) - {{ $order->pin_code }} @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer & Financial Summary -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs bg-gray-50 p-3 rounded-xl border border-gray-200">
                        <div class="space-y-1 border-b sm:border-b-0 sm:border-r border-gray-200 pb-2 sm:pb-0 sm:pr-2">
                            <div class="text-[10px] font-bold uppercase text-gray-400">Customer Info</div>
                            <div><strong class="text-gray-700">Name:</strong> <span class="font-bold text-gray-900">{{ $order->customer_name ?: 'N/A' }}</span></div>
                            <div><strong class="text-gray-700">Mobile:</strong> <span class="font-bold text-gray-900">{{ $order->customer_mobile ?: 'N/A' }}</span></div>
                            @if($order->customer_email)
                                <div><strong class="text-gray-700">Email:</strong> <span class="text-gray-800">{{ $order->customer_email }}</span></div>
                            @endif
                        </div>

                        <div class="space-y-1 sm:pl-2">
                            <div class="text-[10px] font-bold uppercase text-gray-400">Payment Breakdown</div>
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Total Amount:</span>
                                <span class="font-black text-purple-950 text-sm">₹{{ number_format($order->total_amount ?? $order->total, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Received Amount:</span>
                                <span class="font-bold text-green-700">
                                    @if(in_array(strtolower($order->status), ['confirmed','dispatched','completed']) && ($order->payment_status === 'paid' || ($order->payment->status ?? null) === 'paid'))
                                        ₹{{ number_format($order->total_amount ?? $order->total, 2) }}
                                    @elseif(isset($order->receive_amount) && is_numeric($order->receive_amount) && $order->receive_amount > 0)
                                        ₹{{ number_format($order->receive_amount, 2) }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            @if($order->coupon_code || $order->verify_code)
                                <div class="flex justify-between pt-1 border-t border-gray-200 text-[11px]">
                                    @if($order->coupon_code) <span><b class="text-gray-600">Coupon:</b> {{ $order->coupon_code }}</span> @endif
                                    @if($order->verify_code) <span><b class="text-gray-600">Code:</b> {{ $order->verify_code }}</span> @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="text-xs bg-yellow-50 p-2.5 rounded-lg border border-yellow-200 text-yellow-900">
                            <strong>Note:</strong> {{ $order->notes }}
                        </div>
                    @endif
                </div>

                <!-- Card Footer Actions -->
                <div class="bg-gray-100 p-3.5 border-t border-gray-200 flex items-center justify-between gap-2">
                    <a href="{{ route('user.orders.show', $order->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 bg-[#2D0B5A] hover:bg-purple-900 text-white font-extrabold text-xs py-2 px-3 rounded-xl shadow transition-all">
                        <svg class="w-4 h-4 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Details
                    </a>

                    <a href="{{ route('user.orders.invoice_pdf', $order->id) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-purple-700 hover:bg-purple-800 text-white font-extrabold text-xs py-2 px-3 rounded-xl shadow transition-all">
                        <svg class="w-4 h-4 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        PDF Bill
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-2xl shadow border text-center space-y-3">
                <div class="text-4xl">📦</div>
                <h3 class="text-lg font-bold text-gray-800">No Orders Found</h3>
                <p class="text-xs text-gray-500 max-w-md mx-auto">You have not placed any orders yet or no orders match your search filter.</p>
                <a href="{{ route('shop') }}" class="inline-block bg-[#2D0B5A] text-white text-xs font-black px-6 py-2.5 rounded-xl shadow">Browse Shop</a>
            </div>
        @endforelse
    </div>

    <!-- TABLE VIEW (OPTIONAL SWITCHABLE VIEW) -->
    <div id="tableView" class="hidden bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-xs divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Order ID</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Transport & Delivery</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Payment</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-black text-purple-950">#{{ $order->id }}</td>
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">
                                <div>{{ $order->customer_name }}</div>
                                <div class="text-[10px] text-gray-500">{{ $order->customer_mobile }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-amber-900">{{ $order->transport_provider ?: 'Lorry Transport' }}</div>
                                <div class="text-[10px] text-gray-500">{{ $order->transport_details ?: 'En Route' }} ({{ $order->delivery_point ?: 'Main Godown' }})</div>
                            </td>
                            <td class="px-4 py-3 font-black text-gray-900">₹{{ number_format($order->total_amount ?? $order->total, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ strtolower($order->status) === 'dispatched' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ strtolower($order->status) === 'dispatched' ? 'Dispatched' : ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-semibold">
                                <span class="px-2 py-0.5 rounded-full text-[10px] uppercase {{ strtolower($order->payment_status) === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($order->payment_status ?? 'Pending') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('user.orders.show', $order->id) }}" class="text-purple-700 hover:text-purple-900 font-bold mr-2">View</a>
                                <a href="{{ route('user.orders.invoice_pdf', $order->id) }}" target="_blank" class="text-emerald-700 hover:text-emerald-900 font-bold">PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function switchView(view) {
    const cards = document.getElementById('cardsView');
    const table = document.getElementById('tableView');
    const btnCards = document.getElementById('btnViewCards');
    const btnTable = document.getElementById('btnViewTable');

    if (view === 'cards') {
        cards.classList.remove('hidden');
        table.classList.add('hidden');
        btnCards.className = "flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-black transition-all bg-[#2D0B5A] text-white shadow-md";
        btnTable.className = "flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-gray-700 hover:text-gray-900 transition-all";
    } else {
        cards.classList.add('hidden');
        table.classList.remove('hidden');
        btnTable.className = "flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-black transition-all bg-[#2D0B5A] text-white shadow-md";
        btnCards.className = "flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-gray-700 hover:text-gray-900 transition-all";
    }
}
</script>
@endsection 