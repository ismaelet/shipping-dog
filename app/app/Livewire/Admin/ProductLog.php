<?php

namespace App\Livewire\Admin;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use App\Models\Framework\Configuration;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductLog extends Component {

	/**
	 * Property: The orders collection
	 */
	public ?Collection $orderItems;

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
	 * Property: Whether to show printed items
	 */
	public bool $showPrinted = false;

	/**
	 * Property: Whether to show items in cart
	 */
	public bool $showInCart = false;

	/**
	 * Lifecycle hook: Mount the component
	 */
	public function mount() {
		$this->firstOrder = Order::find(Configuration::where('name', 'product_log_start_from')->value('value'));
		$this->orderItems = $this->loadOrderItems();

		if (!$this->firstOrder) {
			// $this->previousOrder = Order::find(Configuration::where('name', 'product_log_start_from')->value('value') - 1);
		}
	}

	/**
	 * Lifecycle hook: Render the component
	 */
	public function render() {
		return view('livewire.admin.product-log');
	}

	/**
	 * Event handler: Handle 'firstOrderUpdated' event
	 */
	#[On('firstOrderUpdated')]
	public function onFirstOrderUpdated(array $data): void {
		$firstOrderId = $data['first_order_id'] ?? null;
		$this->firstOrder = Order::find($firstOrderId);
		$this->orderItems = $this->loadOrderItems();
	}

	/**
	 * Event handler: Handle 'cleanOrderLog' event
	 */
	#[On('cleanOrderLog')]
	public function onCleanOrderLog(): void {
		$this->orderItems = null;
	}

	/**
	 * Event handler: Handle 'includeReadyUpdated' event
	 */
	#[On('includeReadyUpdated')]
	public function onIncludeReadyUpdated(array $data): void {
		$this->showAll = $data['include_ready'];
		$this->orderItems = $this->loadOrderItems();
	}

	/**
	 * Event handler: Handle 'showPrintedUpdated' event
	 */
	#[On('showPrintedUpdated')]
	public function onShowPrintedUpdated(array $data): void {
		$this->showPrinted = $data['show_printed'];
		$this->orderItems = $this->loadOrderItems();
	}

	/**
	 * Event handler: Handle 'showInCartUpdated' event
	 */
	#[On('showInCartUpdated')]
	public function onShowInCartUpdated(array $data): void {
		$this->showInCart = $data['show_in_cart'];
		$this->orderItems = $this->loadOrderItems();
	}

	/**
	 * Load orders from the database
	 */
	public function loadOrderItems(): ?Collection {
		if (!$this->firstOrder) {
			return null;
		}

		// Get the orders
		$filteredOrderItems = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
			->join('addresses', 'addresses.order_id', '=', 'orders.id')
			->join('stores', 'stores.id', '=', 'order_items.seller_id')
			->join('products', 'products.id', '=', 'order_items.product_id')
			->join('product_types', 'product_types.id', '=', 'order_items.type_id')
			->where('orders.created_at', '>=', $this->firstOrder?->created_at)
			->where('orders.status', '>=', EcommerceHelper::statusFromTag('confirmed'))
			->select(
				'orders.id as order_id',
				'orders.created_at as confirmedTime',
				'order_items.*',
				'stores.name as storeName',
				'addresses.full_name as customerName',
				'products.name as productName',
				'product_types.name as productTypeName',
			);

		if (!$this->showAll) {
			$filteredOrderItems = $filteredOrderItems
				->where('orders.status', EcommerceHelper::statusFromTag('confirmed'));
		}

		if (!$this->showPrinted && !$this->showInCart) {
			$filteredOrderItems = $filteredOrderItems
				->where('order_items.is_printed', false)
				->where('order_items.is_in_cart', false);
		} else {
			if ($this->showPrinted) {
				$filteredOrderItems = $filteredOrderItems->where('order_items.is_printed', true);
			}

			if ($this->showInCart) {
				$filteredOrderItems = $filteredOrderItems->where('order_items.is_in_cart', true);
			}
		}

		return $filteredOrderItems
			->with(
				'type',
				'type.sizes',
				'type.colors',
				'color',
				'size',
				'position',
				'logo',
				'product_with_trashed',
				'product_with_trashed.type',
				'product_with_trashed.type.colors',
				'product_with_trashed.type.sizes',
				'seller',
				'design',
			)
			->get();
	}
}
