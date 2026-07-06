<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Order;
use App\Services\PDFService;
use Illuminate\Support\Facades\Auth;

class Orders extends Component
{
    public function render()
    {
        $user = Auth::user();
        $orders = Order::query()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->phone) {
                    $q->orWhere('customer_mobile', $user->phone);
                }
            })
            ->with(['items', 'payment', 'logs', 'user'])
            ->orderByDesc('created_at')
            ->get();
        return view('livewire.user.orders', compact('orders'));
    }

    public function downloadPDF()
    {
        $user = Auth::user();
        $orders = Order::query()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->phone) {
                    $q->orWhere('customer_mobile', $user->phone);
                }
            })
            ->with(['items', 'payment', 'logs', 'user'])
            ->orderByDesc('created_at')
            ->get();

        $pdfService = new PDFService();
        return $pdfService->downloadUserOrders($orders, $user);
    }
}
