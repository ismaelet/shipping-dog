<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Http\Controllers\Controller;
use App\Legacy\System\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PricingController extends Controller {

	/**
	 * Display the pricing page for the seller.
	 * 
	 * @param Request $request
	 * @return View
	 */
	public function index(Request $request): View {
		$seller = Auth::user()->seller;

		// setup variabiles
		$done = false;

		// get types
		$res_cloth = DB::select('id FROM product_types WHERE category=? AND hidden = 0 ORDER BY id ASC', ['c']);
		$res_acc = DB::select('id FROM product_types WHERE category=? AND hidden = 0 ORDER BY id ASC', ['m']);


		return view('seller-dashboard.pricing', [
			'seller' => $seller,
			'done' => $done,
			'res_cloth' => $res_cloth,
			'res_acc' => $res_acc,
		]);
	}

	public function _old() {

		$res = \Legacy\System\Classes\LegacyDB::select('id FROM product_types WHERE hidden = 0 ORDER BY id ASC');

		while ($row = $res->fetch_assoc()) {

			$type = new LegacyProductType($row['id']);

			$price = $_POST['price_' . $type->slug];

			$row_price = \Legacy\System\Classes\LegacyDB::row('* FROM prices_stores WHERE seller_id = ? AND type_id = ?', [auth()->id(), $type->id]) ?: \Legacy\System\Classes\LegacyDB::row('* FROM prices_default WHERE type_id = ?', [$type->id]);

			$price_old = $row_price['price'];
			$gain_w_old = $row_price['gain_w'];
			$gain_c_old = $row_price['gain_c'];

			$gain_w = round(($price - $price_old) * .75 + $gain_w_old, 2);
			$gain_c = round(($price - $price_old) * .75 + $gain_c_old, 2);

			// add prices
			if (!\Legacy\System\Classes\LegacyDB::exists('prices_stores WHERE seller_id = ? AND type_id = ?', [auth()->id(), $type->id]))
				\Legacy\System\Classes\LegacyDB::add('prices_stores (seller_id, type_id, price, gain_w, gain_c) VALUES (?, ?, ?, ?, ?)', [auth()->id(), $type->id, $row_price['price'], $row_price['gain_w'], $row_price['gain_c']]);

			// update prices
			\Legacy\System\Classes\LegacyDB::update('prices_stores SET price=?, gain_w=?, gain_c=? WHERE seller_id = ? AND type_id = ?', [$price, $gain_w, $gain_c, auth()->id(), $type->id]);
			// \Legacy\System\Classes\LegacyDB::update('items_prices SET price=?, gain_w=?, gain_c=? WHERE product_id IN (SELECT id FROM products WHERE user_id = ? AND type_id = ?)', [$price, $gain_w, $gain_c, auth()->id(), $type->id]);

		}

		$done = true;

		redirect_old();
	}
}
