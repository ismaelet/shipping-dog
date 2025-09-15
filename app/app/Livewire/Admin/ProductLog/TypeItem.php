<?php

namespace App\Livewire\Admin\ProductLog;

use App\Legacy\Ecommerce\Product;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductSize;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class TypeItem extends Component {

	/**
	 * Property: The order items collection
	 */
	public Collection $orderItems;

	/**
	 * Property: The product type to filter by
	 */
	public ProductType $type;

	/**
	 * Property: The product color to filter by
	 */
	public ProductColor $color;

	/**
	 * Property: The product size to filter by
	 */
	public ProductSize $size;

	/**
	 * Property: The count of printed items
	 */
	public int $isPrintedCount;

	/**
	 * Property: The count of not printed items
	 */
	public int $isNotPrintedCount;

	/**
	 * Property: The count of items in cart
	 */
	public int $isInCartCount;

	/**
	 * Property: The count of items not in cart
	 */
	public int $isNotInCartCount;

	/**
	 * Property: The design (optional)
	 */
	public ?Design $design = null;

	/**
	 * Property: The design (optional)
	 */
	public string $category;

	/**
	 * Property: The quantity of items
	 */
	public int $quantity = 0;

	/**
	 * Lifecycle hook: Mount the component
	 */
	public function mount() {
		$this->category = $this->design ? 'design' : 'product';

		$this->updateCounts();

		$quantityQuery = $this->orderItems->where('type_id', $this->type->id)
			->where('color_id', $this->color->id)
			->where('size_id', $this->size->id);

		if ($this->design) {
			$quantityQuery = $quantityQuery->where('design_id', $this->design->id);
		}

		$this->quantity = $quantityQuery->sum('quantity');
	}

	/**
	 * Lifecycle hook: Render the component
	 */
	public function render() {
		return view('livewire.admin.product-log.type-item');
	}

	/**
	 * Action handler: Handle 'setPrinted' action
	 */
	public function setIsPrinted(): void {
		$filteredOrderItems = $this->orderItems?->where('type_id', $this->type->id)
			->where('color_id', $this->color->id)
			->where('size_id', $this->size->id);

		if ($this->design) {
			$filteredOrderItems = $filteredOrderItems->where('design_id', $this->design->id);
		}

		if ($filteredOrderItems) {
			foreach ($filteredOrderItems as $item) {
				$item->update(['is_printed' => (bool) $this->isNotPrintedCount]);
			}
		}

		$this->updateCounts();

		$this->dispatch('isPrintedUpdated.' . ($this->category === 'design' ? 'product' : 'design') . '.' . $this->type->id . '.' . $this->color->id . '.' . $this->size->id, ['design_id' => $this->design?->id]);
	}

	/**
	 * Action handler: Handle 'setInCart' action
	 */
	public function setInCart(): void {
		$filteredOrderItems = $this->orderItems?->where('type_id', $this->type->id)
			->where('color_id', $this->color->id)
			->where('size_id', $this->size->id);

		if ($this->design) {
			$filteredOrderItems = $filteredOrderItems->where('design_id', $this->design->id);
		}

		if ($filteredOrderItems) {
			foreach ($filteredOrderItems as $item) {
				$item->update(['is_in_cart' => (bool) $this->isNotInCartCount]);
			}
		}

		$this->updateCounts();

		$this->dispatch('inCartUpdated.' . ($this->category === 'design' ? 'product' : 'design') . '.' . $this->type->id . '.' . $this->color->id . '.' . $this->size->id);
	}

	/**
	 * Event handler: Handle 'inCartUpdated' event
	 */
	#[On('inCartUpdated.{category}.{type.id}.{color.id}.{size.id}')]
	public function onInCartUpdated(): void {
		$this->updateCounts();
	}

	#[On('isPrintedUpdated.{category}.{type.id}.{color.id}.{size.id}')]
	public function onIsPrintedUpdated(array $data): void {
		$this->updateCounts();
	}

	public function updateCounts(): void {
		$this->isPrintedCount = $this->isPrintedCount();
		$this->isNotPrintedCount = $this->isNotPrintedCount();
		$this->isInCartCount = $this->isInCartCount();
		$this->isNotInCartCount = $this->isNotInCartCount();
	}

	public function isPrintedCount(): int {
		$isPrinted = $this->orderItems->where('type_id', $this->type->id)
			->where('color_id', $this->color->id)
			->where('size_id', $this->size->id)
			->where('is_printed', true);

		if ($this->design) {
			$isPrinted = $isPrinted->where('design_id', $this->design->id);
		}

		return $isPrinted->count();
	}

	public function isNotPrintedCount(): int {
		$isNotPrinted = $this->orderItems->where('type_id', $this->type->id)
			->where('color_id', $this->color->id)
			->where('size_id', $this->size->id)
			->where('is_printed', false);

		if ($this->design) {
			$isNotPrinted = $isNotPrinted->where('design_id', $this->design->id);
		}

		return $isNotPrinted->count();
	}

	public function isInCartCount(): int {
		$isInCart = $this->orderItems->where('type_id', $this->type->id)
			->where('color_id', $this->color->id)
			->where('size_id', $this->size->id)
			->where('is_in_cart', true);

		if ($this->design) {
			$isInCart = $isInCart->where('design_id', $this->design->id);
		}

		return $isInCart->count();
	}

	public function isNotInCartCount(): int {
		$isNotInCart = $this->orderItems->where('type_id', $this->type->id)
			->where('color_id', $this->color->id)
			->where('size_id', $this->size->id)
			->where('is_in_cart', false);

		if ($this->design) {
			$isNotInCart = $isNotInCart->where('design_id', $this->design->id);
		}

		return $isNotInCart->count();
	}
}
