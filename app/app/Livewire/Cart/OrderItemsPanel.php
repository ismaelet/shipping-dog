<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderItemsPanel extends Component {

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

	/**
	 * Render the component view.
	 * 
	 * @return \Illuminate\View\View
	 */
	public function render(): View {
		return view('livewire.cart.order-items-panel');
	}
}
