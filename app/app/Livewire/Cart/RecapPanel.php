<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class RecapPanel extends Component {

	public Order $order;

	/**
	 * Initialize the component.
	 * 
	 * @return void
	 */
	public function boot(?Order $order = null): void {
		$this->order = $order->id ?: cart();
	}

	/**
	 * Refresh the order items when the 'orderItemsUpdated' event is dispatched.
	 * 
	 * @return void
	 */
	#[On('orderItemsUpdated')]
	public function refreshOrderItems(): void {
	}

	/**
	 * Refresh the shipping details when the 'shippingUpdated' event is dispatched.
	 * 
	 * @return void
	 */
	#[On('shippingUpdated')]
	public function refreshShipping(): void {
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
		return view('livewire.cart.recap-panel');
	}
}
