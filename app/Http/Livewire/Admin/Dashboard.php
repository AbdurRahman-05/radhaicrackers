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
    public $selected_year = '';
    public $available_years = [];

    protected $queryString = [
        'selected_year' => ['except' => ''],
    ];

    public function mount()
    {
        if (!$this->selected_year) {
            $this->selected_year = date('Y');
        }

        // Get unique years from orders
        $orderYears = Order::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (!in_array(date('Y'), $orderYears)) {
            array_unshift($orderYears, (int)date('Y'));
        }

        $this->available_years = $orderYears;

        $this->loadStats();
        $this->loadRecentOrders();
        $this->loadRecentActivity();
    }

    public function updatedSelectedYear()
    {
        $this->loadStats();
        $this->loadRecentOrders();
        $this->loadRecentActivity();
    }

    public function loadStats()
    {
        $today = Carbon::today();
        
        $userQuery = User::where('is_admin', false);
        $orderQuery = Order::query();
        $paymentQuery = Payment::query();

        if ($this->selected_year) {
            $userQuery->whereYear('created_at', $this->selected_year);
            $orderQuery->whereYear('created_at', $this->selected_year);
            $paymentQuery->whereYear('created_at', $this->selected_year);
        }

        $verifiedOrdersQuery = (clone $orderQuery)->whereIn('status', ['confirmed', 'dispatched', 'completed'])->where('payment_status', 'paid');
        $verifiedPaymentsCount = $verifiedOrdersQuery->count();
        $verifiedPaymentsAmount = $verifiedOrdersQuery->get()->sum(function($order) {
            return $order->receive_amount !== null ? $order->receive_amount : $order->total_amount;
        });

        // Debug: log the values to help diagnose issues
        info('Verified Payments Count: ' . $verifiedPaymentsCount);
        info('Verified Payments Amount: ' . $verifiedPaymentsAmount);

        $this->stats = [
            'total_users' => $userQuery->count(),
            'total_orders' => (clone $orderQuery)->count(),
            'total_revenue' => (clone $orderQuery)->where('status', 'completed')->sum('total_amount'),
            'stock_items' => Stock::where('is_active', true)->count(),

            // Today's metrics (always for today)
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_payments' => Payment::whereDate('created_at', $today)->count(),
            'today_users' => User::where('is_admin', false)->whereDate('created_at', $today)->count(),

            // Order status counts
            'pending_orders' => (clone $orderQuery)->where('status', 'pending')->count(),
            'confirmed_orders' => (clone $orderQuery)->where('status', 'confirmed')->count(),
            'dispatched_orders' => (clone $orderQuery)->where('status', 'dispatched')->count(),
            'completed_orders' => (clone $orderQuery)->where('status', 'completed')->count(),

            // Verified payments (from orders)
            'verified_payments' => $verifiedPaymentsCount,
            'verified_payments_amount' => $verifiedPaymentsAmount,

            // Payment status counts (legacy, if needed)
            'pending_payments' => (clone $paymentQuery)->where('status', 'pending')->count(),

            // Stock metrics
            'active_stocks' => Stock::where('is_active', true)->count(),
            'low_stock_items' => Stock::where('is_active', true)->where('quantity', '<', 10)->count(),
        ];
    }

    public function loadRecentOrders()
    {
        $query = Order::with(['user', 'payment']);
        if ($this->selected_year) {
            $query->whereYear('created_at', $this->selected_year);
        }
        $this->recentOrders = $query->latest()
            ->take(5)
            ->get()
            ->toArray();
    }

    public function loadRecentActivity()
    {
        // Get recent order status changes, payments, and stock activities
        $this->recentActivity = collect();
        
        $orderQuery = Order::with('user');
        $paymentQuery = Payment::with('order.user');
        $userQuery = User::where('is_admin', false);

        if ($this->selected_year) {
            $orderQuery->whereYear('created_at', $this->selected_year);
            $paymentQuery->whereYear('created_at', $this->selected_year);
            $userQuery->whereYear('created_at', $this->selected_year);
        }

        // Recent orders
        $recentOrders = $orderQuery->latest()->take(3)->get();
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
        $recentPayments = $paymentQuery->latest()->take(3)->get();
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
        $recentUsers = $userQuery->latest()->take(3)->get();
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