@php
    use App\Models\Ecommerce\Order;

    $newRevenue = Order::join('carts', 'orders.cart_id', '=', 'carts.id')
        ->where('carts.status', '>', 0)
        ->where('orders.time', '>=', now()->subMonth())
        ->where('orders.time', '<=', now())
        ->sum('orders.fullProductPrice');

    $oldRevenue = Order::join('carts', 'orders.cart_id', '=', 'carts.id')
        ->where('carts.status', '>', 0)
        ->where('orders.time', '>=', now()->subMonth()->subMonth())
        ->where('orders.time', '<', now()->subMonth())
        ->sum('orders.fullProductPrice');

    $difference = $newRevenue - $oldRevenue;
    $differencePercentage = $oldRevenue > 0 ? ($difference / $oldRevenue) * 100 : 0;
    $formattedNewRevenue = number_format($newRevenue, 2, ',', '.');
    $formattedDifferencePercentage = number_format($differencePercentage, 2, ',', '.');
@endphp

<x-dashboard.widget title="Fatturato Totale" subtitle="degli ultimi 30 giorni" icon="money">

    <x-slot name="value">
        € {{ $formattedNewRevenue }}
    </x-slot>

    <x-slot name="footerNotes">
        <span class="{{ $difference >= 0 ? 'text-success' : 'text-error' }}">
            {{ $difference >= 0 ? '+' . $formattedDifferencePercentage : $formattedDifferencePercentage }}%
        </span>

        rispetto al mese precedente
    </x-slot>

</x-dashboard.widget>
