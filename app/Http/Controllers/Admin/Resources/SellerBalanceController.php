<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\ResourceController;
use App\Models\Ecommerce\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SellerBalanceController extends ResourceController {

	/**
	 * ~ Action Configuration: Index
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
	 * * Action: Index
	 */
	public function index(Request $request): View {
		// Prepare the query
		$this->query = Seller::where('status', true)
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

		return parent::index($request);
	}
}
