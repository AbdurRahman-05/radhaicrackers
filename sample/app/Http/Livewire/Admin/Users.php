<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';
    public $date_from = '';
    public $date_to = '';
    public $selectedUsers = [];
    public $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'status_filter' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

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

    private function getFilteredUsers()
    {
        $query = User::withCount('orders')
            ->orderBy('created_at', 'desc');

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
        
        return view('livewire.admin.users', [
            'users' => $users,
            'totalUsers' => User::where('is_admin', false)->count(),
            'activeUsers' => User::where('is_admin', false)->where('is_active', true)->count(),
            'inactiveUsers' => User::where('is_admin', false)->where('is_active', false)->count(),
            'adminUsers' => User::where('is_admin', true)->count(),
            'usersWithOrders' => User::where('is_admin', false)->whereHas('orders')->count(),
        ]);
    }
} 