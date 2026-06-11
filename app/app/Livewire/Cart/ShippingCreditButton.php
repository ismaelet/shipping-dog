<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShippingCreditButton extends Component {

    /**
     * Property: The open order
     */
    public Order $order;

    /**
     * Property: Whether the seller is elegible to use shipping credit
     */
    public bool $elegible;

    /**
     * Property: Whether the shipping credit has been used
     */
    public bool $usedCredit;

    /**
     * Lifecycle hook: Mount the component
     */
    public function mount(): void {
        $this->order = cart();
        $this->usedCredit = $this->order->credit_ship;

        $shippingFee = $this->order->shipping_method->price($this->order->quantity);
        $couponUsed = $this->order->coupon;
        $usableCredit = (float) Auth::user()?->seller?->usableCredit();
        $hasSufficientCredit = $usableCredit >= $shippingFee;

        $this->elegible = is_seller() && $shippingFee && $hasSufficientCredit && !$couponUsed;
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render(): View {
        return view('livewire.cart.shipping-credit-button');
    }

    /**
     * Use the shipping credit
     */
    public function useCredit(): void {
        $this->order->update([
            'credit_ship' => true,
        ]);

        $this->usedCredit = true;

        $this->order->updatePrice();

        $this->dispatch('usedCreditUpdated');
    }

    /**
     * Remove the shipping credit
     */
    public function removeCredit(): void {
        $this->order->update([
            'credit_ship' => false,
        ]);

        $this->usedCredit = false;

        $this->order->updatePrice();

        $this->dispatch('usedCreditUpdated');
    }

    /**
     * Refresh the credit used when the 'usedCreditUpdated' event is dispatched
     */
    #[On('usedCreditUpdated')]
    public function usedCreditUpdated(): void {
    }
}
