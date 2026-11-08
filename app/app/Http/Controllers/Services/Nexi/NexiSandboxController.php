<?php

namespace App\Http\Controllers\Services\Nexi;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Services\Payments\NexiSandbox;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class NexiSandboxController extends Controller
{
    public function guest(Request $request, Order $order, NexiSandbox $sandbox): RedirectResponse
    {
        try {
            $sandbox->assertIsolated();
        } catch (RuntimeException) {
            abort(404);
        }
        abort_unless($request->hasValidSignature(), 403);
        abort_unless(! $request->user() && ! $order->user_id && (int) $order->status === 0
            && (int) $order->payment_method_id === 2, 409);
        $request->session()->put('cart_id', $order->id);

        return redirect()->route('nexi.orders.create', $order)->withHeaders([
            'Cache-Control' => 'private, no-store', 'Referrer-Policy' => 'no-referrer',
        ]);
    }
}
