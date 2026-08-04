<?php

namespace App\Services;

use App\Models\Order;

class WhatsAppService
{
    public function sendOrderConfirmation(Order $order): string
    {
        $phone = preg_replace('/[^0-9]/', '', $order->customer_mobile ?: ($order->user->phone ?? ''));
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        }

        $itemsList = collect($order->items_json ?? $order->items)->map(function ($item) {
            $name = is_array($item) ? ($item['product_name'] ?? $item['name'] ?? 'Item') : ($item->product_name ?? 'Item');
            $qty = is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);
            $price = is_array($item) ? ($item['price'] ?? $item['rate'] ?? 0) : ($item->price ?? 0);
            return "• {$name} x {$qty} - ₹" . number_format($price * $qty, 2);
        })->take(5)->join("\n");

        $totalItemCount = count($order->items_json ?? $order->items ?? []);
        if ($totalItemCount > 5) {
            $itemsList .= "\n... and " . ($totalItemCount - 5) . " more items";
        }

        $pdfUrl = route('user.orders.invoice_pdf', $order->id);
        $custName = $order->customer_name ?: ($order->user->name ?? 'Customer');

        $message = "🎆 *RADHE CRACKERS - ORDER CONFIRMATION*\n\n";
        $message .= "Hello *{$custName}*,\n";
        $message .= "Thank you for your order with Radhe Crackers!\n\n";
        $message .= "📦 *Order Details:*\n";
        $message .= "• Order ID: #{$order->id}\n";
        $message .= "• Total Amount: ₹" . number_format($order->total_amount ?? $order->total, 2) . "\n";
        $message .= "• Status: " . ucfirst($order->status ?? 'Pending') . "\n";
        $message .= "• Date: " . ($order->created_at ? $order->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i')) . "\n\n";
        
        if (!empty($itemsList)) {
            $message .= "*Items Ordered:*\n{$itemsList}\n\n";
        }
        
        $message .= "📄 *Download PDF Bill / Estimate:* \n{$pdfUrl}\n\n";
        $message .= "Thank you for shopping with us! 🎆";

        return $this->generateWhatsAppLink($phone, $message);
    }

    public function generateOrderWhatsAppUrl(Order $order): string
    {
        return $this->sendOrderConfirmation($order);
    }

    public function sendOrderSummary(Order $order): string
    {
        return $this->sendOrderConfirmation($order);
    }

    public function sendPaymentReminder(Order $order): string
    {
        $phone = preg_replace('/[^0-9]/', '', $order->customer_mobile ?: ($order->user->phone ?? ''));
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        }

        $pdfUrl = route('user.orders.invoice_pdf', $order->id);

        $message = "💰 *Payment Reminder - Radhe Crackers*\n\n";
        $message .= "Order ID: #{$order->id}\n";
        $message .= "Amount Due: ₹" . number_format($order->total_amount ?? $order->total, 2) . "\n\n";
        $message .= "📄 *PDF Bill:* {$pdfUrl}\n\n";
        $message .= "Please complete your payment and provide the UPI Transaction ID.\n";
        $message .= "Thank you! 🎆";

        return $this->generateWhatsAppLink($phone, $message);
    }

    public function sendOrderStatusUpdate(Order $order): string
    {
        $phone = preg_replace('/[^0-9]/', '', $order->customer_mobile ?: ($order->user->phone ?? ''));
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        }

        $statusEmoji = [
            'confirmed' => '✅',
            'dispatched' => '🚚',
            'completed' => '🎉',
        ];

        $emoji = $statusEmoji[strtolower($order->status)] ?? '📋';
        $pdfUrl = route('user.orders.invoice_pdf', $order->id);

        $message = "{$emoji} *Order Status Update - Radhe Crackers*\n\n";
        $message .= "Order ID: #{$order->id}\n";
        $message .= "Status: " . ucfirst($order->status) . "\n";
        $message .= "Updated: " . now()->format('d/m/Y H:i') . "\n\n";
        $message .= "📄 *Download Invoice PDF:* {$pdfUrl}\n";

        return $this->generateWhatsAppLink($phone, $message);
    }

    public function generateWhatsAppLink(string $phone, string $message): string
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($cleanPhone) === 10) {
            $cleanPhone = '91' . $cleanPhone;
        }
        $encodedMessage = urlencode($message);
        return "https://api.whatsapp.com/send?phone={$cleanPhone}&text={$encodedMessage}";
    }
}