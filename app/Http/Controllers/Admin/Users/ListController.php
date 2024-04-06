<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ListController extends Controller {

	/**
	 * Display the list of users
	 */
	public function index(): View {
		return view('components.templates.admin-list')
			->with();
	}
}
