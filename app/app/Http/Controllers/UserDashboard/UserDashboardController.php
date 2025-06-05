<?php

namespace App\Http\Controllers\UserDashboard;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Legacy\System\DB;
use App\Legacy\User\FeedEntry;
use App\Models\Ecommerce\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserDashboardController extends Controller {

	/**
	 * Display the user dashboard
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		$title = 'Attività recenti';

		$feedEntries = [];
		$feedEntryRows = DB::select(
			'
	order_items.id as event_id, 2 AS type, orders.created_at
		FROM order_items INNER JOIN orders ON order_items.order_id = orders.id WHERE seller_id = ? AND orders.status > 1
	UNION ALL
	SELECT id as event_id, 1 AS type, created_at
		FROM news
	ORDER BY created_at DESC
	LIMIT 15
	',
			[Auth::id()]
		);

		while ($feedEntryRow = $feedEntryRows->fetch_assoc()) {
			$feedEntries[] = new FeedEntry($feedEntryRow);
		}

		return view('user-dashboard.recap', [
			'locationTag' => 'user-dashboard',
			'title' => $title,
			'feedEntries' => $feedEntries,
		]);
	}

	/**
	 * Display the orders tab
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function orders(Request $request): View {
		$title = 'Ordini';

		$user = Auth::user();
		$pageSize = 10;
		$page = request()->query('page', 1);

		$orderBy = request()->query('order_by', 'id');
		$orderDirection = request()->query('order_direction', 'desc');

		$confirmedOrders = Order::where('user_id', $user->id)
			->where('status', '!=', EcommerceHelper::statusFromTag('open'))
			->with(
				'order_items',
				'order_items.type',
				'order_items.type.sizes',
				'order_items.type.colors',
				'order_items.color',
				'order_items.size',
				'order_items.position',
				'order_items.logo',
				'order_items.product',
				'order_items.product.type',
				'order_items.product_with_trashed',
			);
		$confirmedOrders =
			$orderDirection == 'asc' ? $confirmedOrders->orderBy($orderBy) : $confirmedOrders->orderByDesc($orderBy);

		$confirmedOrders = $confirmedOrders->get();

		$confirmedOrders = $confirmedOrders->forPage($page, $pageSize);

		$columns = ['Ordine', 'Prodotti', 'Totale', 'Stato', 'Data', ''];
		$columns = [
			['label' => 'Ordine', 'name' => 'id', 'type' => 'method', 'method' => 'idCode'],
			['label' => 'Articoli', 'type' => 'method', 'method' => 'orderItemsString'],
			['label' => 'Totale', 'name' => 'price', 'type' => 'price'],
			['label' => 'Stato', 'name' => 'status', 'type' => 'method', 'method' => 'statusLabel'],
			['label' => 'Data', 'name' => 'created_at', 'type' => 'date'],
		];

		return view('user-dashboard.orders', [
			'locationTag' => 'user-dashboard',
			'title' => $title,
			'confirmedOrders' => $confirmedOrders,
			'columns' => $columns,
		]);
	}

	/**
	 * Display the settings tab
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function settings(Request $request): View {
		return view('user-dashboard.settings', [
			'locationTag' => 'user-dashboard',
			'title' => 'Impostazioni',
		]);
	}
}
