<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Models\User\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller {

	/**
	 * Display the wishlist page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		$title = 'Wishlist';

		$wishlistItems = Auth::user()->wishlist_items->load('design', 'design.main_product', 'design.seller');

		return view('user-dashboard.wishlist', [
			'locationTag' => 'user-dashboard',
			'title' => $title,
			'likes' => $wishlistItems,
		]);
	}
}
