<x-layouts.empty>
    <x-heading variant="section">Pagamento ordine #{{ $order->id }}</x-heading>
    <p>Continua sul sito sicuro Nexi per pagare con carta.</p>
    <form method="POST" action="{{ route('nexi.orders.store', $order) }}">
        @csrf
        <x-button type="submit">Paga con Nexi</x-button>
    </form>
</x-layouts.empty>
