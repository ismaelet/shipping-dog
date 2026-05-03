<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class RecapPanel extends Component {

    /**
     * Property: The order associated with the recap panel
     */
    public Order $order;

    /**
     * Lifecycle hook: Initialize the component
     */
    public function boot(?Order $order = null): void {
        $this->order = $order->id ?: cart();
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render(): View {
        return view('livewire.cart.recap-panel');
    }

    /**
     * Event listener: Refresh the order items when the 'orderItemsUpdated' event is dispatched
     */
    #[On('orderItemsUpdated')]
    public function refreshOrderItems(): void {
    }

    /**
     * Event listener: Refresh the shipping information when the 'shippingUpdated' event is dispatched
     */
    #[On('shippingUpdated')]
    public function refreshShipping(): void {
    }

    /**
     * Event listener: Refresh the used credit information when the 'usedCreditUpdated' event is dispatched
     */
    #[On('usedCreditUpdated')]
    public function usedCreditUpdated(): void {
    }
}
