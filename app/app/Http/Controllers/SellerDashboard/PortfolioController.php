<?php

namespace App\Http\Controllers\Ecommerce\SellerDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller {

	/**
	 * Display the seller's portfolio.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		return view('seller-dashboard.portfolio', []);
	}
}
