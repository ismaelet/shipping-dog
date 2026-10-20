{{-- Community --}}
<section
	id="community"
	aria-label="La community Shipping Dog"
	class="breakout mb-12 py-16 text-center"
>
	{{-- Heading --}}
	<p class="text-primary-500 text-xs font-bold uppercase tracking-[0.2em]">
		La community
	</p>

	<h2 class="mx-auto mt-4 max-w-3xl text-4xl font-bold tracking-tight sm:text-6xl">
		Fatto per essere vissuto.
	</h2>

	<p class="max-w-120 mx-auto mt-5 leading-7 text-slate-500 dark:text-slate-400">
		Tagga
		<a
			href="{{ config('custom.contacts.instagram.url') }}"
			target="_blank"
			rel="noopener noreferrer"
			class="text-primary-500 dark:text-neutral-heading font-semibold transition-colors hover:text-orange-500"
		>
			@shippingdog
		</a>
		o inviaci una foto del tuo look via
		<a
			href="{{ config('custom.contacts.email.url') }}"
			class="text-primary-500 dark:text-neutral-heading font-semibold transition-colors hover:text-orange-500"
		>
			e-mail
		</a>
		e ricevi un codice <span class="text-primary-500">sconto del 15%</span>.
	</p>

	{{-- Photo gallery --}}
	<div class="mt-10 grid grid-cols-2 gap-3 md:grid-cols-4 lg:grid-cols-5">
		@foreach ($customerPhotos as $customerPhoto)
			<a
				href="{{ $customerPhoto->design?->url() ?? route('customer-pics') }}"
				class="group relative aspect-square overflow-hidden rounded-2xl bg-slate-100 last:hidden lg:last:block dark:bg-slate-800"
				aria-label="Foto di {{ $customerPhoto->full_name }}"
			>
				<img
					src="{{ $customerPhoto->image() }}"
					alt="Look di {{ $customerPhoto->full_name }}"
					loading="lazy"
					class="size-full object-cover object-top transition-transform duration-700 group-hover:scale-105"
				/>

				{{-- Hover overlay --}}
				<div
					class="bg-linear-to-t absolute inset-0 flex items-end from-slate-950/70 via-transparent to-transparent p-5 opacity-0 transition-opacity duration-300 group-hover:opacity-100 group-focus-visible:opacity-100"
				>
					<div class="min-w-0 text-left text-white">
						@if ($customerPhoto->design)
							<p class="truncate font-semibold">
								{{ $customerPhoto->design->name }}
							</p>
						@endif

						<p class="mt-1 truncate text-sm text-white/75">
							{{ $customerPhoto->full_name }}
						</p>
					</div>
				</div>
			</a>
		@endforeach
	</div>

	{{-- View all --}}
	<a
		href="{{ route('customer-pics') }}"
		class="hover:text-primary-500 mt-8 inline-flex items-center gap-2 text-sm font-bold transition-colors"
	>
		Guarda tutte le foto

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
</section>
