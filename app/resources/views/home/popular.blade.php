{{-- Popular products --}}
<section
	id="novita"
	class="full-width bg-white py-20 transition-colors dark:bg-slate-950"
>
	<div class="mx-auto w-full">
		{{-- Heading --}}
		<div class="mb-10 flex items-end justify-between gap-4">
			<div>
				<p class="text-xs font-bold uppercase tracking-[0.2em] text-orange-600">
					Scelti per te
				</p>

				<h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-5xl">
					I più amati
				</h2>
			</div>

			<a
				href="{{ route('shop') }}"
				class="hidden items-center gap-2 text-sm font-bold transition-colors hover:text-orange-600 sm:inline-flex"
			>
				Vedi tutto

				<svg
					xmlns="http://www.w3.org/2000/svg"
					viewBox="0 0 24 24"
					fill="none"
					stroke="currentColor"
					stroke-width="2"
					stroke-linecap="round"
					stroke-linejoin="round"
					class="size-4"
					aria-hidden="true"
				>
					<path d="M5 12h14" />
					<path d="m13 6 6 6-6 6" />
				</svg>
			</a>
		</div>

		{{-- Products --}}
		<div class="xs:grid-cols-2 grid grid-cols-1 gap-x-5 gap-y-10 lg:grid-cols-4">
			@foreach ($products as $product)
				<x-product-card
					:$product
					:badge="$loop->index < 2 ? 'Bestseller' : 'Novità'"
				/>
			@endforeach
		</div>
	</div>
</section>
