<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderItemCreditButton extends Component {

	public OrderItem $orderItem;

	public bool $usedCredit;

	public bool $elegible;

	/**
	 * Initialize the component.
	 * 
	 * @return void
	 */
	public function mount(): void {
		$order = cart();
		$this->usedCredit = $this->orderItem->usedCredit();

		$price = $this->orderItem->priceBeforeCredit();
		$couponUsed = $order->coupon;
		$usableCredit = Auth::user()->seller->usableCredit();
		$hasSufficientCredit = $usableCredit >= $price;

		$this->elegible = is_seller() && $price && $hasSufficientCredit && !$couponUsed;
	}

	/**
	 * Use the credit for the order item.
	 * 
	 * @return void
	 */
	public function useCredit(): void {
		$this->orderItem->update([
			'credit' => true,
		]);

		$this->orderItem->order->updatePrice();

		$this->usedCredit = true;

		$this->dispatch('usedCreditUpdated');
	}


	/**
	 * Remove the credit used for the order item.
	 * 
	 * @return void
	 */
	public function removeCredit(): void {
		$this->orderItem->update([
			'credit' => false,
		]);

		$this->orderItem->order->updatePrice();

		$this->usedCredit = false;

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
		return view('livewire.cart.order-item-credit-button');
	}
}
