@php
    use App\Models\Ecommerce\Order;

    $newSales = Order::where('status_id', '>', 0)
        ->where('created_at', '>=', now()->subMonth())
        ->where('created_at', '<=', now())
        ->count();

    $oldSales = Order::where('status_id', '>', 0)
        ->where('created_at', '>=', now()->subMonth()->subMonth())
        ->where('created_at', '<', now()->subMonth())
        ->count();

    $difference = $newSales - $oldSales;
    $differencePercentage = $oldSales > 0 ? ($difference / $oldSales) * 100 : 0;
    $formattedDifferencePercentage = number_format($differencePercentage, 2, ',', '.');
@endphp

<x-templates.dashboard.dashboard-panel title="Vendite Totali" subtitle="negli ultimi 30 giorni" icon="credit-card">

    <x-slot name="value">
        {{ $newSales > 0 ? '+' . $newSales : 0 }}
    </x-slot>

    <x-slot name="footerNotes">
        <span class="{{ $difference >= 0 ? 'text-success' : 'text-error' }}">
            {{ $difference >= 0 ? '+' . $formattedDifferencePercentage : $formattedDifferencePercentage }}%
        </span>

        rispetto al mese precedente
    </x-slot>

</x-templates.dashboard.dashboard-panel>
