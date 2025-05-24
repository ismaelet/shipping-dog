<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Legacy\Ecommerce\Seller as LegacySeller;
use App\Legacy\System\DB;
use App\Models\Ecommerce\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller {

	/**
	 * Displays the seller dashboard.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index(): \Illuminate\View\View {

		$saved = $_GET['saved'] ?? false;
		$seller = Auth::user()->seller;
		$legacySeller = new LegacySeller(Auth::id());

		// get socials data
		$sellerSocials = DB::row('* FROM links WHERE user_id = ?', [Auth::id()]);

		if (!$sellerSocials) DB::add('links (user_id) VALUES (?)', [Auth::id()]);

		return view('seller-dashboard.index', [
			'seller' => Auth::user()->seller,
			'saved' => $saved,
		]);
	}

	public function _old() {


		// edit shop data
		if (isset($_POST['edit'])) {
			$name = trim($_POST['name']);
			$desc = trim($_POST['desc']);
			echo $sort = $_POST['sort'];

			if (empty($name)) $name = $legacySeller->name;

			\Legacy\System\Classes\LegacyDB::update(Seller::TABLE . ' SET name = ?, description = ?, sort = ? WHERE ' . Seller::INDEX . ' = ?', [$name, $desc, $sort, Auth::id()]);

			redirect_old();
		}


		// edit socials
		if (isset($_POST['edit-socials'])) {

			$site = trim(strtolower($_POST['site']));

			$fb = trim($_POST['fb']);
			if ($pos = strpos($fb, 'facebook.com/')) $fb = substr($fb, $pos + 13);
			$fb = strtok($fb, '/');
			$fb = str_replace('@', '', $fb);
			$fb = str_replace(' ', '', $fb);

			$inst = trim($_POST['inst']);
			if ($pos = strpos($inst, 'instagram.com/')) $inst = substr($inst, $pos + 14);
			$inst = strtok($inst, '/');
			$inst = str_replace('@', '', $inst);

			$twit = trim($_POST['twit']);
			if ($pos = strpos($twit, 'twitter.com/')) $twit = substr($twit, $pos + 12);
			$twit = strtok($twit, '/');
			$twit = str_replace('@', '', $twit);

			$yt = trim($_POST['yt']);
			if ($pos = strpos($yt, 'youtube.com/')) $yt = substr($yt, $pos + 12);
			$yt = strtok($yt, '/');

			if (!\Legacy\System\Classes\LegacyDB::exists('links WHERE user_id = ?', [Auth::id()])) \Legacy\System\Classes\LegacyDB::add('links (user_id) VALUE (?)', [Auth::id()]);

			\Legacy\System\Classes\LegacyDB::update('links SET site=?, facebook=?, instagram=?, twitter=?, youtube=? WHERE user_id = ?', [$site, $fb, $inst, $twit, $yt, Auth::id()]);

			redirect_old();
		}
	}

	/**
	 * Uploads the seller icon.
	 *
	 * @param Seller $seller The seller instance.
	 * @param Request $request The request object.
	 * @return RedirectResponse The redirect response.
	 */
	public function uploadSellerIcon(Seller $seller, Request $request): RedirectResponse {

		$request->validate([
			'icon' => ['required', 'mimes:jpeg,png,gif,webp'],
		]);

		FileHelper::uploadImage(
			file: $request->file('icon'),
			filePath: 'users/icons/' . $seller->user_id . '.webp',
			size: 252,
			mode: 'crop',
		);

		return redirect()->back();
	}

	/**
	 * Uploads a cover image for the seller's dashboard.
	 *
	 * @param Seller $seller The seller instance.
	 * @param Request $request The HTTP request object.
	 * @return RedirectResponse The redirect response.
	 */
	public function uploadSellerCover(Seller $seller, Request $request): RedirectResponse {

		$request->validate([
			'cover' => ['required', 'mimes:jpeg,png,gif,webp'],
		]);

		FileHelper::uploadImage(
			file: $request->file('cover'),
			filePath: 'shops/covers/' . $seller->user_id . '.webp',
			size: 1920,
			mode: 'width',
		);

		FileHelper::uploadImage(
			file: $request->file('cover'),
			filePath: 'shops/covers/' . $seller->user_id . '_m.webp',
			size: 400,
			mode: 'width',
		);

		return redirect()->back();
	}
}
