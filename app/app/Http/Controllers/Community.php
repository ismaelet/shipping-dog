<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class Community extends Controller
{
    /**
     * Display the community page.
     */
    public function index(Request $request): View
    {
        return view('community', []);
    }
}
