<?php

namespace App\Http\Controllers\Ecommerce\Cart;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\PaymentMethod;
use App\Models\Warning;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CartPaymentController extends Controller
{
    /**
     * View the cart payment page
     */
    public function index(): View
    {

        $cart = cart();

        $paymentMethods = PaymentMethod::allowedFor($cart)->get();
        abort_if($paymentMethods->isEmpty(), 503);
        if (! $paymentMethods->contains('id', $cart->payment_method_id)) {
            $cart->update(['payment_method_id' => $paymentMethods->first()->id]);
        }

        $warning = Warning::currentWarning();

        return view('ecommerce.cart.payment')
            ->with([
                'title' => 'Pagamento',
                'paymentMethods' => $paymentMethods,
                'warning' => $warning,
            ]);
    }

    /**
     * Update the cart payment method
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_method_id' => ['required', 'integer', Rule::in(PaymentMethod::allowedFor(cart())->pluck('id')->all())],
        ]);

        $cart = cart();
        $cart->update($request->only('payment_method_id'));

        $paymentMethodId = $cart->payment_method_id;

        if (in_array((int) $paymentMethodId, [1, 2], true)) {
            return redirect($cart->paymentUrl());
        } elseif (in_array($paymentMethodId, [3, 4])) {
            $token = Str::random(20);

            $cart->update([
                'token' => $token,
            ]);

            return redirect()->route('cart.payment.free', [
                'token' => $token,
            ]);
        }

        return back()->with([
            'message' => 'Metodo di pagamento non valido.',
            'alert-type' => 'error',
        ]);
    }
}
