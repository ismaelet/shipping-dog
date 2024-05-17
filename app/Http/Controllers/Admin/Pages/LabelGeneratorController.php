<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use Illuminate\View\View;

class LabelGeneratorController extends Controller {

	/**
	 * * Action: Index
	 */
	public function index(): View {
		$columns = [
			['label' => 'Da Spedire', 'type' => 'checkbox', 'name' => 'id', 'checked' => true],
			['label' => 'ID', 'name' => 'id'],
			['label' => 'Destinatario', 'name' => 'full_name'],
			['label' => 'Data', 'name' => 'created_at', 'type' => 'date'],
			[],
		];

		$buttons = [
			[
				'label' => 'Elimina l\'ordine',
				'icon' => 'delete',
				'color' => 'red',
				'route' => 'orders.update',
				'name' => 'status',
				'value' => EcommerceHelper::statusFromTag('label-ready'),
				'method' => 'PATCH',
			],
		];

		// Get the orders to be generated
		$orders = Order::unlabelled();

		// Get the order count
		$orderCount = $orders->count();

		return view('admin.labels.generate', compact('orders', 'orderCount', 'columns', 'buttons'));
	}
}
