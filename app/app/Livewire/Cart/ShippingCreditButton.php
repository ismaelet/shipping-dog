<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShippingCreditButton extends Component {

	public Order $order;

	public bool $elegible;

	public bool $usedCredit;

	/**
	 * Initialize the component.
	 * 
	 * @return void
	 */
	public function mount(): void {
		$this->order = cart();
		$this->usedCredit = $this->order->credit_ship;

		$shippingFeed = (bool) $this->order->shipping_method->price($this->order->quantity);
		$couponUsed = $this->order->coupon;
		$usableCredit = (float) Auth::user()?->seller?->usableCredit();
		$hasSufficientCredit = $usableCredit >= $shippingFeed;

		$this->elegible = is_seller() && $shippingFeed && $hasSufficientCredit && !$couponUsed;
	}

	/**
	 * Use the shipping credit.
	 * 
	 * @return void
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
	 * Remove the shipping credit.
	 * 
	 * @return void
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
	 * Refresh the credit used when the 'usedCreditUpdated' event is dispatched.
	 * 
	 * @return void
	 */
	#[On('usedCreditUpdated')]
	public function usedCreditUpdated(): void {
	}

	/**
	 * Render the component view.
	 * 
	 * @return \Illuminate\View\View
	 */
	public function render(): View {
		return view('livewire.cart.shipping-credit-button');
	}
}
