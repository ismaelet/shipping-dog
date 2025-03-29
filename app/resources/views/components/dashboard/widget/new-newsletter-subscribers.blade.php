@php
    use App\Models\Newsletter\Subscriber;

    $newSubscribersCount = Subscriber::where('created_at', '>=', now()->subMonth())
        ->where('created_at', '<=', now())
        ->count();
    $oldSubscribersCount = Subscriber::where('created_at', '>=', now()->subMonth()->subMonth())
        ->where('created_at', '<', now()->subMonth())
        ->count();

    $difference = $newSubscribersCount - $oldSubscribersCount;
    $differencePercentage = $oldSubscribersCount > 0 ? ($difference / $oldSubscribersCount) * 100 : 0;
    $formattedDifferencePercentage = number_format($differencePercentage, 2, ',', '.');
@endphp

<x-dashboard.widget title="Iscritti alla Newsletter" subtitle="negli ultimi 30 giorni" icon="newsletter">

    <x-slot name="value">
        {{ $newSubscribersCount > 0 ? '+' . $newSubscribersCount : 0 }}
    </x-slot>

    <x-slot name="footerNotes">
        <span class="{{ $difference >= 0 ? 'text-success' : 'text-error' }}">
            {{ $difference >= 0 ? '+' . $formattedDifferencePercentage : $formattedDifferencePercentage }}%
        </span>

        rispetto al mese precedente
    </x-slot>

</x-dashboard.widget>
