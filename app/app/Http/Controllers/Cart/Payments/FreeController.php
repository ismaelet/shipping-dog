<?php

namespace App\Http\Controllers\Cart\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FreeController extends Controller
{
    /**
     * Process the free payment method and confirm the order
     */
    public function process(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $order = cart();
        $token = $request->input('token');

        if ($order->status || $order->token !== $token) {
            return back()->withErrors([
                'message' => "C'è stato un errore durante il pagamento. Riprova a breve.",
            ]);
        }

        $order->confirm();

        return redirect()->to($order->recapUrl());
    }
}
