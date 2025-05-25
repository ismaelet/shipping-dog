<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleTermsController extends Controller {

	/**
	 * Display the sale terms page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		$title = 'Condizioni di vendita';
		$locationTag = 'support';

		return view('support.sale-terms', [
			'title' => $title,
			'locationTag' => $locationTag,
		]);
	}
}
