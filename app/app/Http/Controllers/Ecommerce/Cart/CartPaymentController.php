<?php

namespace App\Http\Controllers\Ecommerce\Cart;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\PaymentMethod;
use App\Models\Warning;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CartPaymentController extends Controller
{
    /**
     * View the cart payment page
     */
    public function index(): View
    {

        $cart = cart();

        // setup cart
        if ($cart->price == 0) {
            $allowedPaymentMethods = [4];
            $paymentMethods = PaymentMethod::whereIn('id', $allowedPaymentMethods)->get();

            if (! in_array($cart->payment_method_id, $allowedPaymentMethods)) {
                $cart->update([
                    'payment_method_id' => $paymentMethods->first()->id,
                ]);
            }
        } elseif ($cart->shipping_method->isLocalDelivery()) {
            $allowedPaymentMethods = [1, 3];
            $paymentMethods = PaymentMethod::whereIn('id', $allowedPaymentMethods)->get();

            if (! in_array($cart->payment_method_id, $allowedPaymentMethods)) {
                $cart->update([
                    'payment_method_id' => $paymentMethods->first()->id,
                ]);
            }
        } else {
            $paymentMethods = PaymentMethod::where('active', 1)->get();

            if (! $cart->payment_method_id || ! $paymentMethods->contains('id', $cart->payment_method_id)) {
                $cart->update([
                    'payment_method_id' => $paymentMethods->first()->id,
                ]);
            }
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
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $cart = cart();
        $cart->update($request->only('payment_method_id'));

        $paymentMethodId = $cart->payment_method_id;

        if ($paymentMethodId == 1) {
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
