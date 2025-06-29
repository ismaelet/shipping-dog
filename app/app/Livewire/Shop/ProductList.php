<?php

namespace App\Livewire\Shop;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductGender;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Seller;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class ProductList extends Component {

	/**
	 * Property: The search query for products
	 */
	#[Url]
	public string $search = '';

	/**
	 * Property: The ID of the selected product type for filtering
	 */
	#[Url(as: 'type')]
	public $selectedTypeId = null;

	/**
	 * Property: The ID of the selected product gender for filtering
	 */
	#[Url(as: 'gender')]
	public $selectedGenderId = null;

	/**
	 * Property: The sorting criteria for the products
	 */
	#[Url]
	public ?string $sort = null;

	/**
	 * Property: The current page number for pagination
	 */
	#[Url]
	public int|string|null $page = 1;

	/**
	 * Property: The default page size for pagination
	 */
	public int $pageSize = 40;

	/**
	 * Property: The seller whose products are to be displayed
	 */
	public ?Seller $seller = null;

	/**
	 * Property: The selected product type for filtering
	 */
	public ?ProductType $selectedType = null;

	/**
	 * Property: The selected product gender for filtering
	 */
	public ?ProductGender $selectedGender = null;

	/**
	 * Property: The selected category for filtering (clothing or accessories)
	 */
	public ?string $selectedCategory = null;

	/**
	 * Property: The collection of products from this page
	 */
	public ?Collection $products = null;

	/**
	 * Property: The total number of products matching the criteria
	 */
	public int $productCount = 0;

	/**
	 * Lifecycle hook: Initialize the component
	 */
	public function mount() {
		$this->setup();
	}

	/**
	 * Lifecycle hook: Render the component view
	 */
	public function render() {
		return view('livewire.shop.product-list');
	}

	/**
	 * Event handler: Update the selected type
	 */
	#[On('selectType')]
	public function selectType(?int $typeId, ?int $genderId): void {
		if ($typeId == $this->selectedTypeId && $genderId == $this->selectedGenderId) {
			$typeId = null;
		}

		$this->selectedTypeId = $typeId;
		$this->selectedGenderId = $genderId;
		$this->page = 1;
		$this->setup();
	}

	/**
	 * Event handler: Update the sorting criteria
	 */
	public function changeSort(string $sort): void {
		$this->sort = $sort;
		$this->page = 1;
		$this->setup();
	}

	/**
	 * Event handler: Update the selected type
	 */
	#[On('clearFilters')]
	public function removeFilters(): void {
		$this->search = '';
		$this->selectedTypeId = null;
		$this->selectedGenderId = null;
		$this->page = 1;
		$this->setup();
	}

	/**
	 * Setup the product list based on the selected filters and sorting
	 */
	public function setup(): void {
		$this->page = (int) $this->page;

		// Set the selected type
		$this->selectedType = $this->selectedTypeId ? ProductType::find($this->selectedTypeId) : null;

		// Set the selected category based on the selected type
		if ($this->selectedType) {
			$this->selectedCategory = $this->selectedType->category;
		}

		// Set the selected gender
		$this->selectedGender = $this->selectedGenderId ? ProductGender::find($this->selectedGenderId) : null;

		// Set the selected gender based on the selected type
		if ($this->selectedType && !$this->selectedGender) {
			$this->selectedGender = $this->selectedType->gender;
		}

		// Start building the query
		$products = Product::join('stores', 'products.user_id', '=', 'stores.user_id')
			->leftJoin('designs', 'products.design_id', '=', 'designs.id')
			->where('products.status', 1)
			->where('products.deleted', 0)
			->whereHas('type', function ($query) {
				$query->whereNull('variant_of');
			});

		if ($this->seller) {
			// Filter for a specific shop
			$products->where('products.user_id', $this->seller->user_id);
		} else {
			// Filter for the general shop
			$products->where('stores.status', true)->where('stores.hidden', false);
		}

		// Set the sort criteria based on the seller's preferences or default
		if (!$this->sort && $this->seller) {
			$this->sort = ['new', 'popular', 'custom'][$this->seller->sort - 1] ?? null;
		}

		$this->sort = !$this->sort ? 'popular' : $this->sort;

		// Order the products
		switch ($this->sort) {
			case 'new':
				$products->orderBy('products.time', 'desc')->orderBy('design_id', 'desc')->orderBy('type_id', 'asc');
				break;

			case 'popular':
				$products
					->orderBy('products.recent_sales', 'desc')
					->orderBy('products.sales', 'desc')
					->orderBy('design_id', 'desc')
					->orderBy('type_id', 'asc');
				break;

			case 'custom':
				$products->orderBy('type_id', 'asc')->orderBy('designs.sort', 'asc');
				break;
		}

		// Filter by keyword
		if ($this->search) {
			$products->where(function ($query) {
				$query
					->where('products.name', 'like', '%' . $this->search . '%')
					->orWhere('stores.name', 'like', '%' . $this->search . '%')
					->orWhereIn('design_id', function ($query) {
						$query
							->select('design_id')
							->from('design_tags')
							->where('tags', 'like', '%' . $this->search . '%');
					});
			});
		}

		// Filters by type
		if ($this->selectedType) {
			$products->where('products.type_id', $this->selectedType->id);
		}

		// Filters by gender
		if (in_array($this->selectedGender, ['m', 'f'])) {
			$searchGender = ProductGender::where('tag', $this->selectedGender)->first();

			$products->where(function ($query) use ($searchGender) {
				$query->whereIn('type_id', ProductType::whereIn('gender_id', [$searchGender->id, 3])->get('id'));
			});
		}

		// Get the product count
		$productCount = $products->count();

		// Get the products
		$start = $this->page ? $this->pageSize * ($this->page - 1) : 1;

		$products = $products
			->limit($this->pageSize)
			->offset($start)
			->with(
				'type',
				'design',
				'design_with_trashed',
				'design.seller',
				'design.logo_group',
				'design.auth_user_like',
				'seller',
				'seller.prices',
				'color',
				'specific_price',
				'base_price',
				'position',
			)
			->get('products.*');

		// store the result
		$this->productCount = $productCount;
		$this->products = $products;
	}
}
