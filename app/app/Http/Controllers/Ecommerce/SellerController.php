<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerController extends Controller {

	/**
	 * Display the seller page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		return view('shop', []);
	}

	public function add(Request $request): View {
		return view('shop', []);
	}

	public function prices(Request $request): View {
		return view('shop', []);
	}
}
