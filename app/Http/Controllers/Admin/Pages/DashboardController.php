<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Cart;

class DashboardController extends Controller {

	/**
	 * Display the admin dashboard
	 */
	public function index() {
		return view('admin.home');
	}
}
