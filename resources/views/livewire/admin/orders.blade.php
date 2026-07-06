<div class="p-6 bg-white rounded-lg shadow-md">
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif
    
    @if (session()->has('info'))
        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
            <i class="fas fa-info-circle mr-2"></i>
            {{ session('info') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Order Management</h2>
            <p class="text-gray-600">Manage and track all customer orders</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 mt-4 sm:mt-0">
            <button wire:click="exportOrders" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </button>
            
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-blue-600">{{ $totalOrders }}</div>
            <div class="text-sm text-blue-600">Total Orders</div>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-yellow-600">{{ $pendingOrders }}</div>
            <div class="text-sm text-yellow-600">Pending</div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-green-600">{{ $confirmedOrders }}</div>
            <div class="text-sm text-green-600">Confirmed</div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-purple-600">{{ $dispatchedOrders }}</div>
            <div class="text-sm text-purple-600">Dispatched</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input wire:model.live="search" type="text" placeholder="Order ID, Customer, Phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                <select wire:model.live="selected_year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Years</option>
                    @foreach($available_years as $yr)
                        <option value="{{ $yr }}">{{ $yr }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="dispatched">Dispatched</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment</label>
                <select wire:model.live="payment_filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Payments</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input wire:model.live="date_from" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input wire:model.live="date_to" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Type</label>
                <select wire:model.live="delivery_type_filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Orders</option>
                    <option value="none">None / Unassigned</option>
                    <option value="takeaway">Takeaway</option>
                    <option value="delivery">Delivery (Transport)</option>
                </select>
            </div>
        </div>
        <div class="mt-4">
            <button wire:click="clearFilters" class="text-gray-600 hover:text-gray-800 text-sm">Clear Filters</button>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 text-xs sm:text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-2 py-1.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-2 py-1.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    <th class="px-2 py-1.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer Info</th>
                    <th class="px-2 py-1.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Delivery Details</th>
                    <th class="px-2 py-1.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-2 py-1.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Receive Amount</th>
                    <th class="px-2 py-1.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-2 py-1.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Payment</th>
                    <th class="px-2 py-1.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-2 py-1.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Coupon</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-2 py-1.5 whitespace-nowrap font-bold text-xs">
                        <button wire:click="openEditModal({{ $order->id }})" class="text-blue-600 hover:text-blue-800 font-bold hover:underline" title="Click to Edit Order details">
                            #{{ $order->id }}
                        </button>
                    </td>
                    <td class="px-2 py-1.5 whitespace-nowrap">
                        <div class="flex space-x-1">
                            <button wire:click="openEditModal({{ $order->id }})" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-1 rounded border border-blue-200 flex items-center justify-center" title="Edit & View Order details">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <a href="{{ route('admin.orders.details', $order->id) }}" class="text-gray-600 hover:text-gray-900 bg-gray-50 p-1 rounded border border-gray-200 flex items-center justify-center" title="View details">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.orders.download_invoice_pdf', $order->id) }}" class="text-purple-600 hover:text-purple-900 bg-purple-50 p-1 rounded border border-purple-200 flex items-center justify-center" title="Download Invoice PDF">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                            </a>
                        </div>
                    </td>
                    <td class="px-2 py-1.5 whitespace-normal max-w-[150px] leading-tight text-xs">
                        <div class="font-semibold text-gray-900">{{ $order->customer_name }}</div>
                        <div class="text-[11px] text-gray-600">{{ $order->customer_mobile }}</div>
                        @if($order->customer_email)
                            <div class="text-[10px] text-gray-400 truncate max-w-[140px]">{{ $order->customer_email }}</div>
                        @endif
                    </td>
                    <td class="px-2 py-1.5 whitespace-normal max-w-[180px] leading-tight text-xs">
                        <div class="text-gray-900 font-medium">{{ $order->customer_city }}, {{ $order->customer_state }}</div>
                        <div class="text-[10px] text-gray-600">Del: {{ $order->delivery_point }} ({{ $order->pin_code }})</div>
                        @if($order->delivery_type === 'takeaway')
                            <span class="inline-block bg-orange-100 text-orange-800 text-[10px] px-1.5 py-0.5 rounded font-bold mt-1">Takeaway</span>
                        @elseif($order->delivery_type === 'delivery')
                            <span class="inline-block bg-green-100 text-green-800 text-[10px] px-1.5 py-0.5 rounded font-bold mt-1">Delivery</span>
                            @if($order->transport_provider || $order->transport_details)
                                <div class="text-[10px] text-purple-700 font-semibold mt-0.5" title="Transport Details">
                                    Tr: {{ $order->transport_provider ?: '-' }} ({{ $order->transport_details ?: '-' }})
                                </div>
                            @endif
                        @endif
                    </td>
                    <td class="px-2 py-1.5 whitespace-nowrap font-semibold text-gray-900 text-xs">
                        ₹{{ number_format($order->total, 2) }}
                    </td>
                    <td class="px-2 py-1.5 whitespace-nowrap">
                        @if(in_array($order->status, ['pending','confirmed','dispatched','completed']))
                            <div class="flex items-center space-x-1 min-w-[110px]">
                                @if(isset($editingReceiveAmountId) && $editingReceiveAmountId === $order->id)
                                    <form wire:submit.prevent="saveReceiveAmount({{ $order->id }})" class="flex items-center">
                                        <input type="number" step="0.01" wire:model.defer="receiveAmountInput" class="w-14 px-1 py-0.5 border border-gray-300 rounded text-[11px]" />
                                        <button type="submit" class="ml-1 px-1 bg-green-500 text-white text-[9px] rounded font-bold">Save</button>
                                        <button type="button" wire:click="cancelEditReceiveAmount" class="ml-0.5 px-1 bg-gray-400 text-white text-[9px] rounded font-bold">X</button>
                                    </form>
                                @else
                                    <span class="w-12 text-right text-xs">
                                        {{ number_format(($order->receive_amount !== null ? $order->receive_amount : (in_array($order->status, ['confirmed','dispatched','completed']) ? $order->total : 0)), 2) }}
                                    </span>
                                    <button wire:click="editReceiveAmount({{ $order->id }}, {{ $order->receive_amount ?? 0 }})" class="px-1 py-0.5 bg-yellow-500 text-white text-[9px] rounded hover:bg-yellow-600 transition-colors font-semibold">Edit</button>
                                @endif
                            </div>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                    <td class="px-2 py-1.5 whitespace-nowrap">
                        <span class="inline-flex px-1.5 py-0.5 text-[10px] font-semibold rounded-full
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($order->status === 'dispatched') bg-purple-100 text-purple-800
                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-2 py-1.5 whitespace-nowrap">
                        <span class="inline-flex px-1.5 py-0.5 text-[10px] font-semibold rounded-full
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="px-2 py-1.5 whitespace-nowrap text-xs text-gray-500">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-2 py-1.5 whitespace-nowrap text-xs text-gray-900">{{ $order->coupon_code ?: '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-2 py-1.5 text-center text-gray-500">No orders found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Edit Order Modal (Single Window Split View) -->
    @if($showEditModal && $editingOrder)
    <div class="fixed inset-0 bg-black bg-opacity-60 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4" id="editModal">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 max-w-5xl w-full max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="px-6 py-4 text-white flex items-center justify-between bg-[#1E093B] flex-shrink-0">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📝</span>
                    <div>
                        <h3 class="text-lg font-bold">Edit Order #{{ $editingOrderId }}</h3>
                        <p class="text-xs text-gray-300">Customer: {{ $editCustomerName }} • Created: {{ $editingOrder->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <button wire:click="closeEditModal" class="text-gray-300 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Content - Split View -->
            <div class="flex-1 overflow-y-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Section (2/3 width) - All Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer & Delivery Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Customer details (Editable) -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 pb-1 border-b border-gray-200 flex items-center gap-1.5">
                                👤 Customer Information
                            </h4>
                            <div class="space-y-2 text-xs">
                                <div>
                                    <label class="block text-gray-500 font-medium mb-0.5">Name</label>
                                    <input type="text" wire:model="editCustomerName" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:outline-none" />
                                    @error('editCustomerName') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-500 font-medium mb-0.5">Mobile</label>
                                    <input type="text" wire:model="editCustomerMobile" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:outline-none" />
                                    @error('editCustomerMobile') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-500 font-medium mb-0.5">Email</label>
                                    <input type="email" wire:model="editCustomerEmail" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:outline-none" />
                                    @error('editCustomerEmail') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                @if($editingOrder->verify_code)
                                    <div class="pt-1"><span class="font-medium text-gray-500">Verification Code:</span> <span class="bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded font-mono font-bold">{{ $editingOrder->verify_code }}</span></div>
                                @endif
                            </div>
                        </div>

                        <!-- Delivery Point details (Editable) -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 pb-1 border-b border-gray-200 flex items-center gap-1.5">
                                📍 Delivery Details
                            </h4>
                            <div class="space-y-2 text-xs">
                                <div>
                                    <label class="block text-gray-500 font-medium mb-0.5">Delivery Point</label>
                                    <input type="text" wire:model="editDeliveryPoint" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:outline-none" />
                                    @error('editDeliveryPoint') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-500 font-medium mb-0.5">City / Town</label>
                                    <input type="text" wire:model="editCustomerCity" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:outline-none" />
                                    @error('editCustomerCity') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-gray-500 font-medium mb-0.5">District</label>
                                        <input type="text" wire:model="editCustomerDistrict" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:outline-none" />
                                        @error('editCustomerDistrict') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-500 font-medium mb-0.5">State</label>
                                        <input type="text" wire:model="editCustomerState" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:outline-none" />
                                        @error('editCustomerState') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-500 font-medium mb-0.5">Pin Code</label>
                                    <input type="text" wire:model="editPinCode" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:outline-none" />
                                    @error('editPinCode') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <label class="block text-gray-500 font-medium mb-0.5">Delivery Type</label>
                                    <select wire:model.live="editDeliveryType" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:outline-none bg-white">
                                        <option value="none">None</option>
                                        <option value="takeaway">Takeaway</option>
                                        <option value="delivery">Delivery</option>
                                    </select>
                                    @error('editDeliveryType') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>

                                @if($editDeliveryType === 'delivery')
                                <div class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-gray-100">
                                    <div>
                                        <label class="block text-gray-500 font-medium mb-0.5">Transport Provider</label>
                                        <input type="text" wire:model="editTransportProvider" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:outline-none" placeholder="Lorry, Courier, etc." />
                                        @error('editTransportProvider') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-500 font-medium mb-0.5">Transport Details</label>
                                        <input type="text" wire:model="editTransportDetails" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:outline-none" placeholder="LR No., Vehicle No., etc." />
                                        @error('editTransportDetails') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Ordered Items List (Editable Quantities) -->
                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">📦 Order Items (Edit Qty directly)</h4>
                            <span class="text-xs bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full font-semibold">
                                {{ count($editingOrderItems) }} items
                            </span>
                        </div>
                        <div class="overflow-x-auto max-h-56 overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-100 text-xs">
                                <thead class="bg-gray-50 text-gray-500 font-medium">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Item Name</th>
                                        <th class="px-4 py-2 text-center" style="width: 100px;">Qty</th>
                                        <th class="px-4 py-2 text-right">Price</th>
                                        <th class="px-4 py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100 text-gray-700">
                                    @foreach($editingOrderItems as $index => $item)
                                    <tr>
                                        <td class="px-4 py-2 font-medium text-gray-900">{!! html_entity_decode($item['product_name'] ?? '-') !!}</td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="number" min="0" wire:model.live="editingOrderItems.{{ $index }}.quantity" class="w-16 px-1.5 py-0.5 border border-gray-300 rounded text-center font-bold text-xs focus:ring-1 focus:ring-purple-500 focus:outline-none" />
                                        </td>
                                        <td class="px-4 py-2 text-right">₹{{ number_format($item['price'], 2) }}</td>
                                        <td class="px-4 py-2 text-right font-semibold text-gray-900">₹{{ number_format($item['price'] * (int)($item['quantity'] ?? 0), 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Real-time Order Calculations Breakdown -->
                        @php
                            $calculatedTotals = $this->recalculateTotals();
                        @endphp
                        <div class="bg-gray-50 p-4 border-t border-gray-100 text-xs space-y-1.5">
                            <div class="flex justify-between text-gray-600">
                                <span>Items Subtotal (MRP):</span>
                                <span class="font-medium">₹{{ number_format($calculatedTotals['subtotal'], 2) }}</span>
                            </div>
                            @if($calculatedTotals['discount_70_percent'] > 0)
                            <div class="flex justify-between text-red-600">
                                <span>Wholesale Discount (70%):</span>
                                <span class="font-medium">-₹{{ number_format($calculatedTotals['discount_70_percent'], 2) }}</span>
                            </div>
                            @endif
                            @if($calculatedTotals['special_discount_15_percent'] > 0)
                            <div class="flex justify-between text-red-600">
                                <span>Special Discount (15%):</span>
                                <span class="font-medium">-₹{{ number_format($calculatedTotals['special_discount_15_percent'], 2) }}</span>
                            </div>
                            @endif
                            @if($calculatedTotals['packing_charge_5_percent'] > 0)
                            <div class="flex justify-between text-orange-600 font-medium">
                                <span>Packing & Delivery Charge (5%):</span>
                                <span>+₹{{ number_format($calculatedTotals['packing_charge_5_percent'], 2) }}</span>
                            </div>
                            @endif
                            @if($calculatedTotals['coupon_discount'] > 0)
                            <div class="flex justify-between text-green-600 font-medium">
                                <span>Coupon Discount ({{ $editingOrder->coupon_code }}):</span>
                                <span>-₹{{ number_format($calculatedTotals['coupon_discount'], 2) }}</span>
                            </div>
                            @endif
                            @if($calculatedTotals['gst_amount'] > 0)
                            <div class="flex justify-between text-blue-600 font-medium">
                                <span>GST (18%):</span>
                                <span>+₹{{ number_format($calculatedTotals['gst_amount'], 2) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-sm font-extrabold text-gray-900 border-t border-gray-200 pt-1.5 mt-1">
                                <span>Final Order Value:</span>
                                <span class="text-orange-600 text-base">₹{{ number_format($calculatedTotals['total'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Section (1/3 width) - Edit Form Controls -->
                <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 flex flex-col justify-between">
                    <form wire:submit.prevent="saveOrder" class="space-y-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 pb-2 border-b border-gray-200 flex items-center gap-1.5">
                            ⚙️ Update Order Info
                        </h4>

                        <div>
                            <label for="editStatus" class="block text-xs font-semibold text-gray-600 mb-1">Order Status</label>
                            <select id="editStatus" wire:model="editStatus" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="dispatched">Dispatched</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            @error('editStatus') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="editPaymentStatus" class="block text-xs font-semibold text-gray-600 mb-1">Payment Status</label>
                            <select id="editPaymentStatus" wire:model="editPaymentStatus" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="failed">Failed</option>
                            </select>
                            @error('editPaymentStatus') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="editReceiveAmount" class="block text-xs font-semibold text-gray-600 mb-1">Receive Amount (₹)</label>
                            <input type="number" step="0.01" id="editReceiveAmount" wire:model="editReceiveAmount" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white" placeholder="0.00" />
                            @error('editReceiveAmount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center pt-1 pb-2">
                            <input type="checkbox" id="editHasGst" wire:model.live="editHasGst" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" />
                            <label for="editHasGst" class="ml-2 block text-xs font-bold text-gray-700">Add 18% GST (Admin only)</label>
                        </div>
                        
                        <div>
                            <label for="editNotes" class="block text-xs font-semibold text-gray-600 mb-1">Internal Notes</label>
                            <textarea id="editNotes" wire:model="editNotes" rows="3" placeholder="Add custom comments..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white"></textarea>
                            @error('editNotes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex flex-col gap-2 pt-4">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg font-bold text-sm transition-colors flex items-center justify-center gap-1.5 shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Save Changes
                            </button>
                            <button type="button" wire:click="closeEditModal" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold text-sm transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session()->has('whatsapp_link'))
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="whatsappModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-4">WhatsApp Link Generated</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 mb-4">Click the link below to open WhatsApp with the pre-filled message:</p>
                    <a href="{{ session('whatsapp_link') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                        Open WhatsApp
                    </a>
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="document.getElementById('whatsappModal').style.display='none'" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('livewire:init', () => {
    console.log('Livewire Orders component initialized');
    
    Livewire.on('download-csv', () => {
        // Redirect to the export route to trigger download
        window.location.href = '{{ route("admin.export.orders") }}';
    });
});

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.id === 'editModal') {
        @this.closeEditModal();
    }
});
</script> 