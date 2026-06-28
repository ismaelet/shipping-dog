<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderItemCreditButton extends Component {

    /**
     * Property: The order item ID for which the credit button is displayed
     */
    public int $orderItemId;

    /**
     * Property: The loaded order item
     */
    public ?OrderItem $orderItem = null;

    /**
     * Property: Whether the credit has been used for the order item
     */
    public bool $usedCredit = false;

    /**
     * Property: Whether the seller is eligible to use credit for the order item
     */
    public bool $eligible = false;

    /**
     * Lifecycle hook: Mount the component
     */
    public function mount(int $orderItemId): void {
        $this->orderItemId = $orderItemId;

        $this->loadOrderItem();
        $this->refreshState();
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render(): View {
        return view('livewire.cart.order-item-credit-button');
    }

    /**
     * Event handler: Use the credit for the order item
     */
    public function useCredit(): void {
        $this->loadOrderItem();

        if (! $this->orderItem) {
            return;
        }

        $this->orderItem->update([
            'credit' => true,
        ]);

        $this->orderItem->updatePrices();
        $this->orderItem->order?->updatePrice();

        $this->refreshState();

        $this->dispatch('used-credit-updated');
    }

    /**
     * Event handler: Remove the credit used for the order item
     */
    public function removeCredit(): void {
        $this->loadOrderItem();

        if (! $this->orderItem) {
            return;
        }

        $this->orderItem->update([
            'credit' => false,
        ]);

        $this->orderItem->updatePrices();
        $this->orderItem->order?->updatePrice();

        $this->refreshState();

        $this->dispatch('used-credit-updated');
    }

    /**
     * Event handler: Refresh the credit used when the event is dispatched
     */
    #[On('used-credit-updated')]
    public function usedCreditUpdated(): void {
        $this->loadOrderItem();
        $this->refreshState();
    }

    /**
     * Helper method: Load the order item safely
     */
    private function loadOrderItem(): void {
        $this->orderItem = OrderItem::with([
            'order.coupon',
            'product',
            'color',
        ])->find($this->orderItemId);
    }

    /**
     * Helper method: Refresh the button state
     */
    private function refreshState(): void {
        if (! $this->orderItem) {
            $this->usedCredit = false;
            $this->eligible = false;

            return;
        }

        $order = $this->orderItem->order;

        $this->usedCredit = (bool) $this->orderItem->credit;

        $price = $this->orderItem->total_amount_after_discount;
        $couponUsed = (bool) $order?->coupon;
        $usableCredit = (float) Auth::user()?->seller?->usableCredit();
        $hasSufficientCredit = $usableCredit >= $price;

        $this->eligible = is_seller()
            && $price > 0
            && $hasSufficientCredit
            && ! $couponUsed;
    }
}
