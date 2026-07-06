<?php

namespace App\Http\Livewire\Pages;

use Livewire\Component;
use App\Models\Stock;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderLog;
use App\Services\WhatsAppService;
use App\Services\PDFService;

class OrderNow extends Component
{
    public $items = [];
    public $selectedItems = [];
    public $total = 0;
    public $notes = '';
    public $message = '';
    public $showSuccess = false;

    public function mount()
    {
        $this->loadItems();
    }

    public function loadItems()
    {
        $this->items = Stock::where('is_active', true)
            ->where('quantity', '>', 0)
            ->orderBy('category')
            ->orderBy('item_name')
            ->get()
            ->toArray();
    }

    public function addItem($itemId)
    {
        $item = collect($this->items)->firstWhere('id', $itemId);
        
        if ($item) {
            $existingItem = collect($this->selectedItems)->firstWhere('id', $itemId);
            
            if ($existingItem) {
                $this->selectedItems = collect($this->selectedItems)->map(function ($selectedItem) use ($itemId) {
                    if ($selectedItem['id'] == $itemId) {
                        $selectedItem['quantity']++;
                        $selectedItem['subtotal'] = $selectedItem['quantity'] * $selectedItem['price'];
                    }
                    return $selectedItem;
                })->toArray();
            } else {
                $this->selectedItems[] = [
                    'id' => $item['id'],
                    'product_name' => $item['item_name'],
                    'quantity' => 1,
                    'price' => $item['price'],
                    'subtotal' => $item['price'],
                ];
            }
            
            $this->calculateTotal();
        }
    }

    public function removeItem($itemId)
    {
        $this->selectedItems = collect($this->selectedItems)
            ->filter(function ($item) use ($itemId) {
                return $item['id'] != $itemId;
            })
            ->toArray();
        
        $this->calculateTotal();
    }

    public function updateQuantity($itemId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeItem($itemId);
            return;
        }

        $this->selectedItems = collect($this->selectedItems)->map(function ($item) use ($itemId, $quantity) {
            if ($item['id'] == $itemId) {
                $item['quantity'] = $quantity;
                $item['subtotal'] = $quantity * $item['price'];
            }
            return $item;
        })->toArray();

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = collect($this->selectedItems)->sum('subtotal');
    }

    public function placeOrder()
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            $this->addError('auth', 'Please login to place an order.');
            return;
        }

        if (empty($this->selectedItems)) {
            $this->addError('items', 'Please select at least one item.');
            return;
        }

        try {
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $this->total,
                'status' => 'pending',
                'notes' => $this->notes,
            ]);

            // Create order items
            foreach ($this->selectedItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }

            // Create order log
            OrderLog::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'notes' => 'Order created',
                'changed_by' => 'user',
            ]);

            // Try to send WhatsApp notification (don't fail if this doesn't work)
            try {
                $whatsappService = app(WhatsAppService::class);
                $whatsappService->sendOrderConfirmation($order);
            } catch (\Exception $e) {
                // Log the error but don't fail the order
                \Log::error('WhatsApp notification failed: ' . $e->getMessage());
            }

            // Try to generate PDF (don't fail if this doesn't work)
            try {
                $pdfService = app(PDFService::class);
                $pdfService->generateOrderConfirmation($order);
            } catch (\Exception $e) {
                // Log the error but don't fail the order
                \Log::error('PDF generation failed: ' . $e->getMessage());
            }

            // Reset form
            $this->selectedItems = [];
            $this->total = 0;
            $this->notes = '';
            $this->message = 'Order placed successfully! Order ID: ' . $order->id;
            $this->showSuccess = true;

            // Reload items
            $this->loadItems();

        } catch (\Exception $e) {
            \Log::error('Order placement failed: ' . $e->getMessage());
            $this->addError('order', 'Failed to place order. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.order-now')
            ->layout('layouts.app');
    }
} 