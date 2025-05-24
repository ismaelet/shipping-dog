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
					<h1 class="section-heading txt-sbold">Le vostre foto</h1>
				</div>

				<p class="mwid-600 mar-h-auto mar-t-2 txt-12rem">Mandaci una foto con un nostro capo indossato su <a
						class="txt-c1-h"
						href="{{ config('custom.contacts.instagram.url') }}"
					>Instagram</a> o per <a
						class="txt-c1-h"
						href="{{ config('custom.contacts.email.url') }}"
					>e-email</a> e ottieni subito un <strong>codice sconto del 15%</strong></p>
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
							class="img-fill"
						>
						<div class="overlay pad-2 va-cen grid text-center">
							<div class="title-box">

								@if ($customerPhoto->design)
									<p class="design-name txt-wht txt-11rem txt-line font-semibold">{{ $customerPhoto->design->name }}</p>
								@endif

								<p class="client-name txt-wht txt-line uppercase">{{ $customerPhoto->fullName() }}</p>
							</div>
						</div>
					</a>
				@endforeach

			</div>
		</div>

	</section>


</x-layouts.legacy-main>
