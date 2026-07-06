<div class="p-6 bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Coupon Management</h2>
            <p class="text-gray-600">Manage all discount coupons for your shop</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 mt-4 sm:mt-0">
            <a href="{{ route('admin.coupons.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New Coupon
            </a>
            @livewire('admin.coupons.bulk-upload')
            @livewire('admin.coupons.export-csv')
        </div>
    </div>

    <!-- Uploaded CSV Files -->
    @if($csvUploads->count())
    <div class="mb-6">
        <div class="bg-gray-50 p-4 rounded-lg shadow flex flex-col gap-2">
            <div class="font-semibold text-gray-700 mb-2">Uploaded Coupon CSV Files</div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded At</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Download</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($csvUploads as $upload)
                        <tr>
                            <td class="px-4 py-2">{{ $upload->original_name }}</td>
                            <td class="px-4 py-2">{{ $upload->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ asset('storage/app/' . $upload->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Download</a>
                            </td>
                            <td class="px-4 py-2">
                                <button wire:click="previewCsv({{ $upload->id }})" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">Preview</button>
                            </td>
                            <td class="px-4 py-2">
                                <button wire:click="deleteCsv({{ $upload->id }})" onclick="return confirm('Delete this CSV file?')" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- CSV Preview Modal -->
    @if($showPreview)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-6 relative">
            <button wire:click="closePreview" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
            <h3 class="text-lg font-semibold mb-4">CSV Preview (first 5 rows)</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach($previewHeaders as $header)
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($previewRows as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $cell }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button wire:click="closePreview" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-blue-600">{{ $coupons->total() }}</div>
            <div class="text-sm text-blue-600">Total Coupons</div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-green-600">{{ $coupons->where('is_active', true)->count() }}</div>
            <div class="text-sm text-green-600">Active Coupons</div>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-yellow-600">{{ $coupons->where('expires_at', '<=', now()->addDays(7))->count() }}</div>
            <div class="text-sm text-yellow-600">Expiring Soon</div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-purple-600">{{ $coupons->sum('used_count') }}</div>
            <div class="text-sm text-purple-600">Total Usage</div>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
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
    <div class="mt-6">
        {{ $coupons->links() }}
    </div>
    @endif
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