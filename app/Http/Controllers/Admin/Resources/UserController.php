<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\ResourceController;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends ResourceController {

	/**
	 * ~ Action Configuration: Index
	 */
	protected array $columns = [
		['label' => 'ID', 'name' => 'id'],
		['label' => 'Nome', 'name' => 'first_name'],
		['label' => 'Cognome', 'name' => 'last_name'],
		['label' => 'Negozio', 'name' => 'seller_name'],
		['label' => 'E-mail', 'name' => 'email',],
		['label' => 'Registrazione', 'name' => 'created_at', 'type' => 'date'],
	];

	/**
	 * ~ Action Configuration: Edit
	 */
	protected string $recordClass = User::class;
	protected string $titleField = 'username';

	/**
	 * * Action: Index
	 */
	public function index(Request $request): View {
		// Prepare the query
		$this->query = User::leftJoin('stores', 'stores.user_id', '=', 'users.id')
			->select('users.*', 'stores.name as seller_name');

		// Add search filter
		if ($request->search) {
			$this->query = $this->query->where('users.id', $request->search)
				->orWhere('first_name', 'like', '%' . $request->search . '%')
				->orWhere('last_name', 'like', '%' . $request->search . '%')
				->orWhere('stores.name', 'like', '%' . $request->search . '%')
				->orWhere('email', 'like', '%' . $request->search . '%');
		}

		// Add the buttons
		$this->buttons = [
			[
				'label' => 'Login',
				'icon' => 'login',
				'color' => 'green',
				'route' => 'admin.users.login',
			],
			[
				'type' => 'edit',
			],
			[
				'type' => 'delete',
			],
		];

		return parent::index($request);
	}

	/**
	 * * Action: Edit
	 */
	public function edit(int $userId): View {
		User::findOrFail($userId);

		return parent::edit($userId);
	}

	/**
	 * * Action: Update
	 */
	public function update(int $userId, Request $request): RedirectResponse {
		// Find the record
		$user = User::findOrFail($userId);

		// Validate the request
		$request->validate([
			'first_name' => ['string', 'max:255'],
			'last_name' => ['string', 'max:255'],
			'email' => ['required', 'email'],
		]);

		$user->update($request->only(['email', 'first_name', 'last_name']));

		return parent::update($userId, $request);
	}

	/**
	 * * Custom Action: Login
	 */
	public function login(int $id): RedirectResponse {
		$user = User::findOrFail($id);

		auth()->login($user);

		return redirect('/profile');
	}
}
