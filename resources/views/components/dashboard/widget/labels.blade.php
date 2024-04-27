@php
	use App\Models\Ecommerce\Cart;

	$labels = Cart::unlabelled();
	$labelCount = $labels->count();
	$firstCartDate = $labels->sortBy('created_at')->first()->created_at ?? null;
	$firstCartDate = $firstCartDate ? $firstCartDate->format('d/m/Y') : null;
@endphp

<x-dashboard.widget
	title="Etichette da Stampare"
	:subtitle="$firstCartDate ? 'a partire dal ' . $firstCartDate : 'nessuna etichetta da stampare'"
	icon="shipping"
>

	<x-slot name="value">
		{!! $labelCount ? $labelCount : '<span class="text-neutral-300">' . $labelCount . '</span>' !!}
	</x-slot>

	<x-slot name="action">
		<a
			href="/admin/labels/generate"
			class="hover:text-primary-400 transition-colors"
		>Visualizza</a>
	</x-slot>

</x-dashboard.widget>
