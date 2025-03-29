<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Admin\ResourceController;
use App\Mail\OrderStatusMail;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends ResourceController {

	/**
	 * ~ Action Configuration: Index
	 */
	protected array $columns = [
		['label' => 'ID', 'name' => 'id'],
		['label' => 'Destinatario', 'name' => 'full_name'],
		['label' => 'Articoli', 'type' =>  'method', 'method' => 'orderItemsString',],
		// ['label' => 'Spedizione', 'name' => 'shipping_method_name'],
		['label' => 'Totale', 'name' => 'price', 'type' => 'price',],
		['label' => 'Account', 'name' => 'user_id', 'type' => 'bool'],
		['label' => 'Stato', 'name' => 'status', 'type' => 'method', 'method' => 'statusLabel',],
		['label' => 'Data', 'name' => 'created_at', 'type' => 'date'],
	];

	protected array $orderItemBy = ['created_at' => 'desc'];

	/**
	 * ~ Action Configuration: Edit
	 */
	protected string $recordClass = Order::class;
	protected string $titleField = 'id';

	/**
	 * * Action: Index
	 */
	public function index(Request $request): View {
		// Prepare the query
		$this->query = Order::leftJoin('sys_shipping', 'orders.shipping_method_id', '=', 'sys_shipping.id')
			->leftJoin('addresses', 'orders.id', '=', 'addresses.order_id')
			->select('orders.*', 'sys_shipping.name as shipping_method_name')
			->with('order_items.color', 'order_items.size', 'order_items.position', 'order_items.logo', 'order_items.product', 'order_items.product.type', 'order_items.product.type.colors', 'order_items.product.type.sizes', 'address');

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
			$this->query = $this->query->where('status', '!=', EcommerceHelper::statusFromTag('open'));
		}

		// Add the buttons
		$this->buttons = [
			[
				'label' => 'Reinvia email',
				'icon' => 'send-email',
				'color' => 'primary',
				'route' => 'legacy-admin.orders.resend-email',
				'method' => 'POST',
			],
			[
				'type' => 'edit',
			],
		];

		return parent::index($request);
	}

	/**
	 * * Action: Edit
	 */
	public function edit(int $orderId): View {
		Order::findOrFail($orderId);

		return parent::edit($orderId);
	}

	/**
	 * * Action: Update
	 */
	public function update(int $orderId, Request $request): RedirectResponse {
		// Find the record
		$order = Order::findOrFail($orderId);

		// Update the cart's status
		if ($request->has('new_status')) {
			if ($request->new_status == EcommerceHelper::statusFromTag('confirmed')) {
				$order->confirm(false, true);
			} else if ($request->new_status == EcommerceHelper::statusFromTag('cancelled')) {
				$order->cancel();
			} else {
				$order->update([
					'status' => $request->new_status
				]);
			}
		}

		if ($request->has('attach_to_current_user')) {
			Auth::user()->cart?->update(['status' => EcommerceHelper::statusFromTag('received')]);

			$order->update(['user_id' => Auth::id()]);
		}

		if ($request->has('detach_from_current_user')) {
			$order->update(['user_id' => null]);
		}

		return redirect()->back();
	}

	/**
	 * * Custom Action: Resend Email
	 */
	public function resendEmail(int $id): RedirectResponse {
		$order = Order::findOrFail($id);

		$result = Mail::to($order->address->email)->send(new OrderStatusMail($order));

		if ($result) {
			return redirect()->back()->with('status', 'Email inviata correttamente');
		} else {
			return redirect()->back()->with('error', 'Errore durante l\'invio dell\'email');
		}
	}
}
