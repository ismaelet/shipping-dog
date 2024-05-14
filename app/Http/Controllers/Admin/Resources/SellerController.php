<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\ResourceController;
use App\Models\Ecommerce\Seller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerController extends ResourceController {

	/**
	 * ~ Action Configuration: Index
	 */
	protected array $columns = [
		['label' => 'ID', 'name' => 'id'],
		['label' => 'Nome', 'name' => 'name'],
		['label' => 'Descrizione', 'name' => 'description'],
		['label' => 'E-mail', 'name' => 'email'],
		['label' => 'Vendite Recenti', 'name' => 'recent_sales', 'type' => 'int'],
		['label' => 'Vendite Totali', 'name' => 'sales', 'type' => 'int'],
		['label' => 'Attivo', 'name' => 'status', 'type' => 'bool'],
		['label' => 'Creato il', 'name' => 'created_at', 'type' => 'date'],
	];

	/**
	 * * Action: Index
	 */
	public function index(Request $request): View {
		// Prepare the query
		$this->query = Seller::join('users', 'users.id', '=', 'stores.user_id')
			->select('stores.*', 'users.username', 'users.email');

		// Add search filter
		if ($request->search) {
			$this->query = $this->query->where('stores.id', $request->search)
				->orWhere('stores.name', 'like', '%' . $request->search . '%')
				->orWhere('users.id', $request->search)
				->orWhere('users.username', 'like', '%' . $request->search . '%')
				->orWhere('users.email', 'like', '%' . $request->search . '%');
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
