<?php

namespace App\Http\Livewire\Pages;

use Livewire\Component;
use App\Models\Stock;

class Cart extends Component
{
    public $cartItems = [];
    public $showCart = false;
    public $total = 0;
    public $itemCount = 0;

    protected $listeners = [
        'addToCart' => 'addItem',
        'removeFromCart' => 'removeItem',
        'updateQuantity' => 'updateQuantity',
        'clearCart' => 'clearCart',
        'toggleCart' => 'toggleCart'
    ];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $cart = session('cart', []);
        $this->cartItems = [];
        $this->total = 0;
        $this->itemCount = 0;

        foreach ($cart as $itemId => $quantity) {
            $product = Stock::find($itemId);
            if ($product && $product->is_active) {
                $this->cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->item_name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'category' => $product->category,
                    'icon' => $this->getCategoryIcon($product->category),
                ];
                $this->total += $product->price * $quantity;
                $this->itemCount += $quantity;
            }
        }
        
        // Emit event to update header cart
        $this->dispatch('cartUpdated', ['total' => $this->total, 'itemCount' => $this->itemCount]);
    }

    public function addItem($productId, $quantity = 1)
    {
        $product = Stock::find($productId);
        if (!$product || !$product->is_active) {
            return;
        }

        $cart = session('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        session(['cart' => $cart]);

        $this->loadCart();
        $this->showCart = true;
    }

    public function removeItem($productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);

        $this->loadCart();
    }

    public function updateQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeItem($productId);
            return;
        }

        $cart = session('cart', []);
        $cart[$productId] = $quantity;
        session(['cart' => $cart]);

        $this->loadCart();
    }

    public function clearCart()
    {
        session()->forget('cart');
        $this->loadCart();
        $this->showCart = false;
    }

    public function toggleCart()
    {
        $this->showCart = !$this->showCart;
    }

    public function checkout()
    {
        if (empty($this->cartItems)) {
            return;
        }

        // Redirect to order form with cart items
        return redirect()->route('order.form');
    }

    private function getCategoryIcon($category)
    {
        return match($category) {
            'BOMBS' => '💣',
            'SINGLE FLASH' => '⚡',
            'ROCKETS' => '🚀',
            'SPARKLERS' => '✨',
            'CHIT PUT' => '🎆',
            'TWINKLING STAR' => '⭐',
            'GIFT BOX' => '🎁',
            'BIJILI CRACKERS' => '⚡',
            default => '🎆',
        };
    }

    public function render()
    {
        return view('livewire.pages.cart');
    }
} 