<div>
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600">Welcome to the Cracker Shop admin panel</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['completed_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-semibold text-gray-900">₹{{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.orders') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="text-4xl mb-4">📋</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Manage Orders</h3>
                <p class="text-gray-600">View and update order status</p>
            </a>
            
            <a href="{{ route('admin.stock') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="text-4xl mb-4">📦</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Stock Management</h3>
                <p class="text-gray-600">Add and manage inventory</p>
            </a>
            
            <a href="{{ route('admin.payments') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="text-4xl mb-4">💰</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Payment Verification</h3>
                <p class="text-gray-600">Verify UPI payments</p>
            </a>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Recent Orders</h2>
                <a href="{{ route('admin.orders') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    View All Orders →
                </a>
            </div>
            
            @if (empty($recentOrders))
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">📦</div>
                    <p class="text-gray-600">No orders yet</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Order ID</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Customer</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Total</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Status</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentOrders as $order)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <span class="font-medium text-gray-900">#{{ $order['id'] }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $order['user']['name'] }}</p>
                                            <p class="text-sm text-gray-600">{{ $order['user']['phone'] }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-gray-900">
                                        ₹{{ number_format($order['total'], 2) }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-blue-100 text-blue-800',
                                                'dispatched' => 'bg-purple-100 text-purple-800',
                                                'completed' => 'bg-green-100 text-green-800'
                                            ];
                                            $color = $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-sm font-medium {{ $color }}">
                                            {{ ucfirst($order['status']) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div> 