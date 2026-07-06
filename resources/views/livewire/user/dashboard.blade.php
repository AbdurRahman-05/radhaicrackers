<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-600 mt-2">Manage your orders and track their status</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Phone: {{ auth()->user()->phone }}</p>
                    <p class="text-sm text-gray-500">Member since: {{ auth()->user()->created_at->format('M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('order.form') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="text-4xl mb-4">🛒</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Place New Order</h3>
                <p class="text-gray-600">Order your favorite crackers and fireworks</p>
            </a>
            
            <a href="{{ route('price-list') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="text-4xl mb-4">💰</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">View Price List</h3>
                <p class="text-gray-600">Check current prices and availability</p>
            </a>
            
            <a href="{{ route('user.orders') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="text-4xl mb-4">📋</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">My Orders</h3>
                <p class="text-gray-600">View and track all your orders</p>
            </a>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Recent Orders</h2>
            
            @if (empty($orders))
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">📦</div>
                    <p class="text-gray-600">No orders yet</p>
                    <p class="text-sm text-gray-500 mb-4">Start shopping to see your orders here</p>
                    <a href="{{ route('order.form') }}" 
                       class="inline-flex items-center bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                        <span class="mr-2">🛒</span>
                        Place Your First Order
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Order ID</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Date</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Total</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Status</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <span class="font-medium text-gray-900">#{{ $order['id'] }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y') }}
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
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('order.show', $order['id']) }}" 
                                               class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                                View Details
                                            </a>
                                            <a href="{{ route('order.pdf', $order['id']) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Download PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('user.orders') }}" 
                       class="inline-flex items-center bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        View All Orders
                    </a>
                </div>
            @endif
        </div>

        <!-- Support Section -->
        <div class="mt-8 bg-orange-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
            <p class="text-gray-600 mb-4">Our support team is here to help you with any questions or issues.</p>
            <div class="flex flex-wrap gap-4">
                <a href="https://wa.me/919876543210" target="_blank" 
                   class="inline-flex items-center bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                    <span class="mr-2">💬</span>
                    WhatsApp Support
                </a>
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                    <span class="mr-2">📞</span>
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</div> 