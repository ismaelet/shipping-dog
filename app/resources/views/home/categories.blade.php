@pushOnce('styles')
	<style>
		#categorie .category-gradient {
			background: linear-gradient(to top,
					rgb(2 6 23 / 75%),
					transparent 50%);
		}
	</style>
@endPushOnce

<section
	id="categorie"
	aria-label="Categorie di prodotti"
	class="py-16"
>
	{{-- Heading --}}
	<div class="mb-8 flex items-end justify-between">
		<div>
			<p class="text-xs font-bold uppercase tracking-[0.2em] text-orange-600">
				Trova il tuo stile
			</p>

			<h2 class="text-heading-400 mt-3 text-3xl font-bold tracking-tight sm:text-5xl">
				Esplora le categorie
			</h2>
		</div>
	</div>

	{{-- Category grid --}}
	<div class="grid gap-4 md:grid-cols-2">
		{{-- T-shirt --}}
		<a
			href="{{ route('shop', ['type' => 1]) }}"
			class="group relative min-h-96 overflow-hidden rounded-3xl bg-orange-100 p-8"
		>
			<img
				src="{{ Vite::image('home/category-1.webp') }}"
				alt="Collezione T-shirt Shipping Dog"
				loading="lazy"
				class="absolute inset-0 size-full object-cover transition-transform duration-700 group-hover:scale-105"
			/>

			<div class="category-gradient absolute inset-0"></div>

			<div class="absolute bottom-8 left-8 right-8 text-white">
				<p class="text-sm text-white/70">
					T-shirt
				</p>

				<h3 class="mt-1 text-3xl font-bold">
					Non la solita T-shirt
				</h3>
			</div>
		</a>

		{{-- Right categories --}}
		<div class="grid gap-4 sm:grid-cols-2">
			{{-- Hoodies --}}
			<a
				href="{{ route('shop', ['type' => 8]) }}"
				class="group relative min-h-80 overflow-hidden rounded-3xl bg-blue-100 p-6 md:min-h-full"
			>
				<img
					src="{{ Vite::image('home/category-2.webp') }}"
					alt="Collezione felpe Shipping Dog"
					loading="lazy"
					class="absolute inset-0 size-full object-cover transition-transform duration-700 group-hover:scale-105"
				/>

				<div class="category-gradient absolute inset-0"></div>

				<div class="absolute bottom-6 left-6 right-6 text-white">
					<p class="text-sm text-white/70">
						Hoodies
					</p>

					<h3 class="mt-1 text-2xl font-bold">
						Il comfort ha stile
					</h3>
				</div>
			</a>

			{{-- Accessories --}}
			<a
				href="{{ route('shop', ['type' => 116]) }}"
				class="group relative min-h-80 overflow-hidden rounded-3xl bg-orange-500 p-6 text-white md:min-h-full"
			>
				<div class="flex h-full flex-col justify-between">
					<x-icon-spark class="size-9" />

					<div>
						<p class="text-sm text-white/70">
							Accessori
						</p>

						<h3 class="mt-1 text-2xl font-bold">
							Dettagli che parlano
						</h3>

						<svg
							xmlns="http://www.w3.org/2000/svg"
							viewBox="0 0 24 24"
							fill="none"
							stroke="currentColor"
							stroke-width="2"
							stroke-linecap="round"
							stroke-linejoin="round"
							class="mt-5 size-6 transition-transform group-hover:translate-x-2"
							aria-hidden="true"
						>
							<path d="M5 12h14" />
							<path d="m13 6 6 6-6 6" />
						</svg>
					</div>
				</div>
			</a>
		</div>
	</div>
</section>
