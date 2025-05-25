<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PrivacyPolicyController extends Controller {

	/**
	 * Display the upload terms page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		$title = 'Privacy Policy';
		$locationTag = 'support';

		return view('support.privacy-policy', [
			'title' => $title,
			'locationTag' => $locationTag,
		]);
	}
}
