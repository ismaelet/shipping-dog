@props(['order'])

<tr class="border-neutral-border hover:bg-surface-500/40 group relative border-t transition-colors">
	<td class="text-neutral-heading whitespace-nowrap py-4 pr-4 font-bold">
		<a
			href="{{ $order->recapUrl() }}"
			class="before:absolute before:inset-0"
		>{{ $order->idCode() }}</a>
	</td>
	<td class="py-4 pr-4">
		<div class="flex items-center gap-3">
			<span class="text-primary-400 grid size-6 shrink-0 place-items-center">
				<x-icon-shirt class="size-5" />
			</span>
			<div class="min-w-0">
				<p class="text-neutral-heading font-semibold leading-5">{{ $order->mainTitle() }}</p>
				<p class="text-xs leading-4">{{ $order->subTitle() }}</p>
			</div>
		</div>
	</td>

	<td class="text-neutral-heading whitespace-nowrap py-4 pr-4 font-bold">
		{{ price($order->price, true) }}
	</td>

	<td class="py-4 pr-4">
		<span
			class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold"
			style="color: {{ $order->statusColor() }}; background-color: color-mix(in srgb, {{ $order->statusColor() }} 15%, transparent);"
		>
			<span
				class="size-1.5 rounded-full"
				style="background-color: {{ $order->statusColor() }};"
			></span>
			{{ $order->statusName() }}
		</span>
	</td>

	<td class="whitespace-nowrap py-4 pr-4 text-sm">{{ $order->humanDate() }}</td>

	<td class="group-hover:text-neutral-heading py-4 transition-colors">
		<x-icon-keyboard-arrow-right class="size-4" />
	</td>
</tr>
