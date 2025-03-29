@php
	use App\Models\Ecommerce\Order;

	$labels = Order::unlabelled();
	$labelCount = $labels->count();
	$firstCartDate = $labels->sortBy('created_at')->first()->created_at ?? null;
	$firstCartDate = $firstCartDate ? $firstCartDate->format('d/m/Y') : null;
@endphp

<x-dashboard.widget
	title="Spedizioni da Trasmettere"
	:subtitle="$firstCartDate ? 'a partire dal ' . $firstCartDate : 'nessuna spedizione da trasmettere'"
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
