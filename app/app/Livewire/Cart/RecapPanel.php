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
	public function mount(?Order $order = null): void {
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
	 * Render the component view.
	 * 
	 * @return \Illuminate\View\View
	 */
	public function render(): View {
		return view('livewire.cart.recap-panel');
	}
}
