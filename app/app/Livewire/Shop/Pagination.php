<?php

namespace App\Livewire\Shop;

use Livewire\Attributes\Reactive;
use Livewire\Attributes\Url;
use Livewire\Component;

class Pagination extends Component {

	/**
	 * Reactive property: The total number of products to paginate
	 */
	#[Reactive]
	public int $productCount;

	/**
	 * Property: The current page number for pagination
	 */
	#[Url, Reactive]
	public int $page;

	/**
	 * Property: The default page size for pagination
	 */
	public int $pageSize;

	/**
	 * Property: The total number of pages calculated from product count and page size
	 */
	public int $pageCount;

	/**
	 * Property: The bottom page number for pagination display
	 */
	public ?int $bottomPage = null;

	/**
	 * Property: The top page number for pagination display
	 */
	public ?int $topPage = null;

	/**
	 * Lifecycle hook: Initialize the component
	 */
	public function boot() {
		$this->pageCount = ceil($this->productCount / $this->pageSize);

		if ($this->pageCount > 1) {
			$this->bottomPage = max($this->page - 2, 1);
			$this->topPage = min($this->bottomPage + 4, $this->pageCount);
		}
	}

	/**
	 * Lifecycle hook: Render the component view
	 */
	public function render() {
		return view('livewire.shop.pagination');
	}
}
