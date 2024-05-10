<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Admin\ResourceController;
use App\Mail\OrderStatusMail;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends ResourceController {

	// ~ Index action configuration ~ //
	/**
	 * The columns array for the resource.
	 *
	 * @var array
	 */
	protected array $columns = [
		['label' => 'ID', 'name' => 'id'],
		['label' => 'Destinatario', 'name' => 'full_name', 'type' =>  'method', 'method' => 'address.fullAddress'],
		['label' => 'Articoli', 'type' =>  'method', 'method' => 'orderItemsString',],
		// ['label' => 'Spedizione', 'name' => 'shipping_method_name'],
		['label' => 'Totale', 'name' => 'price', 'type' => 'price',],
		['label' => 'Registrato', 'name' => 'user_id', 'type' => 'bool'],
		['label' => 'Stato', 'name' => 'status', 'type' => 'method', 'method' => 'statusLabel',],
		['label' => 'Data', 'name' => 'created_at', 'type' => 'date'],
	];

	/**
	 * The default order by array for the resource.
	 *
	 * @var array
	 */
	protected array $orderItemBy = ['created_at' => 'desc'];


	// ~ Edit action configuration ~ //
	/**
	 * The field used as the title for the resource.
	 *
	 * @var string
	 */
	protected string $titleField = 'id';


	// * Index action * //
	/**
	 * Display a listing of the resource.
	 *
	 * @param \Illuminate\Http\Request  $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index(Request $request): View {
		// Prepare the query
		$this->query = Order::leftJoin('sys_shipping', 'orders.shipping_method_id', '=', 'sys_shipping.id')
			->leftJoin('addresses', 'orders.id', '=', 'addresses.order_id')
			->select('orders.*', 'sys_shipping.name as shipping_method_name');

		// Add search filter
		if ($request->search) {
			$filter = $request->search;

			$this->query = $this->query->where('orders.id', $filter)
				->orWhere('full_name', 'like', '%' . $filter . '%')
				->orWhere('first_name', 'like', '%' . $filter . '%')
				->orWhere('last_name', 'like', '%' . $filter . '%')
				->orWhere('email', 'like', '%' . $filter . '%')
				->orWhere('city', 'like', '%' . $filter . '%')
				->orWhere('email', 'like', '%' . $filter . '%')
				->orWhereHas('order_items', function ($query) use ($filter) {
					$query->where('id', $filter)
						->orWhere(function ($query) use ($filter) {
							$query->whereHas('product', function ($query) use ($filter) {
								$query->where('name', 'like', '%' . $filter . '%');
							})->orWhereHas('seller', function ($query) use ($filter) {
								$query->where('name', 'like', '%' . $filter . '%');
							});
						});
				});
		} else {
			$this->query = $this->query->where('status', '!=', 0);
		}

		// Add the buttons
		$this->buttons = [
			[
				'label' => 'Reinvia email',
				'icon' => 'send-email',
				'color' => 'primary',
				'route' => 'orders.resend-email',
			],
			[
				'type' => 'edit',
			],
		];

		return $this->_index($request);
	}


	// * Edit action * //
	/**
	 * Edit a resource record.
	 *
	 * @param int $id The ID of the resource record to edit.
	 * @return \Illuminate\View\View The view for editing the resource record.
	 */
	public function edit(int $id): View {
		$this->record = Order::find($id);

		return $this->_edit();
	}


	// * Update action * //
	/**
	 * Update the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request, int $id) {
		// Find the record
		$this->record = Order::find($id);

		// Update the cart's status
		if ($request->has('new_status')) {
			if ($request->new_status == EcommerceHelper::statusFromTag('confirmed')) {
				$this->record->confirm();
			} else if ($request->new_status == EcommerceHelper::statusFromTag('canceled')) {
				$this->record->cancel();
			} else {
				$this->record->status = $request->new_status;
				$this->record->save();
			}
		}

		return redirect()->back();
	}


	// * Custom actions * //
	/**
	 * Resend the email for a specific cart.
	 *
	 * @param int $id The ID of the cart.
	 * @return \Illuminate\Http\RedirectResponse The redirect response.
	 */
	public function resendEmail(int $id): RedirectResponse {
		$order = Order::find($id);

		$result = Mail::to($order->address->email)->send(new OrderStatusMail($order));

		if ($result) {
			return redirect()->back()->with('status', 'Email inviata correttamente');
		} else {
			return redirect()->back()->with('error', 'Errore durante l\'invio dell\'email');
		}
	}
}
