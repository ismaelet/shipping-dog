<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Seller;
use Illuminate\Http\Request;

class DashboardController extends Controller {

	/**
	 * Display the admin dashboard
	 */
	public function index() {
		return view('legacy-admin.home');
	}

	/**
	 * Update the admin dashboard
	 */
	public function update(Request $request) {
		if ($request->has('accept')) {
			$seller = Seller::where('id', $request->accept)->update(['status' => 1]);
		}

		if ($request->has('deny')) {
			$seller = Seller::find($request->deny);
			$seller->delete();
		}

		return redirect()->back();
	}
}
