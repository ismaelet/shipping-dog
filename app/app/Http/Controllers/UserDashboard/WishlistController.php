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
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View {
        $title = 'Wishlist';

        $wishlistItems = Auth::user()->wishlist_items;

        return view('user-dashboard.wishlist', [
            'location' => 'user-dashboard',
            'title' => $title,
            'likes' => $wishlistItems,
        ]);
    }
}
