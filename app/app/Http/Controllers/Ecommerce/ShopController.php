<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Design;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller {

	/**
	 * Display the shop page
	 */
	public function index(Request $request): View {
		$highlightedDesign = Design::with('seller', 'main_product')->find(1612);

		return view('ecommerce.shop', [
			'title' => 'Negozio',
			'locationTag' => 'shop',
			'highlightedDesign' => $highlightedDesign,
		]);
	}
}
