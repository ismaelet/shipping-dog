<?php

namespace App\Http\Middleware;

use App\Helpers\EcommerceHelper;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class Cart
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $pageStepTag = explode('.', Route::currentRouteName())[1] ?? null;
        $pageStep = $pageStepTag ? EcommerceHelper::stepFromTag($pageStepTag) : null;
        $orderStepTag = cart()?->stepTag();
        $orderStep = cart()?->step;

        // If the cart is empty, redirect to the empty cart page
        if (! cart()) {
            return redirect()->route('cart.empty');
        }

        // If the step isn't valid redirect to the first step
        if (! $orderStep) {
            $firstStep = reset(config('ecommerce.cart.steps'));

            return redirect()->route('cart.'.$firstStep);
        }

        // If the page step isn't set or is more advanced than the cart step, redirect to the cart step
        if (! $pageStep || $pageStep > $orderStep) {
            return redirect()->route('cart.'.$orderStepTag);
        }

        return $next($request);
    }
}
