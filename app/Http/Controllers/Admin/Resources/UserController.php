<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\ResourceController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends ResourceController {

	/**
	 * Index action configuration
	 */
	protected array $columns = [
		['label' => 'ID', 'name' => 'id'],
		['label' => 'Nome', 'name' => 'first_name'],
		['label' => 'Cognome', 'name' => 'last_name'],
		['label' => 'E-mail', 'name' => 'email',],
		['label' => 'Registrazione', 'name' => 'created_at', 'type' => 'date'],
	];

	/**
	 * Edit action configuration
	 */
	protected string $titleField = 'email';

	/**
	 * Index action
	 */
	public function index(Request $request): View {
		// Prepare the query
		$this->query = User::select('*');

		// Add search filter
		if ($request->search) {
			$this->query = $this->query->where('id', $request->search)
				->orWhere('email', 'like', '%' . $request->search . '%');
		}

		// Add the buttons
		$this->buttons = [
			[
				'label' => 'Login',
				'icon' => 'login',
				'name' => 'login',
				'color' => 'green',
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
	 * Edit action
	 */
	public function edit(int $id): View {
		$this->record = User::find($id);

		return $this->_edit();
	}
}
