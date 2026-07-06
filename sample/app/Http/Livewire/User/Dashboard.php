<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\Order;

class Dashboard extends Component
{
    public $orders = [];

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = Order::with(['items', 'payment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.user.dashboard')
            ->layout('layouts.app');
    }
} 