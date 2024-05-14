<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\ResourceController;
use App\Models\Ecommerce\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends ResourceController {

	/**
	 * ~ Action Configuration: Index
	 */
	protected array $columns = [
		['label' => 'ID', 'name' => 'id'],
		['label' => 'Design ID', 'name' => 'design_id'],
		['label' => 'Titolo', 'name' => 'name'],
		['label' => 'Negozio', 'name' => 'seller_name'],
		['label' => 'Tipo', 'name' => 'type_name'],
		['label' => 'Aggiunto', 'name' => 'created_at', 'type' => 'date'],
		['label' => 'Rimosso', 'name' => 'deleted_at', 'type' => 'date'],
	];

	/**
	 * * Action: Index
	 */
	public function index(Request $request): View {
		// Prepare the query
		$this->query = Product::leftJoin('stores', 'stores.user_id', '=', 'products.user_id')
			->leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
			->withTrashed()
			->select('products.*', 'stores.name as seller_name', 'product_types.name as type_name');

		// Add search filter
		if ($request->search) {
			$this->query = $this->query->where('products.id', $request->search)
				->orWhere('products.design_id', $request->search)
				->orWhere('products.name', 'like', '%' . $request->search . '%')
				->orWhere('stores.name', 'like', '%' . $request->search . '%');
		}

		// Add the buttons
		$this->buttons = [
			[
				'label' => 'Visualizza',
				'icon' => 'link',
				'color' => 'green',
				'route' => 'products.show',
				'method' => 'GET',
			],
		];

		return parent::index($request);
	}
}
