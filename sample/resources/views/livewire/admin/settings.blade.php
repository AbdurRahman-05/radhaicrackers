<div class="p-6 bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">System Settings</h2>
            <p class="text-gray-600">Configure business and system settings</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-blue-600">{{ $totalUsers }}</div>
            <div class="text-sm text-blue-600">Total Users</div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-green-600">{{ $totalOrders }}</div>
            <div class="text-sm text-green-600">Total Orders</div>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-yellow-600">{{ $totalStocks }}</div>
            <div class="text-sm text-yellow-600">Total Stocks</div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-purple-600">{{ $totalPayments }}</div>
            <div class="text-sm text-purple-600">Total Payments</div>
        </div>
    </div>

    <!-- Business Settings -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Business Settings</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
                <input wire:model="businessName" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('businessName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business Email</label>
                <input wire:model="businessEmail" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('businessEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business Phone</label>
                <input wire:model="businessPhone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('businessPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">UPI ID</label>
                <input wire:model="upiId" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('upiId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Business Address</label>
            <textarea wire:model="businessAddress" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            @error('businessAddress') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="mt-4">
            <button wire:click="saveBusinessSettings" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Save Business Settings</button>
        </div>
    </div>

    <!-- System Settings -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">System Configuration</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OTP Expiry (minutes)</label>
                <input wire:model="otpExpiryMinutes" type="number" min="1" max="60" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('otpExpiryMinutes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock Release Interval (minutes)</label>
                <input wire:model="stockReleaseInterval" type="number" min="1" max="60" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('stockReleaseInterval') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock Release Quantity</label>
                <input wire:model="stockReleaseQuantity" type="number" min="1" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('stockReleaseQuantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock Expiry (minutes)</label>
                <input wire:model="stockExpiryMinutes" type="number" min="1" max="60" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('stockExpiryMinutes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Business Number</label>
                <input wire:model="whatsappBusinessNumber" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('whatsappBusinessNumber') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <div class="mt-6 space-y-4">
            <div class="flex items-center">
                <input wire:model="enableAutoRelease" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label class="ml-2 text-sm text-gray-700">Enable Auto Stock Release</label>
            </div>
            <div class="flex items-center">
                <input wire:model="enableAutoExpiry" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label class="ml-2 text-sm text-gray-700">Enable Auto Stock Expiry</label>
            </div>
            <div class="flex items-center">
                <input wire:model="enableWhatsAppLogs" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label class="ml-2 text-sm text-gray-700">Enable WhatsApp Logs</label>
            </div>
        </div>
        
        <div class="mt-6">
            <button wire:click="saveSystemSettings" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Save System Settings</button>
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">System Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">PHP Version</label>
                <input type="text" value="{{ $systemInfo['php_version'] }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Laravel Version</label>
                <input type="text" value="{{ $systemInfo['laravel_version'] }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Database</label>
                <input type="text" value="{{ $systemInfo['database'] }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">App URL</label>
                <input type="text" value="{{ $systemInfo['app_url'] }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Storage Path</label>
            <input type="text" value="{{ $systemInfo['storage_path'] }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
        </div>
    </div>

    <!-- Maintenance Actions -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button wire:click="clearCache" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Clear Cache
            </button>
            <button wire:click="backupDatabase" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Backup Database
            </button>
            <button wire:click="resetToDefaults" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reset to Defaults
            </button>
        </div>
    </div>
</div> 