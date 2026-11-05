<?php

namespace App\Http\Controllers\Services\Nexi;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Models\NexiPayment;
use App\Services\Payments\NexiService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NexiController extends Controller
{
    public function __construct(
        private readonly NexiService $nexi
    ) {}

    /**
     * Initialize a Nexi payment for an existing order.
     */
    public function create(Request $request, Order $order): View
    {
        // Only the customer who owns the current cart
        // session can initiate its payment.
        abort_unless(
            (string) $request->session()->get('cart_id')
                === (string) $order->id,
            403
        );

        // Nexi is payment method ID 2.
        abort_unless(
            (int) $order->payment_method_id === 2,
            422
        );

        // An already confirmed order cannot be paid again.
        abort_if(
            $order->status >= EcommerceHelper::statusFromTag('confirmed'),
            409
        );

        // Convert the database DECIMAL amount to cents
        // without using floating-point arithmetic.
        $price = (string) $order->price;

        abort_unless(
            preg_match('/^\d{1,6}(\.\d{1,2})?$/', $price),
            422
        );

        [$euros, $cents] = array_pad(
            explode('.', $price, 2),
            2,
            '0'
        );

        $amount = (int) $euros * 100
            + (int) str_pad($cents, 2, '0');

        abort_if($amount <= 0, 422);

        // Generate a unique payment reference.
        $transactionId = 'SD'.Str::upper(Str::random(20));

        // Store the pending payment.
        $payment = NexiPayment::create([
            'order_id' => $order->id,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'currency' => 'EUR',
            'status' => 'pending',
            'return_token' => Str::random(64),
        ]);

        // Generate the signed Nexi payment parameters.
        $parameters = $this->nexi->paymentParameters(
            transactionId: $payment->transaction_id,
            amountInCents: (int) $payment->amount,
            returnUrl: route('nexi.orders.result', [
                'order' => $order->id,
                'return_token' => $payment->return_token,
            ]),
            cancelUrl: route('nexi.orders.cancel', [
                'order' => $order->id,
                'return_token' => $payment->return_token,
            ]),
            notificationUrl: route('nexi.orders.notification'),
        );

        return view('ecommerce.cart.nexi-redirect', [
            'paymentUrl' => $this->nexi->paymentUrl(),
            'parameters' => $parameters,
        ]);
    }

    /**
     * Handle the server-to-server notification from Nexi.
     */
    public function notification(Request $request): Response
    {
        $data = $request->all();

        // Verify the signature before trusting any payment data.
        if (! $this->nexi->verifyNotification($data)) {
            Log::warning('Nexi notification: invalid MAC');

            return response('Invalid signature', 403);
        }

        $transactionId = $data['codTrans'];

        try {
            return DB::transaction(function () use ($data, $transactionId) {
                $payment = NexiPayment::where(
                    'transaction_id',
                    $transactionId
                )->lockForUpdate()->first();

                if (! $payment) {
                    return response('Payment not found', 404);
                }

                // Prevent mismatched amounts and currencies.
                if (
                    ! ctype_digit($data['importo'])
                    || (int) $data['importo'] !== (int) $payment->amount
                    || $data['divisa'] !== $payment->currency
                ) {
                    Log::error('Nexi payment mismatch', [
                        'payment_id' => $payment->id,
                    ]);

                    return response('Payment mismatch', 422);
                }

                $order = Order::whereKey($payment->order_id)
                    ->lockForUpdate()
                    ->first();

                if (
                    ! $order
                    || (int) $order->payment_method_id !== 2
                ) {
                    return response('Invalid order', 422);
                }

                // Avoid acknowledging a payment for a modified
                // order with a different total.
                $expectedAmount = (int) round(
                    (float) $order->price * 100
                );

                if ($expectedAmount !== (int) $payment->amount) {
                    return response('Order amount mismatch', 422);
                }

                // Successful payment.
                if ($data['esito'] === 'OK') {
                    if ($payment->status === 'paid') {
                        return response('OK', 200);
                    }

                    if ($payment->status !== 'pending') {
                        return response('Invalid payment state', 409);
                    }

                    if (! in_array((int) $order->status, [
                        EcommerceHelper::statusFromTag('open'),
                        EcommerceHelper::statusFromTag('received'),
                    ], true)) {
                        return response('Invalid order state', 409);
                    }

                    $payment->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    Log::info('Nexi payment verified', [
                        'order_id' => $order->id,
                        'payment_id' => $payment->id,
                    ]);

                    return response('OK', 200);
                }

                // Store an unsuccessful outcome without changing
                // an already successful payment.
                if ($payment->status === 'paid') {
                    return response('OK', 200);
                }

                if ($payment->status === 'pending') {
                    $payment->update([
                        'status' => 'failed',
                    ]);
                }

                return response('OK', 200);
            }, 3);

        } catch (\Throwable $e) {
            Log::error('Nexi notification processing failed', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            return response('Processing error', 500);
        }
    }

    /**
     * Customer returns from Nexi after payment.
     */
    public function result(
        Request $request,
        Order $order
    ): RedirectResponse {
        $this->authorizeReturn($request, $order);

        // The notification handler determines payment success.
        // Never confirm an order from this browser redirect.
        return redirect($order->recapUrlWithToken());
    }

    /**
     * Customer returns after cancelling payment.
     */
    public function cancel(
        Request $request,
        Order $order
    ): RedirectResponse {
        $this->authorizeReturn($request, $order);

        // Don't cancel the order here: a successful payment
        // notification might already have arrived.
        return redirect($order->recapUrlWithToken());
    }

    /**
     * Verify that the return belongs to a Nexi payment
     * associated with this order.
     */
    private function authorizeReturn(
        Request $request,
        Order $order
    ): void {
        $token = $request->query('return_token');

        abort_unless(
            is_string($token) && strlen($token) === 64,
            403
        );

        $payment = NexiPayment::query()
            ->where('order_id', $order->id)
            ->where('return_token', $token)
            ->first();

        abort_unless($payment, 403);
    }
}
