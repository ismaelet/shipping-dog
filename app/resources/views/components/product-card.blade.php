@props(['product', 'badge' => null])

@php
	if (!$badge && $product->isPopular()) {
	    $badge = 'Bestseller';
	} elseif (!$badge && $product->isNew()) {
	    $badge = 'Novità';
	}
@endphp

@pushOnce('styles')
	<style>
		.product-card-img-wrapper {
			border-radius: clamp(var(--radius-xl), 8cqi, var(--radius-3xl));
		}
	</style>
@endpushOnce

<article class="product-card group min-w-0">
	{{-- Product image --}}
	<div class="@container">
		<div class="product-card-img-wrapper aspect-4/5 bg-surface-400 light relative overflow-hidden rounded-xl">
			<a
				href="{{ $product->url() }}"
				class="block size-full"
			>
				<img
					src="{{ $product->image() }}"
					alt="{{ $product->name }}"
					loading="lazy"
					class="size-full origin-center transform-gpu object-cover transition-transform duration-500 ease-out will-change-transform group-hover:scale-105"
				/>
			</a>

			{{-- Badge --}}
			@if ($badge)
				<span
					class="text-neutral-heading pointer-events-none absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider"
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
	</div>

	{{-- Product details --}}
	<div class="flex items-start justify-between gap-4 pt-4">
		<div class="min-w-0">
			<h3 class="text-neutral-heading font-semibold">
				<a
					href="{{ $product->url() }}"
					class="transition-colors hover:text-orange-600"
				>
					{{ $product->name }}
				</a>
			</h3>

			<p class="mt-1 text-sm">
				{{ $product->type->name }}
			</p>

			<p class="mt-1 text-xs dark:text-slate-500">
				by
				<a
					href="{{ $product->seller->url() }}"
					class="transition-colors hover:text-orange-600"
				>
					{{ $product->seller->name }}
				</a>
			</p>
		</div>

		<p class="dark:text-neutral-heading text-neutral-heading shrink-0 whitespace-nowrap font-semibold">
			{{ price($product->price()->price) }}
		</p>
	</div>
</article>
