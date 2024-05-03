<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Legacy\Classes\Ecommerce\Product as LegacyProduct;
use Legacy\System\Classes\LegacyDB;
use Legacy\System\Classes\LegacyView;

class ProductController extends Controller {

	/**
	 * * Show a specific product
	 */
	public function show(Product $product): RedirectResponse {
		redirect_old('/products/' . $product->slug());
	}
}
