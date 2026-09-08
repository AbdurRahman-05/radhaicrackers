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
        $path = $pdfService->generateAdminAllOrdersInvoice(collect([$this->order]));
        $filename = "order_{$this->order->id}_invoice.pdf";
        $pdfContent = \Storage::disk('public')->get($path);
        return response()->streamDownload(function() use ($pdfContent) {
            echo $pdfContent;
        }, $filename);
    }

    public function sendWhatsAppSummary()
    {
        $customerName = $this->order->customer_name ?: ($this->order->user->name ?? 'Customer');
        $rawPhone = $this->order->customer_mobile ?: ($this->order->user->phone ?? '');
        $cleanPhone = preg_replace('/[^0-9]/', '', (string)$rawPhone);
        if (strlen($cleanPhone) === 12 && str_starts_with($cleanPhone, '91')) {
            $cleanPhone = substr($cleanPhone, 2);
        } elseif (strlen($cleanPhone) === 11 && str_starts_with($cleanPhone, '0')) {
            $cleanPhone = substr($cleanPhone, 1);
        }
        $fullWaPhone = '91' . $cleanPhone;

        $items = collect($this->order->items)->map(function($item) {
            $name = is_array($item) ? ($item['product_name'] ?? '-') : ($item->product_name ?? '-');
            $qty = is_array($item) ? ($item['quantity'] ?? '-') : ($item->quantity ?? '-');
            $price = is_array($item) ? ($item['price'] ?? $item['rate'] ?? 0) : ($item->price ?? $item->rate ?? 0);
            return "• {$name} - Qty: {$qty} - ₹" . number_format($price, 2);
        })->implode("\n");

        $totalAmt = $this->order->total_amount ?: ($this->order->total ?: 0);
        $message = "🛒 *Order Summary*\n\n";
        $message .= "Order ID: #{$this->order->id}\n";
        $message .= "Customer: {$customerName}\n";
        $message .= "Phone: {$cleanPhone}\n";
        $message .= "Status: " . ucfirst($this->order->status) . "\n";
        $message .= "Payment: " . ucfirst($this->order->payment_status) . "\n\n";
        $message .= "*Items:*\n{$items}\n\n";
        $message .= "Total Amount: ₹" . number_format($totalAmt, 2) . "\n";
        $message .= "Order Date: " . $this->order->created_at->format('d/m/Y H:i');

        // Also trigger automated WhatsApp confirmation API if available
        try {
            $smsService = new \App\Services\SMSService();
            $smsService->sendWhatsApp($cleanPhone, '', 'order_confirmation', [
                'customer_name' => $customerName,
                'order_id' => (string)$this->order->id,
                'order_value' => '₹' . number_format($totalAmt, 2),
            ]);
            session()->flash('success', "Automated WhatsApp sent & direct link generated!");
        } catch (\Throwable $e) {
            \Log::error('WhatsApp Summary API trigger error: ' . $e->getMessage());
        }

        $this->whatsappLink = "https://wa.me/{$fullWaPhone}?text=" . urlencode($message);
        $this->showWhatsAppLink = true;
    }

    public function sendDispatchNotification()
    {
        $customerName = $this->order->customer_name ?: ($this->order->user->name ?? 'Customer');
        $rawPhone = $this->order->customer_mobile ?: ($this->order->user->phone ?? '');
        $cleanPhone = preg_replace('/[^0-9]/', '', (string)$rawPhone);
        if (strlen($cleanPhone) === 12 && str_starts_with($cleanPhone, '91')) {
            $cleanPhone = substr($cleanPhone, 2);
        } elseif (strlen($cleanPhone) === 11 && str_starts_with($cleanPhone, '0')) {
            $cleanPhone = substr($cleanPhone, 1);
        }
        $fullWaPhone = '91' . $cleanPhone;

        $message = "🚚 *Order Dispatched*\n\n";
        $message .= "Dear {$customerName},\n\n";
        $message .= "Your order #{$this->order->id} has been dispatched and is on its way!\n\n";
        $message .= "We'll keep you updated on the delivery status.\n\n";
        $message .= "Thank you for choosing our service!";

        try {
            $smsService = new \App\Services\SMSService();
            $smsService->sendWhatsApp($cleanPhone, '', 'order_dispatched', [
                'customer_name' => $customerName,
                'order_id' => (string)$this->order->id,
                'order_value' => '₹' . number_format($this->order->total_amount ?: ($this->order->total ?: 0), 2),
                'transport_provider' => $this->order->transport_provider ?: 'Lorry Transport',
                'transport_details' => $this->order->transport_details ?: 'Assigned',
                'delivery_point' => $this->order->delivery_point ?: $this->order->customer_city,
                'delivery_type' => $this->order->delivery_type,
            ]);
            session()->flash('success', "Out for Delivery WhatsApp notification sent!");
        } catch (\Throwable $e) {
            \Log::error('WhatsApp Dispatch API trigger error: ' . $e->getMessage());
        }

        $this->whatsappLink = "https://wa.me/{$fullWaPhone}?text=" . urlencode($message);
        $this->showWhatsAppLink = true;
    }

    public function sendPaymentReminder()
    {
        $customerName = $this->order->customer_name ?: ($this->order->user->name ?? 'Customer');
        $rawPhone = $this->order->customer_mobile ?: ($this->order->user->phone ?? '');
        $cleanPhone = preg_replace('/[^0-9]/', '', (string)$rawPhone);
        if (strlen($cleanPhone) === 12 && str_starts_with($cleanPhone, '91')) {
            $cleanPhone = substr($cleanPhone, 2);
        } elseif (strlen($cleanPhone) === 11 && str_starts_with($cleanPhone, '0')) {
            $cleanPhone = substr($cleanPhone, 1);
        }
        $fullWaPhone = '91' . $cleanPhone;

        $totalAmt = $this->order->total_amount ?: ($this->order->total ?: 0);
        $message = "💰 *Payment Reminder*\n\n";
        $message .= "Dear {$customerName},\n\n";
        $message .= "This is a friendly reminder that payment for order #{$this->order->id} is pending.\n\n";
        $message .= "Total Amount: ₹" . number_format($totalAmt, 2) . "\n\n";
        $message .= "Please complete the payment to confirm your order.\n\n";
        $message .= "Thank you!";

        $this->whatsappLink = "https://wa.me/{$fullWaPhone}?text=" . urlencode($message);
        $this->showWhatsAppLink = true;
    }

    public function render()
    {
        // Pre-calculate full active stocks serial mapping to match price list catalog serials
        $allActiveCats = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get();
        $allActiveStocks = \App\Models\Stock::where('is_active', true)->get()->groupBy('category');
        $catalogSnoMap = [];
        $snoCounter = 0;
        foreach ($allActiveCats as $cat) {
            $catStocks = $allActiveStocks->get($cat->name) ?? $allActiveStocks->get($cat->id) ?? collect();
            foreach ($catStocks->sortBy('order_within_category') as $stockItem) {
                $snoCounter++;
                $catalogSnoMap[$stockItem->id] = $snoCounter;
            }
        }

        return view('livewire.admin.order-details', [
            'catalogSnoMap' => $catalogSnoMap,
        ]);
    }
} 