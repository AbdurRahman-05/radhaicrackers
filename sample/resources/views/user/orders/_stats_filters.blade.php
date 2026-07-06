<div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
    <!-- Statistics Cards -->
    <div class="bg-white rounded shadow p-4 text-center">
        <div class="text-gray-500 text-sm">Total Orders</div>
        <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
    </div>
    <div class="bg-white rounded shadow p-4 text-center">
        <div class="text-gray-500 text-sm">Pending</div>
        <div class="text-2xl font-bold">{{ $stats['pending'] ?? 0 }}</div>
    </div>
    <div class="bg-white rounded shadow p-4 text-center">
        <div class="text-gray-500 text-sm">Confirmed</div>
        <div class="text-2xl font-bold">{{ $stats['confirmed'] ?? 0 }}</div>
    </div>
    <div class="bg-white rounded shadow p-4 text-center">
        <div class="text-gray-500 text-sm">Dispatched</div>
        <div class="text-2xl font-bold">{{ $stats['dispatched'] ?? 0 }}</div>
    </div>
</div>

<!-- Filters -->
<form method="GET" class="mb-6 flex flex-wrap gap-2 items-end">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..." class="form-input rounded border-gray-300 w-48" />
    <select name="status" class="form-select rounded border-gray-300">
        <option value="">All Statuses</option>
        <option value="pending" @selected(request('status')=='pending')>Pending</option>
        <option value="confirmed" @selected(request('status')=='confirmed')>Confirmed</option>
        <option value="dispatched" @selected(request('status')=='dispatched')>Dispatched</option>
        <option value="delivered" @selected(request('status')=='delivered')>Delivered</option>
        <option value="cancelled" @selected(request('status')=='cancelled')>Cancelled</option>
    </select>
    <select name="payment" class="form-select rounded border-gray-300">
        <option value="">All Payments</option>
        <option value="paid" @selected(request('payment')=='paid')>Paid</option>
        <option value="unpaid" @selected(request('payment')=='unpaid')>Unpaid</option>
    </select>
    <input type="date" name="from" value="{{ request('from') }}" class="form-input rounded border-gray-300" />
    <input type="date" name="to" value="{{ request('to') }}" class="form-input rounded border-gray-300" />
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
</form> 