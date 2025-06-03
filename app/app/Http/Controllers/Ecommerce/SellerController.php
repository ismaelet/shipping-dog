<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Legacy\Ecommerce\Product;
use App\Legacy\System\DB;
use App\Models\Ecommerce\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SellerController extends Controller {

	/**
	 * Display the seller page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View|RedirectResponse {

		// get variabiles
		$id = request()->seller ? (int) request()->seller : (int) request()->id;
		$seller = Seller::find($id);
		$user = $seller?->user;
		$sellerUserId = $seller?->user_id;

		// redirect to user's shop if no ID is specified
		if (!$seller) {
			return redirect('shop');
		}

		$title = $seller->name;
		$thumbnail = $seller->hasCover ? $seller->cover[0] : null;
		$description = $seller->description ?: null;

		// special status
		$owner = Auth::id() == $sellerUserId ? true : false;
		$vip = $owner || is_admin() ? true : false;

		// check if shop hasn't been approved yet
		$public = $seller->status;

		if (!$public && !$vip) {
			return redirect('shop');
		}

		// get products data
		$collBlock = 10;

		// select links
		$hasItems = DB::exists('products WHERE user_id = ? AND status > 0', [$sellerUserId]);
		$productCount = DB::count(Product::TABLE . ' WHERE user_id = ? AND status > 0', [$sellerUserId]);
		$likeN = DB::count('likes WHERE design_id IN (SELECT id FROM designs WHERE user_id = ?)', [$sellerUserId]);
		$follN = DB::count('followers WHERE follower_id = ?', [$sellerUserId]);

		if ($vip) $collN = DB::count('collections WHERE user_id = ?', [$sellerUserId]);
		else $collN = DB::count('collections WHERE user_id = ? AND ID IN (SELECT collection FROM designs)', [$sellerUserId]);

		// add folder
		if (isset($_POST['add_folder'])) {
			$name = trim($_POST['name']);

			if (!empty($name)) {

				$collID = DB::add('collections (user_id, name) VALUES (?, ?)', [$sellerUserId, $name]);

				redirect_old('/artist/collection?id=' . $collID);
			} else redirect_old();
		}

		$contacts = ['website', 'facebook', 'instagram', 'twitter', 'youtube', 'tiktok', 'bluesky'];

		return view('ecommerce.seller', [
			'title' => $title,
			'description' => $description,
			'thumbnail' => $thumbnail,
			'locationTag' => 'shop',
			'seller' => $seller,
			'user' => $user,
			'vip' => $vip,
			'owner' => $owner,
			'id' => $id,
			'contacts' => $contacts,
			'productCount' => $productCount,
			'likeN' => $likeN,
			'follN' => $follN,
			'collN' => $collN,
			'hasItems' => $hasItems,
			'collBlock' => $collBlock,
		]);
	}
}
