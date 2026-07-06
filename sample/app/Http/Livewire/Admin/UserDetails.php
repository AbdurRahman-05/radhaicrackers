<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Order;

class UserDetails extends Component
{
    public $userId;
    public $user;
    public $orders;

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($userId);
        $this->orders = Order::where('user_id', $userId)->latest()->get();
    }

    public function render()
    {
        return view('livewire.admin.user-details');
    }
} 