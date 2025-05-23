<?php

namespace App\Http\Controllers\UserDashboard;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Legacy\System\DB;
use App\Legacy\User\FeedEntry;
use App\Models\Ecommerce\Order;
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
		$loc = 'data';
		$error = $_SESSION['error'] ?? false;
		$done = $_SESSION['done'] ?? false;

		unset($_SESSION['error']);
		unset($_SESSION['done']);

		// updat data
		if (isset($_POST['edit_data'])) {

			$username = $_POST['username'];
			$email = trim($_POST['email']);
			$first_name = trim($_POST['first_name']);
			$last_name = trim($_POST['last_name']);

			if (\Legacy\System\Classes\LegacyDB::exists('users WHERE username = ? AND ID<>?', [$username, auth()->id()])) $_SESSION['error'] = 'user_match';
			else if (\Legacy\System\Classes\LegacyDB::exists('users WHERE email=? AND ID<>?', [$email, auth()->id()])) $_SESSION['error'] = 'email_match';
			else {
				\Legacy\System\Classes\LegacyDB::update('users SET username = ?, email=?, first_name = ?, last_name = ? WHERE id = ?', [$username, $email, $first_name, $last_name, auth()->id()]);

				$_SESSION['done'] = 'data';
			}

			redirect_old('#data-anch');
		}

		// update password
		if (isset($_POST['edit_pass'])) {

			$passOld = $_POST['passOld'];
			$passNew = $_POST['passNew'];
			$passConf = $_POST['passConf'];

			$db_password = \Legacy\System\Classes\LegacyDB::find('password FROM users WHERE id = ?', auth()->id());

			// update old MD5 passwords
			if ($db_password[0] != '$' && md5($passOld) == $db_password) {

				$db_password = password_hash($passOld, PASSWORD_DEFAULT);
				\Legacy\System\Classes\LegacyDB::update('users SET password=? WHERE id = ?', [$db_password, auth()->id()]);
			}

			if (!password_verify($passOld, $db_password)) $_SESSION['error'] = 'pass_bad';
			else if ($passNew != $passConf) $_SESSION['error'] = 'pass_match';
			else {

				$passNew = password_hash($passNew, PASSWORD_DEFAULT);

				\Legacy\System\Classes\LegacyDB::update('users SET password=? WHERE id = ?', [$passNew, auth()->id()]);

				$_SESSION['done'] = 'pass';
			}

			redirect_old('#pass-anch');
		}

		// change icon
		if (isset($_POST['edit_icon'])) {

			$file = '/uploads/users/icons/' . auth()->id() . '.jpg';

			upload_img_OLD($file, 'img', 150, 'crop');
		}

		// delete account
		if (isset($_POST['delete-account'])) {
			$user = User::find(auth()->id());
			$user->delete();

			auth()->logout();
			session()->flush();

			return redirect()->route('login');
		}

		return view('user-dashboard.settings', [
			'locationTag' => 'user-dashboard',
			'title' => 'Impostazioni',
			'done' => $done,
			'error' => $error,
		]);
	}
}
