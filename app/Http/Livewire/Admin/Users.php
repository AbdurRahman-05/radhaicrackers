<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Users extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';
    public $date_from = '';
    public $date_to = '';
    public $selectedUsers = [];
    public $selectAll = false;
    public $showAddUserModal = false;
    public $name, $email, $phone, $otp, $otp_expires_at, $last_otp_verified_at, $is_active = 1, $email_verified_at, $password, $remember_token;
    public $role = 'user';
    public $allRoles = [];
    public $permissions = [];
    public $allPermissions = [];
    public $editUserId = null;
    public $showEditUserModal = false;
    public $showDeleteUserModal = false;
    public $selected_year = '';
    public $available_years = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'status_filter' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'selected_year' => ['except' => ''],
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:20|unique:users,phone',
        'otp' => 'nullable|string|max:10',
        'otp_expires_at' => 'nullable|date',
        'last_otp_verified_at' => 'nullable|date',
        'role' => 'required|exists:roles,name',
        'permissions' => 'array',
        'permissions.*' => 'exists:permissions,name',
        'is_active' => 'required|boolean',
        'email_verified_at' => 'nullable|date',
        'password' => 'required|string|min:6',
        'remember_token' => 'nullable|string|max:100',
    ];

    public function mount()
    {
        $this->allRoles = Role::pluck('name');
        $this->allPermissions = Permission::pluck('name');

        if (!$this->selected_year) {
            $this->selected_year = date('Y');
        }

        // Get unique years from users
        $userYears = User::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (!in_array(date('Y'), $userYears)) {
            array_unshift($userYears, (int)date('Y'));
        }

        $this->available_years = $userYears;
    }

    public function updatedSelectedYear()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'status_filter', 'date_from', 'date_to']);
        $this->selected_year = date('Y');
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = $this->getFilteredUsers()->pluck('id')->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function toggleUserStatus($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        // Don't allow deactivating admin users
        if ($user->is_admin) {
            session()->flash('error', 'Cannot deactivate admin users.');
            return;
        }

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "User {$status} successfully!");
    }

    public function blockUser($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        // Don't allow blocking admin users
        if ($user->is_admin) {
            session()->flash('error', 'Cannot block admin users.');
            return;
        }

        $user->update(['is_active' => false]);
        session()->flash('success', 'User blocked successfully!');
    }

    public function unblockUser($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        $user->update(['is_active' => true]);
        session()->flash('success', 'User unblocked successfully!');
    }

    public function bulkBlock()
    {
        if (empty($this->selectedUsers)) {
            session()->flash('error', 'Please select users to block.');
            return;
        }

        // Don't allow blocking admin users
        $adminUsers = User::whereIn('id', $this->selectedUsers)->where('is_admin', true)->count();
        if ($adminUsers > 0) {
            session()->flash('error', 'Cannot block admin users.');
            return;
        }

        User::whereIn('id', $this->selectedUsers)->update(['is_active' => false]);
        $this->selectedUsers = [];
        session()->flash('success', 'Selected users blocked successfully!');
    }

    public function bulkUnblock()
    {
        if (empty($this->selectedUsers)) {
            session()->flash('error', 'Please select users to unblock.');
            return;
        }

        User::whereIn('id', $this->selectedUsers)->update(['is_active' => true]);
        $this->selectedUsers = [];
        session()->flash('success', 'Selected users unblocked successfully!');
    }

    public function exportUsers()
    {
        $users = $this->getFilteredUsers();
        
        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $csvData = [];
        
        // CSV headers
        $csvData[] = [
            'User ID', 'Name', 'Phone', 'Email', 'Status', 'Is Admin', 
            'Last OTP Verified', 'Total Orders', 'Created Date'
        ];

        foreach ($users as $user) {
            $csvData[] = [
                $user->id,
                $user->name,
                $user->phone,
                $user->email ?? 'N/A',
                $user->is_active ? 'Active' : 'Inactive',
                $user->is_admin ? 'Yes' : 'No',
                $user->last_otp_verified_at ? $user->last_otp_verified_at->format('Y-m-d H:i:s') : 'Never',
                $user->orders_count,
                $user->created_at->format('Y-m-d H:i:s')
            ];
        }

        // Convert to CSV string
        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }

        // Store CSV content in session for download
        session(['export_csv_content' => $csvContent, 'export_csv_filename' => $filename]);
        
        // Dispatch download event
        $this->dispatch('download-csv');
        
        session()->flash('success', 'Export ready! Download will start automatically.');
    }

    public function showAddUserForm()
    {
        $this->reset(['name', 'email', 'phone', 'otp', 'otp_expires_at', 'last_otp_verified_at', 'is_active', 'email_verified_at', 'password', 'remember_token', 'role', 'permissions']);
        $this->is_active = 1;
        $this->role = 'user';
        $this->permissions = [];
        $this->showAddUserModal = true;
    }

    public function addUser()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'otp' => $this->otp,
            'otp_expires_at' => $this->otp_expires_at,
            'last_otp_verified_at' => $this->last_otp_verified_at,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at,
            'password' => bcrypt($this->password),
            'remember_token' => $this->remember_token,
        ]);

        $user->assignRole($this->role);
        if (!empty($this->permissions)) {
            $user->givePermissionTo($this->permissions);
        }

        $this->showAddUserModal = false;
        $this->reset(['name', 'email', 'phone', 'otp', 'otp_expires_at', 'last_otp_verified_at', 'is_active', 'email_verified_at', 'password', 'remember_token', 'role', 'permissions']);
        session()->flash('success', 'User added successfully!');
        $this->resetPage();
    }

    public function showEditUserForm($userId)
    {
        $user = User::findOrFail($userId);
        $this->editUserId = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->otp = $user->otp;
        $this->otp_expires_at = $user->otp_expires_at;
        $this->last_otp_verified_at = $user->last_otp_verified_at;
        $this->is_active = $user->is_active;
        $this->email_verified_at = $user->email_verified_at;
        $this->password = '';
        $this->remember_token = $user->remember_token;
        $this->role = $user->roles->pluck('name')->first();
        $this->permissions = $user->getPermissionNames()->toArray();
        $this->showEditUserModal = true;
    }

    public function updateUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->editUserId,
            'phone' => 'required|string|max:20|unique:users,phone,' . $this->editUserId,
            'otp' => 'nullable|string|max:10',
            'otp_expires_at' => 'nullable|date',
            'last_otp_verified_at' => 'nullable|date',
            'role' => 'required|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
            'is_active' => 'required|boolean',
            'email_verified_at' => 'nullable|date',
            'password' => 'nullable|string|min:6',
            'remember_token' => 'nullable|string|max:100',
        ]);

        $user = User::findOrFail($this->editUserId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'otp' => $this->otp,
            'otp_expires_at' => $this->otp_expires_at,
            'last_otp_verified_at' => $this->last_otp_verified_at,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at,
            'remember_token' => $this->remember_token,
            'password' => $this->password ? bcrypt($this->password) : $user->password,
        ]);
        $user->syncRoles([$this->role]);
        $user->syncPermissions($this->permissions);

        $this->showEditUserModal = false;
        session()->flash('success', 'User updated successfully!');
        $this->resetPage();
    }

    public function confirmDeleteUser($userId)
    {
        $this->editUserId = $userId;
        $this->showDeleteUserModal = true;
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->editUserId);
        $user->delete();
        $this->editUserId = null;
        $this->showDeleteUserModal = false;
        session()->flash('success', 'User deleted successfully!');
        $this->resetPage();
    }

    private function getFilteredUsers()
    {
        $query = User::withCount('orders')
            ->orderBy('created_at', 'desc');

        if ($this->selected_year) {
            $query->whereYear('created_at', $this->selected_year);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status_filter) {
            if ($this->status_filter === 'active') {
                $query->where('is_active', true);
            } elseif ($this->status_filter === 'inactive') {
                $query->where('is_active', false);
            } elseif ($this->status_filter === 'admin') {
                $query->where('is_admin', true);
            } elseif ($this->status_filter === 'regular') {
                $query->where('is_admin', false);
            }
        }

        if ($this->date_from) {
            $query->whereDate('created_at', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $query->whereDate('created_at', '<=', $this->date_to);
        }

        return $query->get();
    }

    public function render()
    {
        $users = $this->getFilteredUsers();
        
        $statsQuery = User::where('is_admin', false);
        if ($this->selected_year) {
            $statsQuery->whereYear('created_at', $this->selected_year);
        }
        
        return view('livewire.admin.users', [
            'users' => $users,
            'totalUsers' => (clone $statsQuery)->count(),
            'activeUsers' => (clone $statsQuery)->where('is_active', true)->count(),
            'inactiveUsers' => (clone $statsQuery)->where('is_active', false)->count(),
            'adminUsers' => User::where('is_admin', true)->count(),
            'usersWithOrders' => (clone $statsQuery)->whereHas('orders')->count(),
        ]);
    }
} 