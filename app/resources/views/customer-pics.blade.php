@php

	use App\Models\Ecommerce\Design\CustomerPhoto;

	$customerPhotos = CustomerPhoto::with('design', 'design.main_product')->orderByDesc('id')->get();

@endphp

@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/customer-pics.css') }}"
	>
@endpushOnce

<x-layouts.legacy-main>

	<section id="client-pics">
		<div class="header">
			<div class="main-wrap text-center">
				<div class="section-heading-box">
					<x-typography.main-heading>Le vostre foto</x-typography.main-heading>
				</div>

				<x-typography.main-subheading class="mx-auto mt-8 max-w-[55ch]">Mandaci una foto con un nostro capo indossato
					su <a
						class="hover:text-primary text-neutral-heading font-semibold transition-colors"
						href="{{ config('custom.contacts.instagram.url') }}"
					>Instagram</a> o per <a
						class="hover:text-primary text-neutral-heading font-semibold transition-colors"
						href="{{ config('custom.contacts.email.url') }}"
					>e-email</a> e ottieni subito un <span class="text-neutral-heading font-semibold">codice sconto del
						15%</span></x-typography.main-subheading>
			</div>
		</div>

		<div class="main-wrap">
			<div class="gallery">

				@foreach ($customerPhotos as $customerPhoto)
					<a
						href="{{ $customerPhoto->design?->url() }}"
						class="client__image image stack-items"
					>
						<img
							src="{{ $customerPhoto->image() }}"
							alt=""
							loading="lazy"
							class="img-fill bg-neutral-200"
						>
						<div class="overlay pad-2 va-cen grid text-center">
							<div class="title-box">

								@if ($customerPhoto->design)
									<p class="design-name txt-wht txt-11rem txt-line font-semibold">{{ $customerPhoto->design->name }}</p>
								@endif

								<p class="client-name txt-wht txt-line uppercase">{{ $customerPhoto->full_name }}</p>
							</div>
						</div>
					</a>
				@endforeach

			</div>
		</div>

	</section>


</x-layouts.legacy-main>
