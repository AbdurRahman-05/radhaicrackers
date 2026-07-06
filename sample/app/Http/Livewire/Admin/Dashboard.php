<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Stock;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $stats = [];
    public $recentOrders = [];
    public $recentActivity = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentOrders();
        $this->loadRecentActivity();
    }

    public function loadStats()
    {
        $today = Carbon::today();
        
        $this->stats = [
            'total_users' => User::where('is_admin', false)->count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'stock_items' => Stock::where('is_active', true)->count(),
            
            // Today's metrics
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_payments' => Payment::whereDate('created_at', $today)->count(),
            'today_users' => User::where('is_admin', false)->whereDate('created_at', $today)->count(),
            
            // Order status counts
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'dispatched_orders' => Order::where('status', 'dispatched')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            
            // Payment status counts
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'verified_payments' => Payment::where('status', 'verified')->count(),
            
            // Stock metrics
            'active_stocks' => Stock::where('is_active', true)->count(),
            'low_stock_items' => Stock::where('is_active', true)->where('quantity', '<', 10)->count(),
        ];
    }

    public function loadRecentOrders()
    {
        $this->recentOrders = Order::with(['user', 'payment'])
            ->latest()
            ->take(5)
            ->get()
            ->toArray();
    }

    public function loadRecentActivity()
    {
        // Get recent order status changes, payments, and stock activities
        $this->recentActivity = collect();
        
        // Recent orders
        $recentOrders = Order::with('user')->latest()->take(3)->get();
        foreach ($recentOrders as $order) {
            $this->recentActivity->push([
                'type' => 'order',
                'message' => "New order #{$order->id} from {$order->user->name}",
                'time' => $order->created_at,
                'icon' => 'shopping-cart',
                'color' => 'blue'
            ]);
        }
        
        // Recent payments
        $recentPayments = Payment::with('order.user')->latest()->take(3)->get();
        foreach ($recentPayments as $payment) {
            $this->recentActivity->push([
                'type' => 'payment',
                'message' => "Payment ₹{$payment->amount} for order #{$payment->order_id}",
                'time' => $payment->created_at,
                'icon' => 'credit-card',
                'color' => 'green'
            ]);
        }
        
        // Recent user registrations
        $recentUsers = User::where('is_admin', false)->latest()->take(3)->get();
        foreach ($recentUsers as $user) {
            $this->recentActivity->push([
                'type' => 'user',
                'message' => "New user registered: {$user->name}",
                'time' => $user->created_at,
                'icon' => 'user',
                'color' => 'purple'
            ]);
        }
        
        // Sort by time and take latest 5
        $this->recentActivity = $this->recentActivity->sortByDesc('time')->take(5);
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
} 