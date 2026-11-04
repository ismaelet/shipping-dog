<?php

namespace App\Http\Controllers\Ecommerce\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartPromotionsController extends Controller
{
    /**
     * View the cart promotions page
     */
    public function index(): View|RedirectResponse
    {
        return $this->skip();

        return view('ecommerce.cart.promotions')
            ->with([
                'title' => 'Offerte',
            ]);
    }

    /**
     * Skip the promotions step
     */
    private function skip(): RedirectResponse
    {
        cart()->advance();

        return redirect()->route('cart.'.cart()->stepTag());
    }
}
