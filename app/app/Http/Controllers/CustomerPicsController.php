<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerPicsController extends Controller {

	/**
     * Display the customer pictures page.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View {
		return view('customer-pics', []);
	}
}
