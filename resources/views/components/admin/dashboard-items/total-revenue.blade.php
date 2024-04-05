@php
    use App\Models\Ecommerce\Order;

    $newRevenue = Order::where('status_id', '>', 0)
        ->where('created_at', '>=', now()->subMonth())
        ->where('created_at', '<=', now())
        ->sum('fullProductPrice');

    $oldRevenue = Order::where('status_id', '>', 0)
        ->where('created_at', '>=', now()->subMonth()->subMonth())
        ->where('created_at', '<', now()->subMonth())
        ->sum('fullProductPrice');

    $difference = $newRevenue - $oldRevenue;
    $differencePercentage = $oldRevenue > 0 ? ($difference / $oldRevenue) * 100 : 0;
    $formattedNewRevenue = number_format($newRevenue, 2, ',', '.');
    $formattedDifferencePercentage = number_format($differencePercentage, 2, ',', '.');
@endphp

<x-admin.dashboard-item title="Fatturato Totale" icon="money">

    <x-slot name="value">
        € {{ $formattedNewRevenue }}
    </x-slot>

    <x-slot name="footerNotes">
        <span class="{{ $difference >= 0 ? 'text-success' : 'text-error' }}">
            {{ $difference >= 0 ? '+' . $formattedDifferencePercentage : $formattedDifferencePercentage }}%
        </span>

        rispetto all'ultimo mese
    </x-slot>

</x-admin.dashboard-item>
