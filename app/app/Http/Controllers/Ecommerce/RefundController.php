<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefundController extends Controller {

	/**
	 * Display the refund page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		return view('under-construction', [
			'title' => 'Cambio/Reso',
			'locationTag' => 'support',
		]);
	}
}
