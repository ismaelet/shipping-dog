@php
    use App\Models\User;

    $newUsersCount = User::where('created_at', '>=', now()->subMonth())->where('created_at', '<=', now())->count();
    $oldUsersCount = User::where('created_at', '>=', now()->subMonth()->subMonth())
        ->where('created_at', '<', now()->subMonth())
        ->count();

    $difference = $newUsersCount - $oldUsersCount;
    $differencePercentage = $oldUsersCount > 0 ? ($difference / $oldUsersCount) * 100 : 0;
    $formattedDifferencePercentage = number_format($differencePercentage, 2, ',', '.');
@endphp

<x-dashboard.item title="Nuovi Utenti" subtitle="negli ultimi 30 giorni" icon="users">

    <x-slot name="value">
        {{ $newUsersCount > 0 ? '+' . $newUsersCount : 0 }}
    </x-slot>

    <x-slot name="footerNotes">
        <span class="{{ $difference >= 0 ? 'text-success' : 'text-error' }}">
            {{ $difference >= 0 ? '+' . $formattedDifferencePercentage : $formattedDifferencePercentage }}%
        </span>

        rispetto al mese precedente
    </x-slot>

</x-dashboard.item>
