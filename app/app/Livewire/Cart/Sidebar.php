<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class Sidebar extends Component
{
    /**
     * Property: The current cart ID
     */
    public ?int $cartId = null;

    /**
     * Property: The order items in the cart
     */
    public ?Collection $orderItems = null;

    /**
     * Property: The cart quantity
     */
    public int $quantity = 0;

    /**
     * Property: The cart subtotal
     */
    public float $subtotal = 0;

    /**
     * Property: Whether the cart is empty
     */
    #[Locked]
    public bool $isEmpty = true;

    /**
     * Lifecycle hook: Initialize the component
     */
    public function mount(): void
    {
        $this->loadCart();
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render(): View
    {
        return view('livewire.cart.sidebar');
    }

    /**
     * Event handler: Update the cart when an order item is added, removed, or updated
     */
    #[On('update-cart')]
    public function updateCart(bool $showSidebar = true): void
    {
        $this->loadCart();

        if ($this->quantity > 0 && $showSidebar) {
            $this->dispatch('open-cart-sidebar');
        }
    }

    /**
     * Helper method: Load the current cart and order items
     */
    private function loadCart(): void
    {
        $cartId = cart()?->id;

        if (! $cartId) {
            $this->clearCart();

            return;
        }

        $cart = Order::with([
            'order_items.product',
            'order_items.product_with_trashed',
            'order_items.color',
            'order_items.position',
            'order_items.size',
            'order_items.logo',
            'order_items.type',
        ])->find($cartId);

        if (! $cart) {
            $this->clearCart();

            return;
        }

        $this->cartId = $cart->id;
        $this->orderItems = $cart->order_items;
        $this->quantity = $cart->order_items->sum('quantity');
        $this->subtotal = $cart->fullOrderItemsPrice();
        $this->isEmpty = $cart->order_items->isEmpty();
    }

    /**
     * Helper method: Clear the cart properties
     */
    private function clearCart(): void
    {
        $this->cartId = null;
        $this->orderItems = null;
        $this->quantity = 0;
        $this->subtotal = 0;
        $this->isEmpty = true;
    }
}
