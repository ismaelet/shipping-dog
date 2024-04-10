<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Admin\AdminFormController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserFormController extends AdminFormController {

	/**
	 * Display the form
	 */
	public function index(Request $request, ?int $id = null): View {
		if ($id) {
			$this->record = User::find($id);
			$this->title = '<span class="font-light">Modifica:</span> ' . $this->record->username;
		}

		return $this->view();
	}

	/**
	 * Update the form
	 */
	public function update() {
		return redirect()->route('admin.users.list');
	}
}
