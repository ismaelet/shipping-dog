<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
    /**
     * Display a listing of the sales for the seller
     */
    public function index(Request $request): View
    {
        return view('seller-dashboard.sales.index', ['seller' => $request->seller]);
    }
}
