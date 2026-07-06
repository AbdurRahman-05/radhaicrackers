<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderLog;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';
    public $payment_filter = '';
    public $date_from = '';
    public $date_to = '';
    public $selectedOrders = [];

    // Modal properties
    public $showEditModal = false;
    public $editingOrderId = null;
    public $editStatus = '';
    public $editPaymentStatus = '';
    public $editNotes = '';

    public $editingReceiveAmountId = null;
    public $receiveAmountInput = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status_filter' => ['except' => ''],
        'payment_filter' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

    protected $rules = [
        'editStatus' => 'required|in:pending,confirmed,dispatched,completed,cancelled',
        'editPaymentStatus' => 'required|in:pending,paid,failed',
        'editNotes' => 'nullable|string|max:500'
    ];

    protected $messages = [
        'editStatus.required' => 'Order status is required.',
        'editStatus.in' => 'Please select a valid order status.',
        'editPaymentStatus.required' => 'Payment status is required.',
        'editPaymentStatus.in' => 'Please select a valid payment status.',
        'editNotes.max' => 'Notes cannot exceed 500 characters.'
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPaymentFilter()
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
        $this->reset(['search', 'status_filter', 'payment_filter', 'date_from', 'date_to']);
        $this->resetPage();
    }

    public function openEditModal($orderId)
    {
        \Log::info('openEditModal called with orderId: ' . $orderId);
        
        $order = Order::find($orderId);
        if ($order) {
            $this->editingOrderId = $orderId;
            $this->editStatus = $order->status ?? 'pending';
            $this->editPaymentStatus = $order->payment_status ?? 'pending';
            $this->editNotes = $order->notes ?? '';
            $this->showEditModal = true;
            
            \Log::info('Edit modal opened for order: ' . $orderId);
            \Log::info('Current values - Status: ' . $this->editStatus . ', Payment: ' . $this->editPaymentStatus . ', Notes: ' . $this->editNotes);
        } else {
            \Log::error('Order not found with ID: ' . $orderId);
            session()->flash('error', 'Order not found.');
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['editingOrderId', 'editStatus', 'editPaymentStatus', 'editNotes']);
    }

    public function saveOrder()
    {
        try {
            $this->validate();

            $order = Order::find($this->editingOrderId);
            if (!$order) {
                session()->flash('error', 'Order not found.');
                return;
            }

            // Store old values for logging
            $oldStatus = $order->status;
            $oldPaymentStatus = $order->payment_status;
            $oldNotes = $order->notes;

            // Update the order
            $updateData = [
                'status' => $this->editStatus,
                'payment_status' => $this->editPaymentStatus,
                'notes' => $this->editNotes
            ];

            $order->update($updateData);

            // Log status changes
            if ($oldStatus !== $this->editStatus) {
                OrderLog::create([
                    'order_id' => $order->id,
                    'status' => $this->editStatus,
                    'previous_status' => $oldStatus,
                    'changed_by' => auth()->id(),
                    'notes' => "Status changed from {$oldStatus} to {$this->editStatus}",
                    'payment_status' => null,
                ]);
            }

            if ($oldPaymentStatus !== $this->editPaymentStatus) {
                OrderLog::create([
                    'order_id' => $order->id,
                    'status' => 'payment_status_changed',
                    'previous_status' => $oldPaymentStatus,
                    'changed_by' => auth()->id(),
                    'notes' => "Payment status changed from {$oldPaymentStatus} to {$this->editPaymentStatus}",
                    'payment_status' => $this->editPaymentStatus,
                ]);
            }

            if ($oldNotes !== $this->editNotes) {
                OrderLog::create([
                    'order_id' => $order->id,
                    'status' => 'notes_updated',
                    'previous_status' => 'notes_updated',
                    'changed_by' => auth()->id(),
                    'notes' => "Notes updated: " . ($this->editNotes ?: 'Notes cleared'),
                    'payment_status' => null,
                ]);
            }

            $this->closeEditModal();
            session()->flash('success', 'Order updated successfully!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    public function editReceiveAmount($orderId, $currentAmount)
    {
        $this->editingReceiveAmountId = $orderId;
        $this->receiveAmountInput = $currentAmount;
    }

    public function saveReceiveAmount($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->receive_amount = $this->receiveAmountInput;
            $order->save();
            session()->flash('success', 'Receive amount updated.');
        }
        $this->editingReceiveAmountId = null;
        $this->receiveAmountInput = null;
    }

    public function cancelEditReceiveAmount()
    {
        $this->editingReceiveAmountId = null;
        $this->receiveAmountInput = null;
    }

    public function exportOrders()
    {
        $orders = $this->getFilteredOrders();
        
        $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';
        
        $csvData = [];
        
        // CSV headers
        $csvData[] = [
            'Order ID', 'Customer', 'Phone', 'Status', 'Payment Status', 
            'Total Amount', 'Receive Amount', 'Created Date', 'Items',
            'Name', 'Mobile', 'Email', 'State', 'District', 'City', 'Delivery Point', 'Pin Code', 'Coupon', 'Verify Code'
        ];

        foreach ($orders as $order) {
            $items = $order->items->map(function($item) {
                return $item->product_name . ' (x' . $item->quantity . ')';
            })->implode(', ');

            $csvData[] = [
                $order->id,
                $order->user->name ?? $order->customer_name ?? 'Guest',
                $order->user->phone ?? $order->customer_mobile ?? '-',
                $order->status,
                $order->payment_status,
                number_format($order->total_amount, 2),
                $order->receive_amount !== null ? number_format($order->receive_amount, 2) : '',
                $order->created_at->format('Y-m-d H:i:s'),
                $items,
                $order->customer_name,
                $order->customer_mobile,
                $order->customer_email,
                $order->customer_state,
                $order->customer_district,
                $order->customer_city,
                $order->delivery_point,
                $order->pin_code,
                $order->coupon_code,
                $order->verify_code,
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

    public function sendWhatsAppSummary($orderId)
    {
        $order = Order::with(['user', 'items'])->find($orderId);
        
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $items = $order->items->map(function($item) {
            return "• {$item->product_name} - Qty: {$item->quantity} - ₹" . number_format($item->price, 2);
        })->implode("\n");

        $message = "🛒 *Order Summary*\n\n";
        $message .= "Order ID: #{$order->id}\n";
        $message .= "Customer: {$order->user->name}\n";
        $message .= "Phone: {$order->user->phone}\n";
        $message .= "Status: {$order->status}\n";
        $message .= "Payment: {$order->payment_status}\n\n";
        $message .= "*Items:*\n{$items}\n\n";
        $message .= "Total Amount: ₹" . number_format($order->total_amount, 2) . "\n";
        $message .= "Order Date: " . $order->created_at->format('d/m/Y H:i');

        $whatsappLink = "https://wa.me/{$order->user->phone}?text=" . urlencode($message);
        
        session()->flash('whatsapp_link', $whatsappLink);
        session()->flash('success', 'WhatsApp link generated successfully!');
    }

    private function getFilteredOrders()
    {
        $query = Order::with(['user', 'items'])
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            })->orWhere('id', 'like', '%' . $this->search . '%');
        }

        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        if ($this->payment_filter) {
            $query->where('payment_status', $this->payment_filter);
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
        $orders = $this->getFilteredOrders();
        
        \Log::info('Orders component rendering', [
            'showEditModal' => $this->showEditModal,
            'editingOrderId' => $this->editingOrderId,
            'ordersCount' => $orders->count(),
            'editStatus' => $this->editStatus,
            'editPaymentStatus' => $this->editPaymentStatus,
            'editNotes' => $this->editNotes
        ]);
        
        return view('livewire.admin.orders', [
            'orders' => $orders,
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'confirmedOrders' => Order::where('status', 'confirmed')->count(),
            'dispatchedOrders' => Order::where('status', 'dispatched')->count(),
            'completedOrders' => Order::where('status', 'completed')->count(),
        ]);
    }
} 