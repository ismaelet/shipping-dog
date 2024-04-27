<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\ResourceController;
use App\Models\Ecommerce\Cart;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends ResourceController {

	/**
	 * - Index action configuration
	 */
	protected array $columns = [
		['label' => 'ID', 'name' => 'id'],
		['label' => 'Destinatario', 'name' => 'full_name', 'type' =>  'method', 'method' => 'address.fullAddress'],
		['label' => 'Articoli', 'type' =>  'method', 'method' => 'ordersString',],
		// ['label' => 'Spedizione', 'name' => 'shipping_method_name'],
		['label' => 'Totale', 'name' => 'tot', 'type' => 'method', 'method' => 'priceString', 'wrap' => false],
		['label' => 'Registrato', 'name' => 'user_id', 'type' => 'bool'],
		['label' => 'Stato', 'name' => 'status', 'type' => 'method', 'method' => 'statusTag',],
		['label' => 'Data', 'name' => 'created_at', 'type' => 'date'],
	];

	/**
	 * - Edit action configuration
	 */
	protected string $titleField = 'id';

	/**
	 * * Index action
	 */
	public function index(Request $request): View {
		// Prepare the query
		$this->query = Cart::leftJoin('sys_shipping', 'carts.shipping_method_id', '=', 'sys_shipping.id')
			->leftJoin('addresses', 'carts.id', '=', 'addresses.cart_id')
			->select('carts.*', 'sys_shipping.name as shipping_method_name');

		// Add search filter
		if ($request->search) {
			$this->query = $this->query->where('carts.id', $request->search)
				->orWhere('full_name', 'like', '%' . $request->search . '%')
				->orWhere('first_name', 'like', '%' . $request->search . '%')
				->orWhere('last_name', 'like', '%' . $request->search . '%')
				->orWhere('email', 'like', '%' . $request->search . '%')
				->orWhere('city', 'like', '%' . $request->search . '%')
				->orWhere('email', 'like', '%' . $request->search . '%')
				->orWhereHas('orders', function ($query) use ($request) {
					$query->whereHas('product', function ($query) use ($request) {
						$query->where('name', 'like', '%' . $request->search . '%');
					})->orWhereHas('seller', function ($query) use ($request) {
						$query->where('name', 'like', '%' . $request->search . '%');
					});
				});
		} else {
			$this->query = $this->query->where('status', '!=', 0);
		}

		// Add the buttons
		$this->buttons = [
			[
				'type' => 'edit',
			],
		];

		return $this->_index($request);
	}

	/**
	 * * Edit action
	 */
	public function edit(int $id): View {
		$this->record = Cart::find($id);

		return $this->_edit();
	}

	/**
	 * * Update action
	 */
	public function update(Request $request, int $id) {
		// Find the record
		$this->record = Cart::find($id);

		// Update the cart's status
		if ($request->has('new_status')) {
			if ($request->new_status == 1) {
				$this->record->confirm();
			} else {
				$this->record->status = $request->new_status;
				$this->record->save();
			}
		}

		return redirect()->back();
	}
}
