<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleTermsController extends Controller {

    /**
     * Display the sale terms page.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View {
        $title = 'Condizioni di vendita';
        $location = 'support';

        return view('support.sale-terms', [
            'title' => $title,
            'location' => $location,
        ]);
    }
}
