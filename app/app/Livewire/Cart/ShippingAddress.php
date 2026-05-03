<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\ShippingMethod;
use App\Models\Shipping\Province;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class ShippingAddress extends Component {

    /**
     * Property: The cart associated with the cart page
     */
    public Order $cart;

    /**
     * Property: The address associated with the cart
     */
    public ?Address $address = null;

    /**
     * Property: Flag indicating whether the cart is being shipped or not
     */
    public bool $isShipped = true;

    /**
     * Property: The collection of provinces available
     */
    public Collection $provinces;

    /**
     * Lifecycle hook: Initialize the component
     */
    public function mount(): void {
        $this->address = $this->cart->address;
        $this->provinces = Province::orderBy('name')->get();
        $this->isShipped = $this->getIsShipped();
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render() {
        return view('livewire.cart.shipping-address');
    }

    /**
     * Event listener: Refresh the shipping information when the shipping method is updated
     */
    #[On('shippingUpdated')]
    public function refreshShipping(): void {
        $this->cart->refresh();
        $this->isShipped = $this->getIsShipped();
    }

    /**
     * Helper method: Determine if the cart is being shipped or not
     */
    private function getIsShipped(): bool {
        return $this->cart->shipping_method?->isShipped() ?? true;
    }
}
