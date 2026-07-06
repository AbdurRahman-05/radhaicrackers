<div>
<div class="p-6 bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">User Management</h2>
            <p class="text-gray-600">Manage customer accounts and permissions</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 mt-4 sm:mt-0">
            <button wire:click="exportUsers" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </button>
            <button wire:click="showAddUserForm" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New User
            </button>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-blue-600">{{ $totalUsers }}</div>
            <div class="text-sm text-blue-600">Total Users</div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-green-600">{{ $activeUsers }}</div>
            <div class="text-sm text-green-600">Active Users</div>
        </div>
        <div class="bg-red-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-red-600">{{ $inactiveUsers }}</div>
            <div class="text-sm text-red-600">Inactive Users</div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
            <div class="text-2xl font-bold text-purple-600">{{ $usersWithOrders }}</div>
            <div class="text-sm text-purple-600">With Orders</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input wire:model.live="search" type="text" placeholder="Name, phone, email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                <select wire:model.live="selected_year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Years</option>
                    @foreach($available_years as $yr)
                        <option value="{{ $yr }}">{{ $yr }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Users</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="admin">Admin</option>
                    <option value="regular">Regular</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input wire:model.live="date_from" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input wire:model.live="date_to" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div class="mt-4">
            <button wire:click="clearFilters" class="text-gray-600 hover:text-gray-800 text-sm">Clear Filters</button>
        </div>
    </div>

    <!-- Bulk Actions -->
    @if(!empty($selectedUsers))
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="text-sm text-yellow-800">
                {{ count($selectedUsers) }} user(s) selected
            </div>
            <div class="flex gap-2">
                <button wire:click="bulkUnblock" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                    Unblock Selected
                </button>
                <button wire:click="bulkBlock" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                    Block Selected
                </button>
                <button wire:click="$set('selectedUsers', [])" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">
                    Clear Selection
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last OTP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $user->display_name }}</div>
                        @if($user->is_admin)
                        <div class="text-xs text-red-600 font-medium">Admin</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->phone }}</div>
                        @if($user->email)
                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($user->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($user->last_otp_verified_at)
                        {{ $user->last_otp_verified_at->format('d/m/Y H:i') }}
                        @else
                        Never
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->orders_count }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.users.details', $user->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            <button wire:click="showEditUserForm({{ $user->id }})" class="text-yellow-600 hover:text-yellow-900">Edit</button>
                            <button wire:click="confirmDeleteUser({{ $user->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                            @if(!$user->is_admin)
                                @if($user->is_active)
                                    <button wire:click="toggleUserStatus({{ $user->id }})" class="text-red-600 hover:text-red-900">Deactivate</button>
                                @else
                                    <button wire:click="toggleUserStatus({{ $user->id }})" class="text-green-600 hover:text-green-900">Activate</button>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add New User Modal (always in DOM, toggled by $showAddUserModal) -->
<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: {{ $showAddUserModal ? 'flex' : 'none' }};">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
        <h2 class="text-xl font-bold mb-4">Add New User</h2>
        <form wire:submit.prevent="addUser">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700">Name</label>
                    <input type="text" wire:model.defer="name" class="w-full border rounded px-3 py-2" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Email</label>
                    <input type="email" wire:model.defer="email" class="w-full border rounded px-3 py-2" required>
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Phone</label>
                    <input type="text" wire:model.defer="phone" class="w-full border rounded px-3 py-2" required>
                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">OTP</label>
                    <input type="text" wire:model.defer="otp" class="w-full border rounded px-3 py-2">
                    @error('otp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">OTP Expires At</label>
                    <input type="datetime-local" wire:model.defer="otp_expires_at" class="w-full border rounded px-3 py-2">
                    @error('otp_expires_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Last OTP Verified At</label>
                    <input type="datetime-local" wire:model.defer="last_otp_verified_at" class="w-full border rounded px-3 py-2">
                    @error('last_otp_verified_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Role</label>
                    <select wire:model.defer="role" class="w-full border rounded px-3 py-2">
                        @foreach($allRoles as $roleOption)
                            <option value="{{ $roleOption }}">{{ ucfirst($roleOption) }}</option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Permissions (optional)</label>
                    <select wire:model.defer="permissions" multiple class="w-full border rounded px-3 py-2">
                        @foreach($allPermissions as $perm)
                            <option value="{{ $perm }}">{{ ucfirst($perm) }}</option>
                        @endforeach
                    </select>
                    @error('permissions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Is Active</label>
                    <select wire:model.defer="is_active" class="w-full border rounded px-3 py-2">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('is_active') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Email Verified At</label>
                    <input type="datetime-local" wire:model.defer="email_verified_at" class="w-full border rounded px-3 py-2">
                    @error('email_verified_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Password</label>
                    <input type="password" wire:model.defer="password" class="w-full border rounded px-3 py-2" required>
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Remember Token</label>
                    <input type="text" wire:model.defer="remember_token" class="w-full border rounded px-3 py-2">
                    @error('remember_token') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" wire:click="$set('showAddUserModal', false)" class="mr-2 px-4 py-2 rounded border">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add User</button>
            </div>
        </form>
        <button type="button" wire:click="$set('showAddUserModal', false)" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
    </div>
</div>
<!-- Edit User Modal -->
<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: {{ $showEditUserModal ? 'flex' : 'none' }};">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
        <h2 class="text-xl font-bold mb-4">Edit User</h2>
        <form wire:submit.prevent="updateUser">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Same fields as Add User Modal -->
                <div>
                    <label class="block text-gray-700">Name</label>
                    <input type="text" wire:model.defer="name" class="w-full border rounded px-3 py-2" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Email</label>
                    <input type="email" wire:model.defer="email" class="w-full border rounded px-3 py-2" required>
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Phone</label>
                    <input type="text" wire:model.defer="phone" class="w-full border rounded px-3 py-2" required>
                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">OTP</label>
                    <input type="text" wire:model.defer="otp" class="w-full border rounded px-3 py-2">
                    @error('otp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">OTP Expires At</label>
                    <input type="datetime-local" wire:model.defer="otp_expires_at" class="w-full border rounded px-3 py-2">
                    @error('otp_expires_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Last OTP Verified At</label>
                    <input type="datetime-local" wire:model.defer="last_otp_verified_at" class="w-full border rounded px-3 py-2">
                    @error('last_otp_verified_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Role</label>
                    <select wire:model.defer="role" class="w-full border rounded px-3 py-2">
                        @foreach($allRoles as $roleOption)
                            <option value="{{ $roleOption }}">{{ ucfirst($roleOption) }}</option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Permissions (optional)</label>
                    <select wire:model.defer="permissions" multiple class="w-full border rounded px-3 py-2">
                        @foreach($allPermissions as $perm)
                            <option value="{{ $perm }}">{{ ucfirst($perm) }}</option>
                        @endforeach
                    </select>
                    @error('permissions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Is Active</label>
                    <select wire:model.defer="is_active" class="w-full border rounded px-3 py-2">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('is_active') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Email Verified At</label>
                    <input type="datetime-local" wire:model.defer="email_verified_at" class="w-full border rounded px-3 py-2">
                    @error('email_verified_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Password (leave blank to keep current)</label>
                    <input type="password" wire:model.defer="password" class="w-full border rounded px-3 py-2">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700">Remember Token</label>
                    <input type="text" wire:model.defer="remember_token" class="w-full border rounded px-3 py-2">
                    @error('remember_token') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" wire:click="$set('showEditUserModal', false)" class="mr-2 px-4 py-2 rounded border">Cancel</button>
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Update User</button>
            </div>
        </form>
        <button type="button" wire:click="$set('showEditUserModal', false)" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
    </div>
</div>
<!-- Delete User Confirmation Modal -->
<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: {{ $showDeleteUserModal ? 'flex' : 'none' }};">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-bold mb-4">Delete User</h2>
        <p>Are you sure you want to delete this user? This action cannot be undone.</p>
        <div class="flex justify-end mt-6">
            <button type="button" wire:click="$set('showDeleteUserModal', false)" class="mr-2 px-4 py-2 rounded border">Cancel</button>
            <button type="button" wire:click="deleteUser" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
        </div>
        <button type="button" wire:click="$set('showDeleteUserModal', false)" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
    </div>
</div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('download-csv', () => {
        // Redirect to the export route to trigger download
        window.location.href = '{{ route("admin.export.users") }}';
    });
});
</script> 