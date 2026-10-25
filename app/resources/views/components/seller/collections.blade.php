@pushOnce('styles')
	<style>
		.collection-overlay {
			background: linear-gradient(45deg,
					rgb(2 6 23 / 80%),
					rgb(2 6 23 / 25%));
		}
	</style>
@endPushOnce

@foreach ($collections as $collection)
	<article class="min-w-0">

		{{-- Collection image --}}
		<a
			href="{{ $collection->url() }}"
			class="bg-primary-400 duration-400 group relative block aspect-square overflow-hidden rounded-2xl shadow-[0_4px_30px_rgba(0,0,0,0.15)] transition-shadow hover:shadow-[0_4px_30px_rgba(0,0,0,0.4)]"
			aria-label="Esplora la collezione {{ $collection->name }}"
		>
			@if ($image = $collection->image())
				<img
					src="{{ $image }}"
					alt=""
					loading="lazy"
					class="duration-400 size-full object-cover transition-transform group-hover:scale-110"
				/>
			@else
				<div class="grid size-full place-items-center p-6 text-center">
					<span class="text-2xl font-bold text-white">
						{{ $collection->name }}
					</span>
				</div>
			@endif

			{{-- Hover overlay --}}
			<div
				class="duration-400 collection-overlay pointer-events-none absolute inset-0 opacity-0 transition-opacity group-hover:opacity-100"
				aria-hidden="true"
			></div>

			{{-- Hover text --}}
			<div
				class="pointer-events-none absolute inset-0 flex items-center justify-center opacity-0 transition-all duration-300 group-hover:opacity-100"
				aria-hidden="true"
			>
				<span
					class="hover:text-primary-500 pointer-events-auto rounded-full border border-white/30 bg-black/30 px-5 py-2 font-semibold text-white transition-colors"
				>
					Esplora
				</span>
			</div>
		</a>

		{{-- Collection name --}}
		<h3 class="text-heading-400 mt-4 truncate text-center tracking-wide">
			<a
				href="{{ $collection->url() }}"
				class="hover:text-primary-400 transition-colors"
			>
				{{ $collection->name }}
			</a>
		</h3>
	</article>
@endforeach
