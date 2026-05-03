<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\ShippingMethod as ShippingMethodModel;
use Livewire\Component;

class ShippingMethod extends Component {

    /**
     * Property: The cart associated with the cart page
     */
    public Order $cart;

    /**
     * Property: The shipping method associated with the cart
     */
    public ShippingMethodModel $shippingMethod;

    /**
     * Lifecycle hook: Render the component view
     */
    public function render() {
        return view('livewire.cart.shipping-method');
    }

    /**
     * Action handler: Update the shipping method of the cart
     */
    public function selectShippingMethod(): void {
        $this->cart->shipping_method_id = $this->shippingMethod->id;
        $this->cart->save();

        $this->dispatch('shippingUpdated');
    }
}
