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

    <!-- TODAY'S REAL-TIME BREAKDOWN SECTION -->
    <div class="bg-white rounded-xl shadow-lg border border-purple-100 overflow-hidden">
        <!-- Header Bar -->
        <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100 bg-gradient-to-r from-purple-900 via-indigo-900 to-slate-900 text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-xl">
                    ⚡
                </div>
                <div>
                    <h3 class="text-xl font-bold tracking-tight">Today's Real-time Breakdown</h3>
                    <p class="text-xs text-purple-200 mt-0.5">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping mr-1"></span>
                        Live activity summary for {{ date('d M Y, l') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-purple-100 border border-white/20">
                    Total Today Actions: {{ $todayBreakdown['timeline']->count() }}
                </span>
            </div>
        </div>

        <!-- Today Summary Stat Cards -->
        <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-4 bg-purple-50/50 border-b border-purple-100">
            <!-- Today Orders -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100">
                <div class="flex items-center justify-between text-xs text-gray-500 font-medium mb-1">
                    <span>🛍️ Orders Today</span>
                    <span class="text-emerald-600 font-bold">₹{{ number_format($todayBreakdown['orders_revenue'], 2) }}</span>
                </div>
                <div class="text-2xl font-extrabold text-gray-900">
                    {{ number_format($todayBreakdown['orders_count']) }} <span class="text-xs font-normal text-gray-500">Orders</span>
                </div>
            </div>

            <!-- Today Verified Payments -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-blue-100">
                <div class="flex items-center justify-between text-xs text-gray-500 font-medium mb-1">
                    <span>💳 Paid & Verified</span>
                    <span class="text-blue-600 font-bold">₹{{ number_format($todayBreakdown['payments_amount'], 2) }}</span>
                </div>
                <div class="text-2xl font-extrabold text-gray-900">
                    {{ number_format($todayBreakdown['payments_count']) }} <span class="text-xs font-normal text-gray-500">Verified</span>
                </div>
            </div>

            <!-- Today New Registrations -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-indigo-100">
                <div class="flex items-center justify-between text-xs text-gray-500 font-medium mb-1">
                    <span>👤 New Customers</span>
                    <span class="text-indigo-600 font-bold">Today</span>
                </div>
                <div class="text-2xl font-extrabold text-gray-900">
                    {{ number_format($todayBreakdown['users_count']) }} <span class="text-xs font-normal text-gray-500">Registered</span>
                </div>
            </div>

            <!-- Today GST Bills -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-purple-100">
                <div class="flex items-center justify-between text-xs text-gray-500 font-medium mb-1">
                    <span>🧾 GST Bills</span>
                    <span class="text-purple-600 font-bold">₹{{ number_format($todayBreakdown['gst_bills_amount'], 2) }}</span>
                </div>
                <div class="text-2xl font-extrabold text-gray-900">
                    {{ number_format($todayBreakdown['gst_bills_count']) }} <span class="text-xs font-normal text-gray-500">Generated</span>
                </div>
            </div>
        </div>

        <!-- Today Action Timeline Feed -->
        <div class="p-5" x-data="{ activeTab: 'all' }">
            <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                <h4 class="font-bold text-gray-800 text-sm uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list-ul text-purple-600"></i> Today's Live Actions Stream
                </h4>
                
                <!-- Quick Filter Tabs -->
                <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg text-xs font-medium">
                    <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-white shadow text-purple-700 font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-3 py-1 rounded-md transition-all">
                        All ({{ $todayBreakdown['timeline']->count() }})
                    </button>
                    <button @click="activeTab = 'order_created'" :class="activeTab === 'order_created' ? 'bg-white shadow text-purple-700 font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-3 py-1 rounded-md transition-all">
                        Orders ({{ $todayBreakdown['orders_count'] }})
                    </button>
                    <button @click="activeTab = 'order_log'" :class="activeTab === 'order_log' ? 'bg-white shadow text-purple-700 font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-3 py-1 rounded-md transition-all">
                        Status Updates
                    </button>
                    <button @click="activeTab = 'payment'" :class="activeTab === 'payment' ? 'bg-white shadow text-purple-700 font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-3 py-1 rounded-md transition-all">
                        Payments
                    </button>
                    <button @click="activeTab = 'user_registered'" :class="activeTab === 'user_registered' ? 'bg-white shadow text-purple-700 font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-3 py-1 rounded-md transition-all">
                        Registrations
                    </button>
                </div>
            </div>

            @if($todayBreakdown['timeline']->count() > 0)
                <div class="space-y-3 max-h-[450px] overflow-y-auto pr-1">
                    @foreach($todayBreakdown['timeline'] as $action)
                        <div x-show="activeTab === 'all' || activeTab === '{{ $action['type'] }}'" class="p-3.5 rounded-xl border border-gray-100 hover:border-purple-200 bg-gray-50/50 hover:bg-white transition-all shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white shadow-sm border border-gray-200 flex items-center justify-center text-sm flex-shrink-0 mt-0.5">
                                    <i class="{{ $action['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border {{ $action['badge_color'] }}">
                                            {{ $action['badge'] }}
                                        </span>
                                        <span class="text-xs font-semibold text-gray-900">
                                            {{ $action['title'] }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $action['subtitle'] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between sm:justify-end gap-3 flex-shrink-0 border-t sm:border-t-0 pt-2 sm:pt-0 border-gray-100">
                                <div class="text-right">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $action['status_color'] }}">
                                        {{ $action['status_badge'] }}
                                    </span>
                                    <div class="text-[11px] text-gray-400 font-mono mt-0.5">
                                        <i class="far fa-clock text-[10px] mr-0.5"></i> {{ $action['time'] }}
                                    </div>
                                </div>
                                <a href="{{ $action['link'] }}" class="px-2.5 py-1 text-xs font-semibold bg-purple-50 text-purple-700 hover:bg-purple-600 hover:text-white rounded-lg transition-colors flex items-center gap-1">
                                    View <i class="fas fa-chevron-right text-[9px]"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mx-auto text-xl mb-3">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <h5 class="font-bold text-gray-700 text-sm">No Site Actions Recorded Today Yet</h5>
                    <p class="text-xs text-gray-500 mt-1">When customers place orders, make payments, or register today, their actions will stream live here!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Year Filter Bar -->
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <div class="text-sm font-medium text-gray-700">
            Filtering stats for: <span class="font-bold text-blue-600">{{ $selectedYear === 'all' ? 'All Years' : $selectedYear }}</span>
        </div>
        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center">
            <label for="year-select" class="mr-2 text-sm text-gray-600 font-medium">Select Year:</label>
            <select id="year-select" name="year" onchange="this.form.submit()" class="px-3 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-700 bg-white">
                <option value="all" {{ $selectedYear === 'all' ? 'selected' : '' }}>All Years</option>
                @foreach($years as $yr)
                    <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                @endforeach
            </select>
        </form>
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
                    <p class="text-2xl font-semibold text-gray-900">₹{{ number_format($stats['verified_payments'], 2) }}</p>
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

    <!-- Order Status Breakdown -->
    <div>
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            📦 Order Status Breakdown
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending Orders -->
            <a href="{{ route('admin.orders', ['status_filter' => 'pending']) }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md hover:border-yellow-300 border border-transparent transition-all duration-200 block group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 transition-colors group-hover:bg-yellow-200">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_orders'] ?? 0) }}</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-yellow-600 transition-colors"></i>
                </div>
            </a>

            <!-- Confirmed Orders -->
            <a href="{{ route('admin.orders', ['status_filter' => 'confirmed']) }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md hover:border-blue-300 border border-transparent transition-all duration-200 block group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 transition-colors group-hover:bg-blue-200">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Confirmed Orders</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['confirmed_orders'] ?? 0) }}</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                </div>
            </a>

            <!-- Dispatched Orders -->
            <a href="{{ route('admin.orders', ['status_filter' => 'dispatched']) }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md hover:border-purple-300 border border-transparent transition-all duration-200 block group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600 transition-colors group-hover:bg-purple-200">
                            <i class="fas fa-truck text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Dispatched Orders</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['dispatched_orders'] ?? 0) }}</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-purple-600 transition-colors"></i>
                </div>
            </a>

            <!-- Completed Orders -->
            <a href="{{ route('admin.orders', ['status_filter' => 'completed']) }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md hover:border-green-300 border border-transparent transition-all duration-200 block group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 transition-colors group-hover:bg-green-200">
                            <i class="fas fa-flag-checkered text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Completed Orders</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['completed_orders'] ?? 0) }}</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-green-600 transition-colors"></i>
                </div>
            </a>
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
                                    @if($activity->order)
                                        Order #{{ $activity->order->id }} - {{ $activity->order->user->name }}
                                    @else
                                        Order not found
                                    @endif
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

               

                <a href="{{ route('admin.stocks') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-boxes text-blue-600 text-xl mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Manage Stock</p>
                        <p class="text-sm text-gray-500">Update inventory</p>
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