<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class UploadTermsController extends Controller {

	/**
     * Display the upload terms page.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View {
		$title = 'Condizioni di invio';
		$locationTag = 'support';

		return view('support.upload-terms', [
			'title' => $title,
			'locationTag' => $locationTag,
		]);
	}
}
