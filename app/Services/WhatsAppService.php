<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;

class WhatsAppService
{
    public function sendOrderConfirmation(Order $order): string
    {
        $items = $order->items->map(function ($item) {
            return "• {$item->product_name} - Qty: {$item->quantity} - ₹" . number_format($item->price * $item->quantity, 2);
        })->join("\n");

        $message = "🎆 *Order Confirmation*\n\n";
        $message .= "Order ID: #{$order->id}\n";
        $message .= "Customer: {$order->user->name}\n";
        $message .= "Phone: {$order->user->phone}\n";
        $message .= "Total: ₹" . number_format($order->total, 2) . "\n\n";
        $message .= "*Items:*\n{$items}\n\n";
        $message .= "Status: {$order->status}\n";
        $message .= "Date: " . $order->created_at->format('d/m/Y H:i') . "\n\n";
        $message .= "Thank you for your order! 🎆";

        return $this->generateWhatsAppLink($order->user->phone, $message);
    }

    public function sendOrderSummary(Order $order): string
    {
        $items = $order->items->map(function ($item) {
            return "• {$item->product_name} - Qty: {$item->quantity} - ₹{$item->subtotal}";
        })->join("\n");

        $message = "🎆 *Order Summary*\n\n";
        $message .= "Order ID: #{$order->id}\n";
        $message .= "Customer: {$order->user->name}\n";
        $message .= "Phone: {$order->user->phone}\n";
        $message .= "Total: ₹{$order->total}\n\n";
        $message .= "*Items:*\n{$items}\n\n";
        $message .= "Status: {$order->status}\n";
        $message .= "Date: " . $order->created_at->format('d/m/Y H:i');

        return $this->generateWhatsAppLink($order->user->phone, $message);
    }

    public function sendPaymentReminder(Order $order): string
    {
        $message = "💰 *Payment Reminder*\n\n";
        $message .= "Order ID: #{$order->id}\n";
        $message .= "Amount: ₹{$order->total}\n\n";
        $message .= "Please complete your payment and provide the UPI Transaction ID.\n";
        $message .= "Thank you! 🎆";

        return $this->generateWhatsAppLink($order->user->phone, $message);
    }

    public function sendOrderStatusUpdate(Order $order): string
    {
        $statusEmoji = [
            'confirmed' => '✅',
            'dispatched' => '🚚',
            'completed' => '🎉',
        ];

        $emoji = $statusEmoji[$order->status] ?? '📋';

        $message = "{$emoji} *Order Status Update*\n\n";
        $message .= "Order ID: #{$order->id}\n";
        $message .= "Status: {$order->status}\n";
        $message .= "Updated: " . now()->format('d/m/Y H:i');

        return $this->generateWhatsAppLink($order->user->phone, $message);
    }

    private function generateWhatsAppLink(string $phone, string $message): string
    {
        $encodedMessage = urlencode($message);
        return "https://wa.me/91{$phone}?text={$encodedMessage}";
    }
} 