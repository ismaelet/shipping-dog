@props(['order'])

<span
	class="order-label flex gap-2 whitespace-nowrap rounded border px-4 py-1"
	style="border-color: {{ $order->statusColor() }}; color: {{ $order->statusColor() }};"
>
	<x-dynamic-component
		:component="'icon-' . $order->statusIcon()"
		class="w-[1em] shrink-0"
	/>

	{{ $order->statusName() }}
</span>
