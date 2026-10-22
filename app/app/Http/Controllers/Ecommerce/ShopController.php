<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ShopController extends Controller {

    /**
     * Display the shop page
     */
    public function index(): View {
        return view('ecommerce.shop', [
            'title' => 'Negozio',
            'location' => 'shop',
        ]);
    }
}
