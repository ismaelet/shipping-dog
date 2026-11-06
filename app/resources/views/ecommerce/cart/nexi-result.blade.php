<!doctype html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="referrer" content="no-referrer">
        <meta name="robots" content="noindex, nofollow">
        <title>Pagamento ordine #{{ $order->id }}</title>
    </head>
    <body>
        <h1>Ordine #{{ $order->id }}</h1>
        @if ($payment->status === 'paid')
            <p>Pagamento ricevuto. {{ in_array($payment->fulfillment_status, ['confirmed', 'fulfilled']) ? 'Ordine confermato.' : 'Conferma ordine in elaborazione.' }}</p>
        @elseif (in_array($payment->status, ['failed', 'cancelled']))
            <p>Pagamento non completato. Contatta l'assistenza prima di effettuare un nuovo pagamento.</p>
        @else
            <p>In attesa della conferma Nexi. Aggiorna questa pagina tra qualche istante. Non ripetere il pagamento.</p>
        @endif
        <p>Totale: {{ number_format($payment->amount / 100, 2, ',', '.') }} €</p>
        @include('ecommerce.cart.nexi-recap-link')
    </body>
</html>
