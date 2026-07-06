<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderLog;
use App\Services\PDFService;
use Livewire\Component;

class OrderDetails extends Component
{
    public $orderId;
    public $order;
    
    // WhatsApp properties
    public $showWhatsAppLink = false;
    public $whatsappLink = '';

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->loadOrder();
    }

    public function loadOrder()
    {
        $this->order = Order::with(['user', 'items', 'logs'])->find($this->orderId);
        
        if (!$this->order) {
            session()->flash('error', 'Order not found.');
            return redirect()->route('admin.orders');
        }
    }

    public function generatePDF()
    {
        $pdfService = new PDFService();
        $pdf = $pdfService->generateOrderConfirmation($this->order);
        
        $filename = "order_{$this->order->id}_confirmation.pdf";
        
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function sendWhatsAppSummary()
    {
        $items = $this->order->items->map(function($item) {
            return "• {$item->product_name} - Qty: {$item->quantity} - ₹" . number_format($item->price, 2);
        })->implode("\n");

        $message = "🛒 *Order Summary*\n\n";
        $message .= "Order ID: #{$this->order->id}\n";
        $message .= "Customer: {$this->order->user->name}\n";
        $message .= "Phone: {$this->order->user->phone}\n";
        $message .= "Status: {$this->order->status}\n";
        $message .= "Payment: {$this->order->payment_status}\n\n";
        $message .= "*Items:*\n{$items}\n\n";
        $message .= "Total Amount: ₹" . number_format($this->order->total_amount, 2) . "\n";
        $message .= "Order Date: " . $this->order->created_at->format('d/m/Y H:i');

        $this->whatsappLink = "https://wa.me/{$this->order->user->phone}?text=" . urlencode($message);
        $this->showWhatsAppLink = true;
    }

    public function sendDispatchNotification()
    {
        $message = "🚚 *Order Dispatched*\n\n";
        $message .= "Dear {$this->order->user->name},\n\n";
        $message .= "Your order #{$this->order->id} has been dispatched and is on its way!\n\n";
        $message .= "We'll keep you updated on the delivery status.\n\n";
        $message .= "Thank you for choosing our service!";

        $this->whatsappLink = "https://wa.me/{$this->order->user->phone}?text=" . urlencode($message);
        $this->showWhatsAppLink = true;
    }

    public function sendPaymentReminder()
    {
        $message = "💰 *Payment Reminder*\n\n";
        $message .= "Dear {$this->order->user->name},\n\n";
        $message .= "This is a friendly reminder that payment for order #{$this->order->id} is pending.\n\n";
        $message .= "Total Amount: ₹" . number_format($this->order->total_amount, 2) . "\n\n";
        $message .= "Please complete the payment to confirm your order.\n\n";
        $message .= "Thank you!";

        $this->whatsappLink = "https://wa.me/{$this->order->user->phone}?text=" . urlencode($message);
        $this->showWhatsAppLink = true;
    }

    public function render()
    {
        return view('livewire.admin.order-details');
    }
} 