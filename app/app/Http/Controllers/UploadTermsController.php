<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class UploadTermsController extends Controller
{
    /**
     * Display the upload terms page.
     */
    public function index(Request $request): View
    {
        $title = 'Condizioni di invio';
        $location = 'support';

        return view('support.upload-terms', [
            'title' => $title,
            'location' => $location,
        ]);
    }
}
