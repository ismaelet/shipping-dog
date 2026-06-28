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
    public ?Order $order = null;

    /**
     * Lifecycle hook: Initialize the component
     */
    public function mount(): void {
        if (!$this->order || !$this->order->exists) {
            $this->order = cart();
        }
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render(): View {
        return view('livewire.cart.recap-panel');
    }

    /**
     * Event listener: Refresh the order items when the 'order-items-updated' event is dispatched
     */
    #[On('order-items-updated')]
    public function refreshOrderItems(): void {
        $this->refreshOrder();
    }

    /**
     * Event listener: Refresh the shipping information when the 'shippingUpdated' event is dispatched
     */
    #[On('shippingUpdated')]
    public function refreshShipping(): void {
        $this->refreshOrder();
    }

    /**
     * Event listener: Refresh the used credit information when the 'usedCreditUpdated' event is dispatched
     */
    #[On('usedCreditUpdated')]
    public function usedCreditUpdated(): void {
        $this->refreshOrder();
    }

    /**
     * Helper method: Refresh the order data from the database to ensure the recap panel displays the most up-to-date information
     */
    private function refreshOrder(): void {
        $this->order->updatePrice();
        $this->order = $this->order->fresh() ?: cart();
    }
}
