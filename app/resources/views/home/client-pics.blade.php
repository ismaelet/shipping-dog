@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/home-client-pics.css') }}"
	>
@endpushOnce

<section
	id="client-pics"
	class="overflow-hidden"
>
	<div class="header">
		<div class="content-grid text-center">
			<div class="grid">
				<div class="section-heading-box">
					<h2 class="section-heading">Le vostre foto</h2>
				</div>

				<p class="mwid-600 mar-h-auto">Mandaci una foto con un nostro capo indossato su <a
						class="text-primary-h"
						href="<?= config('custom.contacts.instagram.url') ?>"
					>Instagram</a> o per <a
						class="text-primary-h"
						href="<?= config('custom.contacts.email.url') ?>"
					>e-email</a> e ottieni subito un <strong>codice sconto del 15%</strong></p>

				<div class="center-content flex">
					<x-button
						:href="config('custom.contacts.instagram.url')"
						class="mt-8"
						data-size="auto"
					>
						{!! embed_icon('instagram') !!}
						Seguici su Instagram
					</x-button>
				</div>

				<a
					href="{{ route('customer-pics') }}"
					class="mar-t-3 block"
				>
					<p class="more txt-rig">Esplorale tutte</p>
				</a>
			</div>
		</div>
	</div>

	<div class="content-grid">
		<div class="gallery span-full-width grid-cols-3 px-8 lg:-mx-64 lg:grid-cols-7 lg:px-0">

			<?php foreach ($customerPhotos as $customerPhoto) { ?>

			<a
				href="<?= $customerPhoto->design?->url() ?>"
				class="client__image image lg:nth-[1]:opacity-20 lg:nth-last-[1]:opacity-20 relative last:hidden lg:last:block"
			>
				<img
					src="<?= $customerPhoto->image() ?>"
					alt=""
					loading="lazy"
					class="img-fill bg-neutral-200"
				>
				<div class="overlay pad-2 va-cen absolute inset-0 grid text-center">
					<div class="title-box">

						<?php if ($customerPhoto->design) { ?>
						<p class="design-name txt-wht txt-11rem txt-line font-semibold"><?= $customerPhoto->design->name ?></p>
						<?php } ?>

						<p class="client-name txt-wht txt-line uppercase"><?= $customerPhoto->full_name ?></p>
					</div>
				</div>
			</a>

			<?php } ?>

		</div>
	</div>
</section>
