<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderDetails extends Component
{
    public $orderId;
    public $order;

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $user = Auth::user();
        $this->order = Order::where('id', $orderId)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->phone) {
                    $q->orWhere('customer_mobile', $user->phone);
                }
            })
            ->with(['items', 'logs', 'payment'])
            ->first();
    }

    public function render()
    {
        return view('livewire.user.order-details', [
            'order' => $this->order
        ]);
    }
}
