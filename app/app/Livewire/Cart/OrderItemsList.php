<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderItemsList extends Component
{
    /**
     * Property: The open order ID
     */
    public ?int $orderId = null;

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
    public function mount(): void
    {
        $this->orderId = $this->order ? $this->order->id : cart()?->id;

        $this->loadOrderItems();
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render(): View
    {
        return view('livewire.cart.order-items-list');
    }

    /**
     * Action: Remove an order item from the order
     */
    public function removeItem(int $orderItemId): void
    {
        if (! $this->orderId) {
            return;
        }

        $order = Order::with('order_items')->find($this->orderId);

        if (! $order || $order->status) {
            $this->clearOrder();

            return;
        }

        $orderItem = $order->order_items->firstWhere('id', $orderItemId);

        if (! $orderItem) {
            return;
        }

        $wasLastItem = $order->order_items->count() === 1;

        $orderItem->delete();

        if ($wasLastItem) {
            $this->clearOrder();

            $order->delete();

            $this->dispatch('update-cart', showSidebar: false);

            return;
        }

        $order->updateOrderItems();

        $this->loadOrderItems();

        $this->dispatch('order-items-updated');
        $this->dispatch('update-cart', showSidebar: false);
    }

    /**
     * Event handler: Update the order items when an order item is added, removed, or updated
     */
    #[On('order-items-updated')]
    public function updateOrderItems(): void
    {
        $this->loadOrderItems();
    }

    /**
     * Helper method: Load the order items and related data
     */
    private function loadOrderItems(): void
    {
        if (! $this->orderId) {
            $this->clearOrder();

            return;
        }

        $this->order = Order::with([
            'order_items.color',
            'order_items.position',
            'order_items.size',
            'order_items.logo',
            'order_items.type',
            'order_items.product',
            'order_items.product.type',
            'order_items.product_with_trashed',
            'order_items.product_with_trashed.design_with_trashed',
            'order_items.product_with_trashed.type',
        ])->find($this->orderId);

        if (! $this->order) {
            $this->clearOrder();

            return;
        }

        $this->isCompleted = (bool) $this->order->status;
    }

    /**
     * Helper method: Clear the loaded order
     */
    private function clearOrder(): void
    {
        $this->orderId = null;
        $this->order = null;
        $this->isCompleted = false;
    }
}
