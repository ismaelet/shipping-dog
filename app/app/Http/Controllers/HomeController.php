<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Design\CustomerPhoto;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Seller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller {

	/**
	 * Display the home page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {

		$productN = 20;
		$popularDesignCount = 20;
		$sellerCount = 12;
		$fanPicsCount = 4;

		// products
		$popular = Product::join('stores', 'products.user_id', '=', 'stores.user_id')
			->where('products.status', true)
			->where('stores.status', true)
			->with('design', 'design_with_trashed', 'design.seller', 'design.auth_user_like', 'seller', 'seller.prices', 'design.logo_group', 'color', 'type', 'position', 'specific_price', 'base_price')
			->orderBy('products.recent_sales', 'desc')
			->limit($productN)
			->get('products.*');

		// designs
		$popularDesigns = Design::join('stores', 'designs.user_id', '=', 'stores.user_id')
			->where('designs.status', true)
			->where('stores.status', true)
			->with('main_product', 'products', 'seller')
			->orderBy('designs.recent_sales', 'desc')
			->limit($popularDesignCount)
			->get('designs.*');

		// sellers
		$sellers = Seller::where('status', true)
			->orderByDesc('recent_sales', 'desc')
			->orderBy('user_id')
			->with('user')
			->limit($sellerCount)
			->get();

		// customer pics
		$customerPhotos = CustomerPhoto::inRandomOrder()
			->limit($fanPicsCount)
			->with('design', 'design.main_product')
			->get();

		// categories
		$categories = [
			['Abbigliamento Donna', 'Tantissimi colori disponibili', 'shop?gender=f'],
			['Felpe & Hoodie', 'Calde e confortevoli', 'shop?type=hodf'],
			['Abbigliamento<br>Uomo', 'T-shirt, canotte e molto altro', 'shop?gender=m'],
			['Zaini & Accessori', 'Sacche, astucci, maschere...', 'shop?group=m'],
			['Mouse Pad', '', 'shop?type=pad'],
		];

		$features = [
			[
				"Spedizioni rapide in Italia",
				"Riceverai il tuo ordine entro 24 ore dal momento della spedizione in Italia, 48 ore per le isole."
			],
			[
				"Nuovi sconti ogni mese",
				"Ogni mese troverai offerte per i prodotti selezionati o per tutti gli articoli presenti nel nostro shop."
			],
			[
				"Servizio di cambio taglia e rimborso",
				"Voi cambiare taglia? Basta seguire la nostra procedura online, facile e veloce."
			],
		];

		return view('home', [
			'products' => $popular,
			'designs' => $popularDesigns,
			'sellers' => $sellers,
			'customerPhotos' => $customerPhotos,
			'categories' => $categories,
			'features' => $features,
		]);
	}
}
