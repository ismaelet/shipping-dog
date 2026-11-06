<?php

namespace App\Http\Controllers\Services\Nexi;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\PaymentMethod;
use App\Models\NexiPayment;
use App\Services\Payments\NexiOrderSnapshot;
use App\Services\Payments\NexiPaymentReconciliation;
use App\Services\Payments\NexiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class NexiController extends Controller
{
    public function __construct(private readonly NexiService $nexi) {}

    public function create(Request $request, Order $order): Response
    {
        $this->authorizeCustomer($request, $order);
        abort_unless(in_array((int) $order->status, [0, 1], true), 409);
        abort_unless((int) $order->payment_method_id === 2 && PaymentMethod::whereKey(2)->where('active', 1)->exists(), 422);

        return $this->paymentView('ecommerce.cart.nexi-start', compact('order'));
    }

    public function store(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeCustomer($request, $order);

        try {
            $this->nexi->assertConfigured();
            [$payment, $initialize] = DB::transaction(function () use ($order) {
                $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
                abort_unless(in_array((int) $order->status, [0, 1], true), 409);
                abort_unless((int) $order->payment_method_id === 2 && PaymentMethod::whereKey(2)->where('active', 1)->exists(), 422);
                abort_if($order->isEmpty() || ! $order->address, 422);

                $amount = $this->nexi->amountInCents((string) $order->getRawOriginal('price'));
                $payment = $order->nexiPayments()->latest('id')->first();

                if ($payment) {
                    abort_unless($payment->status === 'pending', 409, 'Il tentativo precedente richiede verifica prima di un nuovo pagamento.');
                    abort_unless($payment->amount === $amount && $payment->order_fingerprint
                        && hash_equals($payment->order_fingerprint, app(NexiOrderSnapshot::class)->fingerprint($order)), 409);

                    if ($payment->initialization_status === 'ready') {
                        abort_unless($payment->hosted_page_url && $payment->security_token, 409);

                        return [$payment, false];
                    }

                    abort_unless($payment->initialization_status === 'not_started', 409, 'L\'inizializzazione richiede verifica prima di essere ripetuta.');
                    $payment->update(['initialization_status' => 'creating']);

                    return [$payment, true];
                }

                $order->update([
                    'status' => EcommerceHelper::statusFromTag('received'),
                    'token' => Str::random(64),
                ]);
                $payment = $order->nexiPayments()->create([
                    'transaction_id' => 'SD'.Str::upper(Str::random(16)),
                    'amount' => $amount,
                    'currency' => 'EUR',
                    'status' => 'pending',
                    'initialization_status' => 'creating',
                    'return_token' => Str::random(64),
                    'order_fingerprint' => app(NexiOrderSnapshot::class)->fingerprint($order->fresh()),
                ]);

                return [$payment, true];
            }, 3);
        } catch (\InvalidArgumentException $exception) {
            Log::error('Nexi initialization configuration or amount invalid', ['order_id' => $order->id]);
            abort(503, 'Pagamento temporaneamente non disponibile.');
        } catch (HttpExceptionInterface $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            Log::error('Nexi initialization failed', ['order_id' => $order->id, 'exception' => $exception::class]);
            abort(503, 'Pagamento temporaneamente non disponibile.');
        }

        $request->session()->put('nexi_order_id', $payment->order_id);
        $request->session()->put('nexi_recaps.'.$payment->order_id, true);
        if ((string) $request->session()->get('cart_id') === (string) $payment->order_id) {
            $request->session()->forget('cart_id');
        }

        if ($initialize) {
            try {
                $session = $this->nexi->createHostedPage(
                    $payment->transaction_id,
                    $payment->amount,
                    route('nexi.orders.result', ['order' => $payment->order_id, 'return_token' => $payment->return_token]),
                    route('nexi.orders.cancel', ['order' => $payment->order_id, 'return_token' => $payment->return_token]),
                    route('nexi.orders.notification'),
                );

                DB::transaction(function () use ($payment, $session) {
                    $locked = NexiPayment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
                    abort_unless($locked->initialization_status === 'creating', 409);
                    $locked->update([
                        ...$session,
                        'initialization_status' => 'ready',
                        'initialized_at' => now(),
                    ]);
                }, 3);
                $payment = $payment->fresh();
            } catch (Throwable $exception) {
                NexiPayment::whereKey($payment->id)->where('initialization_status', 'creating')
                    ->update(['initialization_status' => 'review']);
                Log::error('Nexi initialization outcome requires reconciliation', [
                    'payment_id' => $payment->id,
                    'exception' => $exception::class,
                ]);
                abort(503, 'Pagamento temporaneamente non disponibile. Contatta l\'assistenza prima di riprovare.');
            }
        }

        return redirect()->away($payment->hosted_page_url)->withHeaders([
            'Cache-Control' => 'private, no-store',
            'Referrer-Policy' => 'no-referrer',
        ]);
    }

    public function notification(Request $request): Response
    {
        if (! $request->isJson()) {
            return response('JSON notification required', 415);
        }

        $data = $request->json()->all();
        $orderId = $data['orderId'] ?? null;
        $securityToken = $data['securityToken'] ?? null;
        if (! is_string($orderId) || ! is_string($securityToken) || $securityToken === '') {
            return response('Invalid notification', 403);
        }

        $payment = NexiPayment::where('provider_order_id', $orderId)->first();
        if (! $payment) {
            return response('Payment not found', 404);
        }
        if (! is_string($payment->security_token) || ! hash_equals($payment->security_token, $securityToken)) {
            return response('Invalid notification', 403);
        }

        try {
            abort_unless($payment->currency === 'EUR', 422);
            app(NexiPaymentReconciliation::class)->reconcile($payment->id);
        } catch (Throwable $exception) {
            Log::error('Nexi notification verification failed', [
                'payment_id' => $payment->id,
                'exception' => $exception::class,
            ]);

            return response('Verification error', 503);
        }

        return response('OK');
    }

    public function result(Request $request, Order $order): Response
    {
        $token = $request->query('return_token');
        abort_unless(is_string($token) && strlen($token) === 64, 403);
        $payment = $order->nexiPayments()->where('return_token', $token)->first();
        abort_unless($payment, 403);
        $request->session()->put('nexi_recaps.'.$order->id, true);

        return $this->paymentView('ecommerce.cart.nexi-result', compact('order', 'payment'));
    }

    public function cancel(Request $request, Order $order): Response
    {
        return $this->result($request, $order);
    }

    private function paymentView(string $view, array $data): Response
    {
        return response()->view($view, $data)->withHeaders([
            'Cache-Control' => 'private, no-store',
            'Referrer-Policy' => 'no-referrer',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }

    private function authorizeCustomer(Request $request, Order $order): void
    {
        if ($order->user_id) {
            abort_unless($request->user() && (string) $request->user()->id === (string) $order->user_id, 403);

            return;
        }

        abort_unless((string) $request->session()->get('cart_id') === (string) $order->id
            || (string) $request->session()->get('nexi_order_id') === (string) $order->id, 403);
    }
}
