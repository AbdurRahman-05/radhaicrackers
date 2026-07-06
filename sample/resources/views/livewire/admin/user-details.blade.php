<div class="space-y-6">
    <!-- User Info -->
    <div class="bg-white rounded-lg shadow p-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $user->name }}</h2>
            <p class="text-gray-600 mb-1"><span class="font-semibold">Phone:</span> {{ $user->phone }}</p>
            <p class="text-gray-600 mb-1"><span class="font-semibold">Last OTP Verified:</span> {{ $user->last_otp_verified_at ? $user->last_otp_verified_at->diffForHumans() : 'Never' }}</p>
            <p class="text-gray-600"><span class="font-semibold">Status:</span> {{ $user->is_blocked ? 'Blocked' : 'Active' }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-2">
            @if($user->is_blocked)
                <button wire:click="$emit('unblockUser', $user->id)" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Unblock</button>
            @else
                <button wire:click="$emit('blockUser', $user->id)" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Block</button>
            @endif
        </div>
    </div>

    <!-- User Orders -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order History</h3>
        @if($orders->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-4 py-2 font-semibold text-gray-900">#{{ $order->id }}</td>
                                <td class="px-4 py-2 text-gray-700">₹{{ number_format($order->total, 2) }}</td>
                                <td class="px-4 py-2">
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'dispatched') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-gray-600">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.orders.details', $order->id) }}" class="text-blue-600 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No orders found for this user.</p>
        @endif
    </div>
</div> 