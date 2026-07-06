<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;

class WhatsAppLinks extends Component
{
    public $selectedTemplate = '';
    public $customMessage = '';
    public $phoneNumber = '';
    public $orderId = '';
    public $userId = '';
    public $generatedLink = '';
    public $showLinkModal = false;
    
    // Template variables
    public $customerName = '';
    public $otp = '';
    public $orderAmount = '';
    public $orderItems = '';

    public $showWhatsAppLink = false;
    public $whatsappLink = '';

    public $orders;
    public $users;

    public function mount()
    {
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        // Load default templates from settings
        $this->templates = [
            'otp' => $this->getTemplate('whatsapp_otp_template'),
            'order_summary' => $this->getTemplate('whatsapp_order_summary_template'),
            'payment_reminder' => $this->getTemplate('whatsapp_payment_reminder_template'),
            'dispatch_notification' => $this->getTemplate('whatsapp_dispatch_template'),
            'welcome' => $this->getTemplate('whatsapp_welcome_template'),
        ];
    }

    private function getTemplate($key)
    {
        $defaults = [
            'whatsapp_otp_template' => "🔐 *OTP Verification*\n\nHello {name},\n\nYour OTP is: *{otp}*\n\nPlease enter this code to verify your account.\n\nValid for 5 minutes.",
            'whatsapp_order_summary_template' => "🛒 *Order Summary*\n\nOrder ID: #{order_id}\nCustomer: {name}\nPhone: {phone}\n\n*Items:*\n{items}\n\nTotal: ₹{amount}\nStatus: {status}",
            'whatsapp_payment_reminder_template' => "💰 *Payment Reminder*\n\nDear {name},\n\nPayment for order #{order_id} is pending.\nAmount: ₹{amount}\n\nPlease complete payment to confirm your order.",
            'whatsapp_dispatch_template' => "🚚 *Order Dispatched*\n\nDear {name},\n\nYour order #{order_id} has been dispatched!\n\nWe'll keep you updated on delivery status.",
            'whatsapp_welcome_template' => "🎉 *Welcome to Cracker Store*\n\nHello {name},\n\nThank you for registering with us!\n\nWe have the best crackers for all occasions.",
        ];

        return \DB::table('settings')->where('key', $key)->value('value') ?? $defaults[$key] ?? '';
    }

    public function generateOTPLink()
    {
        if (!$this->phoneNumber || !$this->customerName || !$this->otp) {
            session()->flash('error', 'Please fill all required fields.');
            return;
        }

        $message = $this->templates['otp'];
        $message = str_replace('{name}', $this->customerName, $message);
        $message = str_replace('{otp}', $this->otp, $message);

        $this->whatsappLink = "https://wa.me/{$this->phoneNumber}?text=" . urlencode($message);
        $this->showWhatsAppLink = true;
    }

    public function generateOrderSummaryLink()
    {
        if (!$this->orderId) {
            session()->flash('error', 'Please select an order.');
            return;
        }

        $order = Order::with(['user', 'items'])->find($this->orderId);
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $items = $order->items->map(function($item) {
            return "• {$item->product_name} - Qty: {$item->quantity} - ₹" . number_format($item->price, 2);
        })->implode("\n");

        $message = $this->templates['order_summary'];
        $message = str_replace('{order_id}', $order->id, $message);
        $message = str_replace('{name}', $order->user->name, $message);
        $message = str_replace('{phone}', $order->user->phone, $message);
        $message = str_replace('{items}', $items, $message);
        $message = str_replace('{amount}', number_format($order->total_amount, 2), $message);
        $message = str_replace('{status}', $order->status, $message);

        $this->whatsappLink = "https://wa.me/{$order->user->phone}?text=" . urlencode($message);
        $this->showWhatsAppLink = true;
    }

    public function generatePaymentReminderLink()
    {
        if (!$this->orderId) {
            session()->flash('error', 'Please select an order.');
            return;
        }

        $order = Order::with(['user'])->find($this->orderId);
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $message = $this->templates['payment_reminder'];
        $message = str_replace('{name}', $order->user->name, $message);
        $message = str_replace('{order_id}', $order->id, $message);
        $message = str_replace('{amount}', number_format($order->total_amount, 2), $message);

        $this->whatsappLink = "https://wa.me/{$order->user->phone}?text=" . urlencode($message);
        $this->showWhatsAppLink = true;
    }

    public function generateDispatchNotificationLink()
    {
        if (!$this->orderId) {
            session()->flash('error', 'Please select an order.');
            return;
        }

        $order = Order::with(['user'])->find($this->orderId);
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $message = $this->templates['dispatch_notification'];
        $message = str_replace('{name}', $order->user->name, $message);
        $message = str_replace('{order_id}', $order->id, $message);

        $this->whatsappLink = "https://wa.me/{$order->user->phone}?text=" . urlencode($message);
        $this->showWhatsAppLink = true;
    }

    public function generateWelcomeLink()
    {
        if (!$this->userId) {
            session()->flash('error', 'Please select a user.');
            return;
        }

        $user = User::find($this->userId);
        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        $message = $this->templates['welcome'];
        $message = str_replace('{name}', $user->name, $message);

        $this->whatsappLink = "https://wa.me/{$user->phone}?text=" . urlencode($message);
        $this->showWhatsAppLink = true;
    }

    public function generateCustomLink()
    {
        if (!$this->phoneNumber || !$this->customMessage) {
            session()->flash('error', 'Please fill all required fields.');
            return;
        }

        $this->whatsappLink = "https://wa.me/{$this->phoneNumber}?text=" . urlencode($this->customMessage);
        $this->showWhatsAppLink = true;
    }

    public function copyLink()
    {
        $this->dispatch('copyToClipboard', text: $this->whatsappLink);
        session()->flash('success', 'Link copied to clipboard!');
    }

    public function saveTemplate($key)
    {
        $template = $this->templates[$key] ?? '';
        \DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $template, 'updated_at' => now()]
        );
        
        session()->flash('success', 'Template saved successfully!');
    }

    public function getOrders()
    {
        return Order::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
    }

    public function getUsers()
    {
        return User::where('is_admin', false)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
    }

    public function render()
    {
        $this->orders = $this->getOrders();
        $this->users = $this->getUsers();
        
        return view('livewire.admin.whatsapp-links');
    }
} 