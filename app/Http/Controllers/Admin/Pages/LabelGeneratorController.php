<?php

namespace App\Http\Controllers\Admin\Pages;

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
		];

		// Get the orders to be generated
		$orders = Order::unlabelled();

		// Get the order count
		$orderCount = $orders->count();

		return view('admin.labels.generate', compact('orders', 'orderCount', 'columns'));
	}
}
