<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderItemsPanel extends Component {

    /**
     * Property: The open order
     */
    public ?Order $order = null;

    /**
     * Property: Whether the order has been completed
     */
    public bool $isCompleted = false;

    /**
     * Lifecycle hook: Mount the component
     */
    public function mount(): void {
        $this->order = $this->order ?: cart();
        $this->isCompleted = (bool) $this->order?->status;
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render(): View {
        return view('livewire.cart.order-items-panel');
    }

    /**
     * Event handler: Refresh the order items when the 'orderItemsUpdated' event is dispatched
     */
    #[On('orderItemsUpdated')]
    public function refreshOrderItems(): void {
        $this->order->load(
            'order_items.color',
            'order_items.position',
            'order_items.size',
            'order_items.product',
            'order_items.product.type',
            'order_items.product_with_trashed',
            'order_items.product_with_trashed.design_with_trashed',
            'order_items.product_with_trashed.type',
        );
    }
}
