<?php

namespace App\Services\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Auth;

class CartResolver
{
    /**
     * Get the active cart.
     */
    public function cart(): ?Order
    {
        if (Auth::check()) {
            return Auth::user()->cart;
        }

        $openStatus = EcommerceHelper::statusFromTag('open');

        if ($openStatus === null) {
            return null;
        }

        return Order::where('id', session('cart_id'))
            ->where('status', $openStatus)
            ->first();
    }

    /**
     * Get the ID of the active cart.
     */
    public function cartId(): ?int
    {
        return $this->cart()?->id;
    }
}
