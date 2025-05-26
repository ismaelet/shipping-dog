<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Seller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller {

	/**
	 * Display the search page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		$search = $_GET['search'] ?? '';
		$locationTag = 'search';
		$title = 'Cerca: ' . $search;

		$resultCount = 0;
		$max = 20;

		if (strlen($search) > 2) {

			$search = trim(strtolower($search));

			//get design results
			$designs = Design::join('stores', 'designs.user_id', '=', 'stores.user_id')
				->where('stores.status', true)
				->where('designs.name', 'like', "%$search%")
				->orWhere('stores.name', 'like', "%$search%")
				// ->orWhereHas('tags', function ($query) use ($search) {
				// $query->where('tags', 'like', "%$search%");
				// })
				->orderByDesc('designs.sales')
				->orderByDesc('stores.sales')
				->orderByDesc('designs.id')
				->limit($max)
				->with('main_product', 'products', 'main_product.design_with_trashed', 'main_product.color', 'main_product.position', 'main_product.type', 'seller')
				->select('designs.*')
				->get();

			$designCount = $designs->count();

			//get seller results
			$sellers = Seller::where('status', true)
				->where('name', 'like', "%$search%")
				->orderByDesc('sales')
				->orderByDesc('id')
				->limit($max)
				->with('user')
				->get();

			$sellerCount = $sellers->count();

			//get total number of results		
			$resultCount = $designCount + $sellerCount;
		}


		return view('search', [
			'title' => $title,
			'locationTag' => $locationTag,
			'search' => $search,
			'designs' => $designs ?? collect(),
			'sellers' => $sellers ?? collect(),
			'designCount' => $designCount ?? 0,
			'sellerCount' => $sellerCount ?? 0,
			'resultCount' => $resultCount,
			'maxResults' => $max,
		]);
	}
}
