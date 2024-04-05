<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Seller;
use Illuminate\Http\Request;

class HomeController extends Controller {

	/**
	 * Display the admin dashboard
	 */
	public function index() {

		// Count the labels to be printed
		$labelCount = Cart::unlabelled()->count();

		// Select the sellers awaiting for approval
		$awaitingSeller = Seller::awaitingApproval();

		return view('admin.home')
			->with('labelCount', $labelCount)
			->with('awaitingSeller', $awaitingSeller);
	}

	// // accept seller entry
	// if (isset($_POST['accept-seller'])) {
	// 	(new Shop($_POST['accept-seller']))->accept();
	// }

	// // delete seller entry
	// if (isset($_POST['delete-seller'])) {
	// 	$seller = Shop::find($_POST['delete-seller']);
	// 	$seller->deleteAndClean();
	// }
}
