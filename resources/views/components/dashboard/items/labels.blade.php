@php
    use App\Models\Ecommerce\Cart;

    $labelCount = Cart::unlabelled()->count();
    $firstCartDate = Cart::unlabelled()->sortBy('created_at')->first()->created_at ?? null;
    $firstCartDate = $firstCartDate ? $firstCartDate->format('d/m/Y') : null;
@endphp

<x-dashboard.item title="Etichette da Stampare" :subtitle="$firstCartDate ? 'a partire dal ' . $firstCartDate : 'nessuna etichetta da stampare'" icon="shipping">

    <x-slot name="value">
        {!! $labelCount ? $labelCount : '<span class="text-neutral-300">' . $labelCount . '</span>' !!}
    </x-slot>

    <x-slot name="action">
        <a href="/admin/shipping/courier" class="hover:text-primary-400 transition-colors">Visualizza</a>
    </x-slot>

</x-dashboard.item>
