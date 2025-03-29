<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\ResourceController;
use App\Models\Ecommerce\Design;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignController extends ResourceController {

	/**
	 * ~ Action Configuration: Index
	 */
	protected array $columns = [
		['label' => 'ID', 'name' => 'id'],
		['label' => 'Nome', 'name' => 'name'],
		['label' => 'Negozio', 'name' => 'seller_name'],
		['label' => 'Vendite Recenti', 'name' => 'recent_sales',],
		['label' => 'Vendite Totali', 'name' => 'sales',],
		['label' => 'Attivo', 'name' => 'status', 'type' => 'bool'],
		['label' => 'Aggiunto il', 'name' => 'created_at', 'type' => 'date'],
	];

	/**
	 * * Index action
	 */
	public function index(Request $request): View {
		// Prepare the query
		$this->query = Design::leftJoin('stores', 'stores.user_id', '=', 'designs.user_id')
			->select('designs.*', 'stores.name as seller_name');

		// Add search filter
		if ($request->search) {
			$this->query = $this->query->where('designs.id', $request->search)
				->orWhere('designs.name', 'like', '%' . $request->search . '%')
				->orWhere('stores.id', $request->search)
				->orWhere('stores.name', 'like', '%' . $request->search . '%');
		}

		// Add the buttons
		$this->buttons = [
			[
				'label' => 'Design',
				'icon' => 'design',
				'color' => 'green',
				'type' => 'download',
				'model_method' => 'originalImage',
			],
			[
				'label' => 'Design su nero',
				'icon' => 'design',
				'color' => 'dark-green',
				'type' => 'download',
				'model_method' => 'originalDarkImage',
			],
		];

		return parent::index($request);
	}
}
