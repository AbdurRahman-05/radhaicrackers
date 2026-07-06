@extends('layouts.admin')

@section('title', 'Coupon Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Coupon Management</h1>
        <a href="{{ route('admin.coupons.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Create New Coupon
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-ticket-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Coupons</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $coupons->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Coupons</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $coupons->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Expiring Soon</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $coupons->where('expires_at', '<=', now()->addDays(7))->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Usage</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $coupons->sum('used_count') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">All Coupons</h2>
                <div class="flex space-x-2">
                    <input type="text" placeholder="Search coupons..." 
                           class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <select class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="percentage">Percentage</option>
                        <option value="fixed_amount">Fixed Amount</option>
                        <option value="bonus_items">Bonus Items</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($coupons as $coupon)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="font-mono text-sm font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded">
                                    {{ $coupon->code }}
                                </span>
                                <button onclick="copyToClipboard('{{ $coupon->code }}')" 
                                        class="ml-2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-copy text-xs"></i>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $coupon->name }}</div>
                                @if($coupon->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($coupon->description, 50) }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($coupon->type)
                                @case('percentage')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-percentage mr-1"></i>Percentage
                                    </span>
                                    @break
                                @case('fixed_amount')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-rupee-sign mr-1"></i>Fixed Amount
                                    </span>
                                    @break
                                @case('bonus_items')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-gift mr-1"></i>Bonus Items
                                    </span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @switch($coupon->type)
                                @case('percentage')
                                    {{ $coupon->value }}%
                                    @if($coupon->maximum_discount)
                                        <br><span class="text-xs text-gray-500">Max: ₹{{ $coupon->maximum_discount }}</span>
                                    @endif
                                    @break
                                @case('fixed_amount')
                                    ₹{{ $coupon->value }}
                                    @break
                                @case('bonus_items')
                                    @if($coupon->bonusProduct)
                                        {{ $coupon->bonus_quantity }}x {{ $coupon->bonusProduct->item_name }}
                                    @else
                                        <span class="text-red-500">Product not found</span>
                                    @endif
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $coupon->used_count }}
                            @if($coupon->usage_limit)
                                / {{ $coupon->usage_limit }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($coupon->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($coupon->expires_at)
                                @if($coupon->expires_at->isPast())
                                    <span class="text-red-600">Expired</span>
                                @elseif($coupon->expires_at->diffInDays(now()) <= 7)
                                    <span class="text-yellow-600">{{ $coupon->expires_at->format('M d, Y') }}</span>
                                @else
                                    {{ $coupon->expires_at->format('M d, Y') }}
                                @endif
                            @else
                                <span class="text-gray-500">No expiry</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.coupons.usage', $coupon) }}" 
                                   class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                                <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-toggle-{{ $coupon->is_active ? 'on' : 'off' }}"></i>
                                    </button>
                                </form>
                                @if($coupon->used_count == 0)
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" 
                                                onclick="return confirm('Are you sure you want to delete this coupon?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No coupons found. <a href="{{ route('admin.coupons.create') }}" class="text-blue-600 hover:text-blue-900">Create your first coupon</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target;
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check text-xs"></i>';
        button.classList.add('text-green-600');
        
        setTimeout(() => {
            button.innerHTML = originalIcon;
            button.classList.remove('text-green-600');
        }, 2000);
    });
}
</script>
@endsection 