<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\ShippingMethod;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ShippingMethods extends Component {

    /**
     * Property: The cart associated with the cart page
     */
    public Order $cart;

    /**
     * Property: The available shipping methods
     */
    public Collection $shippingMethods;

    /**
     * Property: Whether the cart has a long delivery time
     */
    public bool $hasLongDelivery = false;

    /**
     * Property: The selected shipping method ID
     */
    public ?int $selectedShippingMethodId = null;

    /**
     * Lifecycle hook: Mount the component
     */
    public function mount(): void {
        $this->selectedShippingMethodId = $this->cart->shipping_method_id;
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render() {
        $this->cart->loadMissing([
            'order_items.product',
            'order_items.product.type',
            'order_items.color',
        ]);

        $this->hasLongDelivery = $this->cart->hasLongDelivery();

        return view('livewire.cart.shipping-methods');
    }

    /**
     * Updated hook: When the selected shipping method ID is updated, select the shipping method and update the cart
     */
    public function updatedSelectedShippingMethodId(int|string $value): void {
        $this->selectShippingMethod($value);
    }

    /**
     * Action: Select a shipping method and update the cart 
     */
    public function selectShippingMethod(int|string $value): void {
        $value = (int) $value;

        if ($this->selectedShippingMethodId === $value) {
            return;
        }

        $this->selectedShippingMethodId = $value;

        $this->cart->update([
            'shipping_method_id' => $value,
        ]);

        $this->cart->shipping_method_id = $value;

        $this->dispatch('shippingUpdated');
    }
}
