@extends('layouts.admin')

@section('title', 'GST Bills Management')
@section('page-title', 'GST Bills')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-xl font-bold text-gray-900">GST Invoice & Tax Bills</h2>
            <p class="text-xs text-gray-500 mt-1">Generate and manage official GST B2B / B2C tax invoices for Radhe Crackers</p>
        </div>
        <a href="{{ route('admin.gst-bills.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition-colors flex items-center gap-2 shadow-sm">
            <i class="fas fa-plus"></i> Create New GST Bill
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total GST Bills</div>
            <div class="text-2xl font-bold text-purple-700 mt-1">{{ number_format($totalBillsCount) }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Grand Amount</div>
            <div class="text-2xl font-bold text-green-600 mt-1">₹{{ number_format($totalGrandAmount, 2) }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total CGST (9%)</div>
            <div class="text-2xl font-bold text-blue-600 mt-1">₹{{ number_format($totalCgst, 2) }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total SGST (9%)</div>
            <div class="text-2xl font-bold text-indigo-600 mt-1">₹{{ number_format($totalSgst, 2) }}</div>
        </div>
    </div>

    <!-- Flash messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 text-green-700 text-sm rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('admin.gst-bills.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Bill No, Customer Name, GSTIN..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>
            <div class="sm:col-span-3 flex justify-end gap-2">
                <button type="submit" class="bg-purple-600 text-white font-bold text-xs px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.gst-bills.index') }}" class="bg-gray-500 text-white font-bold text-xs px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Bills Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-xs">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase">Bill No</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase">Date</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase">Customer Name & GSTIN</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase">Items</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase">Subtotal</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase">Tax (CGST/SGST)</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase">Grand Total</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($gstBills as $bill)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-bold text-purple-700">{{ $bill->bill_number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $bill->bill_date ? $bill->bill_date->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="font-bold text-gray-900">{{ $bill->customer_name }}</div>
                            @if($bill->customer_gstin)
                                <div class="text-[10px] text-blue-600 font-semibold">GSTIN: {{ $bill->customer_gstin }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $bill->items->count() }} Items</td>
                        <td class="px-4 py-3 font-semibold text-gray-900">₹{{ number_format($bill->subtotal, 2) }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            @if($bill->cgst_amount > 0)
                                <div>CGST: ₹{{ number_format($bill->cgst_amount, 2) }}</div>
                                <div>SGST: ₹{{ number_format($bill->sgst_amount, 2) }}</div>
                            @elseif($bill->igst_amount > 0)
                                <div>IGST: ₹{{ number_format($bill->igst_amount, 2) }}</div>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 font-bold text-green-700">₹{{ number_format($bill->grand_total, 2) }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('admin.gst-bills.pdf', $bill->id) }}" target="_blank" class="bg-purple-100 hover:bg-purple-200 text-purple-800 font-bold px-2.5 py-1 rounded text-[11px] flex items-center gap-1 transition-colors" title="View / Print PDF">
                                    <i class="fas fa-file-pdf text-red-600"></i> PDF Bill
                                </a>
                                <form action="{{ route('admin.gst-bills.destroy', $bill->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this GST Bill?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-400">No GST Bills found. Click "Create New GST Bill" to add one.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $gstBills->links() }}
        </div>
    </div>
</div>
@endsection
