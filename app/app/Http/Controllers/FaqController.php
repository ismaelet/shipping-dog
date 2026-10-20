<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller {

    /**
     * Display the FAQ page.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View {
        $title = 'FAQ';
        $location = 'support';

        return view('support.faq', [
            'title' => $title,
            'location' => $location,
        ]);
    }
}
