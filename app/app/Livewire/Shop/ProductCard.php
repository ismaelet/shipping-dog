<?php

namespace App\Livewire\Shop;

use App\Models\Ecommerce\Product;
use Livewire\Component;

class ProductCard extends Component {

	/**
	 * Property: The product to be displayed in the card
	 */
	public Product $product;

	/**
	 * Lifecycle hook: Render the component view
	 */
	public function render() {
		return view('livewire.shop.product-card');
	}
}
