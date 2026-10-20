@props(['product', 'badge' => null])

<article class="group min-w-0 text-slate-950 dark:text-stone-100">
	{{-- Product image --}}
	<div class="aspect-4/5 relative overflow-hidden rounded-3xl bg-slate-100 dark:bg-slate-800">
		<a
			href="{{ $product->url() }}"
			class="block size-full"
		>
			<img
				src="{{ $product->image() }}"
				alt="{{ $product->name }}"
				loading="lazy"
				class="size-full object-cover transition-transform duration-700 group-hover:scale-105"
			/>
		</a>

		{{-- Badge --}}
		@if ($badge)
			<span
				class="pointer-events-none absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-950"
			>
				{{ $badge }}
			</span>
		@endif

		{{-- Wishlist --}}
		<div class="absolute right-4 top-4 z-10">
			<livewire:wishlist-button
				:product="$product"
				:key="'wishlist-button-' . $product->id . '-' . $product->color_id . '-' . $product->position_id"
			/>
		</div>
	</div>

	{{-- Product details --}}
	<div class="flex items-start justify-between gap-4 pt-4">
		<div class="min-w-0">
			<h3 class="font-semibold text-slate-950 dark:text-stone-100">
				<a
					href="{{ $product->url() }}"
					class="transition-colors hover:text-orange-600"
				>
					{{ $product->name }}
				</a>
			</h3>

			<p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
				{{ $product->type->name }}
			</p>

			<p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
				by
				<a
					href="{{ $product->seller->url() }}"
					class="transition-colors hover:text-orange-600"
				>
					{{ $product->seller->name }}
				</a>
			</p>
		</div>

		<p class="shrink-0 whitespace-nowrap font-semibold text-slate-950 dark:text-stone-100">
			{{ price($product->price()->price) }}
		</p>
	</div>
</article>
