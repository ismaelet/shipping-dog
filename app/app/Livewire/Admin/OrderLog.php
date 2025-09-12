<?php

namespace App\Livewire\Admin;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use App\Models\Framework\Configuration;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderLog extends Component {

	/**
	 * Property: The orders collection
	 */
	public ?Collection $orders;

	/**
	 * Property: The first order in the current log
	 */
	public ?Order $firstOrder = null;

	/**
	 * Property: The time from which to start showing orders
	 */
	public ?Order $previousOrder = null;

	/**
	 * Property: Whether to show all orders
	 */
	public bool $showAll = false;

	/**
	 * Lifecycle hook: Mount the component
	 */
	public function mount() {
		$this->firstOrder = Order::find(Configuration::where('name', 'order_log_start_from')->value('value'));
		$this->orders = $this->loadOrders();

		if (!$this->firstOrder) {
			$this->previousOrder = Order::find(Configuration::where('name', 'order_log_start_from')->value('value') - 1);
		}
	}

	/**
	 * Lifecycle hook: Render the component
	 */
	public function render() {
		return view('livewire.admin.order-log');
	}

	/**
	 * Event handler: Handle 'firstOrderUpdated' event
	 */
	#[On('firstOrderUpdated')]
	public function onFirstOrderUpdated(array $data): void {
		$firstOrderId = $data['first_order_id'] ?? null;
		$this->firstOrder = Order::find($firstOrderId);
		$this->orders = $this->loadOrders();
	}

	/**
	 * Event handler: Handle 'cleanOrderLog' event
	 */
	#[On('cleanOrderLog')]
	public function onCleanOrderLog(): void {
		$this->orders = null;
	}

	/**
	 * Event handler: Handle 'includeReadyUpdated' event
	 */
	#[On('includeReadyUpdated')]
	public function onIncludeReadyUpdated(array $data): void {
		$this->showAll = $data['include_ready'];
		$this->orders = $this->loadOrders();
	}

	/**
	 * Load orders from the database
	 */
	public function loadOrders(): ?Collection {
		if (!$this->firstOrder) {
			return null;
		}

		$orders = Order::where('created_at', '>=', $this->firstOrder?->created_at)->where(
			'status',
			'>',
			EcommerceHelper::statusFromTag('received'),
		);

		if (!$this->showAll) {
			$orders = $orders->where('status', '<', EcommerceHelper::statusFromTag('awaiting-shipping'));
		}

		return $orders
			->with(
				'user',
				'user.seller',
				'address',
				'shipping_method',
				'coupon',
				'change_request',
				'order_items',
				'order_items.type',
				'order_items.type.sizes',
				'order_items.type.colors',
				'order_items.product',
				'order_items.product_with_trashed',
				'order_items.product.type',
				'order_items.product.type.sizes',
				'order_items.product.type.colors',
				'order_items.color',
				'order_items.size',
				'order_items.position',
				'order_items.logo',
				'payment_method',
			)
			->oldest()
			->get();
	}
}
