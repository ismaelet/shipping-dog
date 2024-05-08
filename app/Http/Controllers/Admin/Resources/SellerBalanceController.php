<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\ResourceController;
use App\Models\Ecommerce\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SellerBalanceController extends ResourceController {

	/**
	 * - Index action configuration
	 */
	protected array $columns = [
		['label' => 'ID', 'name' => 'id'],
		['label' => 'Nome', 'name' => 'name'],
		['label' => 'Guadagnato', 'name' => 'earned', 'type' => 'price'],
		['label' => 'Pagato', 'name' => 'payed', 'type' => 'price'],
		['label' => 'Trasferenze', 'name' => 'other_transfers', 'type' => 'price'],
		['label' => 'Credito', 'name' => 'credit', 'type' => 'price'],
		['label' => 'Da pagare', 'name' => 'to_be_payed', 'type' => 'price',],
	];

	/**
	 * - Edit action configuration
	 */
	// protected string $titleField = 'name';

	/**
	 * * Index action
	 */
	public function index(Request $request): View {
		// // Update earned
		// DB::update('UPDATE stores SET earned = IFNULL((SELECT SUM(gain) as tot FROM order_items WHERE seller_id = stores.id AND status > 1), 0)');

		// // Update paied
		// DB::update('UPDATE stores SET payed = IFNULL((SELECT SUM(value) as tot FROM stores_payments WHERE seller_id = stores.id AND other = 0), 0)');

		// // Update other transferences
		// DB::update('UPDATE stores SET other_transfers = IFNULL((SELECT SUM(value) as tot FROM stores_payments WHERE seller_id = stores.id AND other = 1), 0)');

		// // Update shipping price
		// DB::update('UPDATE orders SET shipping = (CASE WHEN quantity = 1 THEN 5.9 ELSE 7.4 END) WHERE credit_ship = 1 AND shipping = 0');

		// // Update credit
		// DB::update('UPDATE stores SET credit = (
		// 	earned * 0.1 - 
		// 		IFNULL((SELECT SUM((fullProductPrice - price_disc) * quantity) as orderItemsCredit FROM order_items WHERE user_id = stores.user_id AND status != -1 AND credit = 1), 0) -
		// 		IFNULL((SELECT SUM(shipping) as shippingCredit FROM orders WHERE user_id = stores.user_id AND status != -1 AND credit_ship = 1),0)
		// 	)');

		// Prepare the query
		$this->query = Seller::where('status', '=', 1)
			->select('*')
			->selectRaw('(earned - payed + other_transfers) AS to_be_payed');

		// Add search filter
		if ($request->search) {
			$this->query = $this->query->where('stores.id', $request->search)
				->orWhere('stores.name', 'like', '%' . $request->search . '%')
				->orWhere('stores.id', $request->search);
		}

		// Add the buttons
		$this->buttons = [
			[
				'type' => 'edit',
			],
			[
				'type' => 'delete',
			],
		];

		return $this->_index($request);
	}

	/**
	 * * Edit action
	 */
	// public function edit(int $id): View {
	// 	$this->record = User::find($id);

	// 	return $this->_edit();
	// }

	/**
	 * * Update action
	 */
	// public function update(Request $request, int $id) {
	// 	// Find the record
	// 	$this->record = User::find($id);

	// 	// Validate the request
	// 	$request->validate([
	// 		'email' => ['required', 'email'],
	// 	]);

	// 	$this->record->update($request->only(['email', 'first_name', 'last_name']));

	// 	return $this->_update($request);
	// }
}
