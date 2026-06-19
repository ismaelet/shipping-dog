<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderItemCreditButton extends Component {

    /**
     * Property: The order item for which the credit button is displayed
     */
    public OrderItem $orderItem;

    /**
     * Property: Whether the credit has been used for the order item
     */
    public bool $usedCredit;

    /**
     * Property: Whether the seller is elegible to use credit for the order item
     */
    public bool $elegible;

    /**
     * Lifecycle hook: Mount the component
     */
    public function mount(): void {
        $order = cart();
        $this->usedCredit = $this->orderItem->usedCredit();

        $price = $this->orderItem->total_amount_after_discount;
        $couponUsed = $order->coupon;
        $usableCredit = (float) Auth::user()?->seller?->usableCredit();
        $hasSufficientCredit = $usableCredit >= $price;

        $this->elegible = is_seller() && $price && $hasSufficientCredit && !$couponUsed;
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
        $this->orderItem->update([
            'credit' => true,
        ]);

        $this->orderItem->updatePrices();
        $this->orderItem->order->updatePrice();

        $this->usedCredit = true;

        $this->dispatch('usedCreditUpdated');
    }


    /**
     * Event handler: Remove the credit used for the order item
     */
    public function removeCredit(): void {
        $this->orderItem->update([
            'credit' => false,
        ]);

        $this->orderItem->updatePrices();
        $this->orderItem->order->updatePrice();

        $this->usedCredit = false;

        $this->dispatch('usedCreditUpdated');
    }

    /**
     * Event handler: Refresh the credit used when the 'usedCreditUpdated' event is dispatched
     */
    #[On('usedCreditUpdated')]
    public function usedCreditUpdated(): void {
    }
}
