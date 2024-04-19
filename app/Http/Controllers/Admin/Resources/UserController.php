<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\ResourceController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends ResourceController {

	/**
	 * - Index action configuration
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
	 * - Edit action configuration
	 */
	protected string $titleField = 'username';

	/**
	 * * Index action
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
				'route' => 'users.login',
			],
			[
				'type' => 'edit',
			],
			[
				'type' => 'delete',
			],
		];

		return $this->_index($request);
	}

	/**
	 * * Edit action
	 */
	public function edit(int $id): View {
		$this->record = User::find($id);

		return $this->_edit();
	}

	/**
	 * * Update action
	 */
	public function update(Request $request, int $id) {
		// Find the record
		$this->record = User::find($id);

		// Validate the request
		$request->validate([
			'email' => ['required', 'email'],
		]);

		$this->record->update($request->only(['email', 'first_name', 'last_name']));

		return $this->_update($request);
	}

	/**
	 * * Login action
	 */
	public function login(int $id) {
		$user = User::findOrFail($id);

		auth()->login($user);

		return redirect('/profile');
	}
}
