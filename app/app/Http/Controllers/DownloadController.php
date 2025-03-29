<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller {

	public function download($filename) {
		// Check if the file exists
		if (Storage::exists('public/' . $filename)) {
			// Download the file
			return response()->download(public_storage($filename));
		} else {
			// File not found
			return abort(404);
		}
	}
}
