<?php

namespace App\Livewire\Shop;

use App\Models\Ecommerce\Product\ProductGender;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Seller;
use Illuminate\Support\Collection;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CategorySelector extends Component {

	/**
	 * Reactive property: The selected product type
	 */
	#[Reactive]
	public ?ProductType $selectedType = null;

	/**
	 * Reactive property: The selected gender
	 */
	#[Reactive]
	public ?ProductGender $selectedGender = null;

	/**
	 * Reactive property: The selected category
	 */
	#[Reactive]
	public ?string $selectedCategory = null;

	/**
	 * Property: The ID of the gender to filter product types
	 */
	public ?int $genderId = null;

	/**
	 * Property: The category to filter product types
	 */
	public ?string $category = null;

	/**
	 * Property: The seller whose products are to be displayed
	 */
	public ?Seller $seller = null;

	/**
	 * Property: The gender associated with the selected filter
	 */
	public ?ProductGender $gender = null;

	/**
	 * Property: Collection of product types available for the selected category
	 */
	public Collection $types;

	/**
	 * Property: Whether the current filter is checked
	 */
	public bool $genderChecked = false;

	/**
	 * Lifecycle hook: Initialize the component
	 */
	public function boot() {
		$this->types = collect();
		$this->gender = ProductGender::find($this->genderId);

		$types = ProductType::where('category', $this->category)->where('hidden', false)->doesntHave('main_type');

		if ($this->category == 'c') {
			$types = $types->where(function ($query) {
				$query
					->whereHas('gender', function ($query) {
						$query->where('id', $this->gender->id);
					})
					->orWhere('unisex', true);
			});
		}

		if ($this->seller) {
			$types = $types->whereHas('products', function ($query) {
				$query->where('user_id', $this->seller->user_id)->where('status', true);
			});
		}

		$types = $types->orderBy('sort')->get();

		foreach ($types as $type) {
			$checked = $this->selectedGender?->id == $this->gender?->id && $this->selectedType?->id == $type->id;

			$this->types->push(
				(object) [
					'id' => $type->id,
					'names' => $type->names,
					'checked' => $checked,
				],
			);
		}

		$this->genderChecked = $this->types->contains(fn($type) => $type->checked);
	}

	/**
	 * Lifecycle hook: Render the component view
	 */
	public function render() {
		return view('livewire.shop.category-selector');
	}
}
