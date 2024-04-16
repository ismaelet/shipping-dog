<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\ResourceController;
use App\Models\Newsletter\Subscriber;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterSubscriberController extends ResourceController {

	/**
	 * Index action configuration
	 */
	protected array $columns = [
		['label' => 'ID', 'name' => 'id'],
		['label' => 'E-mail', 'name' => 'email'],
		['label' => 'Nome', 'name' => 'first_name'],
		['label' => 'Cognome', 'name' => 'last_name'],
		['label' => 'Ha un account', 'name' => 'user_id', 'type' => 'bool'],
		['label' => 'Iscritto/a il', 'name' => 'created_at', 'type' => 'date'],
	];

	/**
	 * Index
	 */
	public function index(Request $request): View {
		// Prepare the query
		$this->query = Subscriber::leftJoin('users', 'users.email', '=', 'newsletter_subscribers.email')
			->select('newsletter_subscribers.id', 'newsletter_subscribers.email', 'newsletter_subscribers.created_at', 'users.first_name', 'users.last_name', 'users.id as user_id');

		// Add search filter
		if ($request->search) {
			$this->query = $this->query->where('newsletter_subscribers.id', $request->search)
				->orWhere('newsletter_subscribers.email', 'like', '%' . $request->search . '%')
				->orWhere('users.first_name', 'like', '%' . $request->search . '%')
				->orWhere('users.last_name', 'like', '%' . $request->search . '%');
		}

		// Add the buttons
		$this->buttons = [
			[
				'label' => 'Elimina',
				'icon' => 'delete',
				'name' => 'delete',
				'color' => 'red',
				'confirmation' => true,
			],
		];

		return $this->_index($request);
	}
}
