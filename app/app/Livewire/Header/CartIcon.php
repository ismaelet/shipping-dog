<?php

namespace App\Livewire\Header;

use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class CartIcon extends Component
{
    /**
     * Property: The quantity of items in the cart
     */
    #[Locked]
    public int $quantity = 0;

    /**
     * Lifecycle hook: Initialize the component
     */
    public function mount(): void
    {
        $this->updateCart();
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render(): View
    {
        return view('livewire.header.cart-icon');
    }

    /**
     * Event: Update the cart quantity
     */
    #[On('update-cart')]
    public function updateCart(): void
    {
        $cart = cart();

        if (! $cart) {
            $this->quantity = 0;

            return;
        }

        $this->quantity = $cart->order_items()->sum('quantity');
    }
}
