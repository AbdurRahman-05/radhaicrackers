@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class=" rounded-lg p-6 text-white"style="background-color: #1E093B;">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}!</h2>
                <p class="text-gray-100 mt-1">Here's what's happening with your business today.</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-line text-6xl text-gray-200"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_orders']) }}</p>
                </div>
            </div>
        </div>

        <!-- Verified Payments -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-credit-card text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Verified Payments</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['verified_payments']) }}</p>
                </div>
            </div>
        </div>

        <!-- Stock Items -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Stock Items</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['stock_items']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Orders -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Today's Orders</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['today_orders'] }}</p>
                        <p class="text-sm text-gray-600">Orders placed today</p>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-calendar-day text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Payments -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Today's Payments</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['today_payments'] }}</p>
                        <p class="text-sm text-gray-600">Payments verified today</p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
        </div>
        <div class="p-6">
            @if($stats['recent_activity']->count() > 0)
                <div class="space-y-4">
                    @foreach($stats['recent_activity'] as $activity)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-shopping-cart text-gray-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    Order #{{ $activity->order->id }} - {{ $activity->order->user->name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $activity->notes }} - {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($activity->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($activity->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($activity->status === 'dispatched') bg-purple-100 text-purple-800
                                    @elseif($activity->status === 'completed') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($activity->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No recent activity</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.orders') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-shopping-cart text-red-600 text-xl mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">View Orders</p>
                        <p class="text-sm text-gray-500">Manage all orders</p>
                    </div>
                </a>

                <a href="{{ route('admin.payments') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-credit-card text-green-600 text-xl mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Verify Payments</p>
                        <p class="text-sm text-gray-500">Check payment status</p>
                    </div>
                </a>

                <a href="{{ route('admin.stocks') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-boxes text-blue-600 text-xl mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Manage Stock</p>
                        <p class="text-sm text-gray-500">Update inventory</p>
                    </div>
                </a>

                <a href="{{ route('admin.pdf-manager') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-file-pdf text-purple-600 text-xl mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">PDF Manager</p>
                        <p class="text-sm text-gray-500">Generate documents</p>
                    </div>
                </a>

                <a href="{{ route('admin.coupons') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-ticket-alt text-orange-600 text-xl mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Coupons</p>
                        <p class="text-sm text-gray-500">Manage discount codes</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 