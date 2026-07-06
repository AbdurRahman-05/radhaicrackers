@extends('layouts.admin')

@section('title', 'Coupon Usage - ' . $coupon->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Coupon Usage</h1>
                <p class="text-gray-600 mt-1">{{ $coupon->name }} ({{ $coupon->code }})</p>
            </div>
            <a href="{{ route('admin.coupons') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Coupons
            </a>
        </div>

        <!-- Coupon Summary -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Coupon Details</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium">Code:</span> <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $coupon->code }}</span></p>
                        <p><span class="font-medium">Type:</span> 
                            @switch($coupon->type)
                                @case('percentage')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-percentage mr-1"></i>Percentage
                                    </span>
                                    @break
                                @case('fixed_amount')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-rupee-sign mr-1"></i>Fixed Amount
                                    </span>
                                    @break
                                @case('bonus_items')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-gift mr-1"></i>Bonus Items
                                    </span>
                                    @break
                            @endswitch
                        </p>
                        <p><span class="font-medium">Value:</span> 
                            @switch($coupon->type)
                                @case('percentage')
                                    {{ $coupon->value }}%
                                    @if($coupon->maximum_discount)
                                        <br><span class="text-sm text-gray-500">Max: ₹{{ $coupon->maximum_discount }}</span>
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
                        </p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Usage Statistics</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium">Total Used:</span> {{ $coupon->used_count }}</p>
                        @if($coupon->usage_limit)
                            <p><span class="font-medium">Usage Limit:</span> {{ $coupon->usage_limit }}</p>
                            <p><span class="font-medium">Remaining:</span> {{ $coupon->usage_limit - $coupon->used_count }}</p>
                        @else
                            <p><span class="font-medium">Usage Limit:</span> <span class="text-gray-500">Unlimited</span></p>
                        @endif
                        @if($coupon->user_limit)
                            <p><span class="font-medium">Per User Limit:</span> {{ $coupon->user_limit }}</p>
                        @else
                            <p><span class="font-medium">Per User Limit:</span> <span class="text-gray-500">Unlimited</span></p>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Validity</h3>
                    <div class="space-y-2">
                        @if($coupon->starts_at)
                            <p><span class="font-medium">Starts:</span> {{ $coupon->starts_at->format('M d, Y H:i') }}</p>
                        @else
                            <p><span class="font-medium">Starts:</span> <span class="text-gray-500">Immediately</span></p>
                        @endif
                        @if($coupon->expires_at)
                            <p><span class="font-medium">Expires:</span> {{ $coupon->expires_at->format('M d, Y H:i') }}</p>
                            @if($coupon->expires_at->isPast())
                                <p class="text-red-600 font-medium">Expired</p>
                            @elseif($coupon->expires_at->diffInDays(now()) <= 7)
                                <p class="text-yellow-600 font-medium">Expiring Soon</p>
                            @endif
                        @else
                            <p><span class="font-medium">Expires:</span> <span class="text-gray-500">Never</span></p>
                        @endif
                        <p><span class="font-medium">Status:</span> 
                            @if($coupon->is_active)
                                <span class="text-green-600 font-medium">Active</span>
                            @else
                                <span class="text-red-600 font-medium">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Restrictions</h3>
                    <div class="space-y-2">
                        @if($coupon->minimum_order_amount > 0)
                            <p><span class="font-medium">Min Order:</span> ₹{{ $coupon->minimum_order_amount }}</p>
                        @else
                            <p><span class="font-medium">Min Order:</span> <span class="text-gray-500">No minimum</span></p>
                        @endif
                        @if(!empty($coupon->applies_to_categories))
                            <p><span class="font-medium">Categories:</span> {{ implode(', ', $coupon->applies_to_categories) }}</p>
                        @else
                            <p><span class="font-medium">Categories:</span> <span class="text-gray-500">All categories</span></p>
                        @endif
                        @if(!empty($coupon->excluded_products))
                            <p><span class="font-medium">Excluded:</span> {{ count($coupon->excluded_products) }} products</p>
                        @else
                            <p><span class="font-medium">Excluded:</span> <span class="text-gray-500">None</span></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage History -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Usage History</h2>
            </div>

            @if($usages->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bonus Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Used At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($usages as $usage)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $usage->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $usage->user->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.orders.details', $usage->order->id) }}" class="text-blue-600 hover:text-blue-900">
                                                Order #{{ $usage->order->id }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">₹{{ $usage->order->total }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($usage->discount_amount > 0)
                                        <span class="text-green-600 font-medium">-₹{{ $usage->discount_amount }}</span>
                                    @else
                                        <span class="text-gray-500">No discount</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if(!empty($usage->bonus_items_added))
                                        <div class="space-y-1">
                                            @foreach($usage->bonus_items_added as $bonus)
                                                <div class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                                    {{ $bonus['quantity'] }}x {{ $bonus['product_name'] }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-500">None</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $usage->used_at->format('M d, Y H:i') }}
                                    <div class="text-xs text-gray-500">{{ $usage->used_at->diffForHumans() }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($usages->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $usages->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-ticket-alt text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Usage History</h3>
                    <p class="text-gray-500">This coupon hasn't been used yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 