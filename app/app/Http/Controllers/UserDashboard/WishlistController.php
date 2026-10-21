<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller {

    /**
     * Display the wishlist page.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View {
        $title = 'Wishlist';

        $wishlistItems = user()->wishlist_items;

        return view('user-dashboard.wishlist', [
            'location' => 'user-dashboard',
            'title' => $title,
            'likes' => $wishlistItems,
        ]);
    }
}
