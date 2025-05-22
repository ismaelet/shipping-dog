<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactsController extends Controller {

	/**
	 * Display the contacts page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		return view('contacts', [
			'locationTag' => 'contacts',
		]);
	}
}
