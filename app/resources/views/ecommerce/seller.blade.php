@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/seller.css') }}"
	>
	<style>
		main {
			--breakout-width: 1400px;
		}
	</style>
@endpushOnce

<x-layouts.legacy-main :$locationTag>

	<section
		id="head"
		class="breakout"
		data-seller-id="<?= $seller->id ?>"
	>

		<!-- Cover -->
		<img
			id="cover"
			src="<?= $seller->cover() ?>"
		/>

		<form
			id="head-sec"
			method="POST"
			enctype="multipart/form-data"
		>
			@csrf

			<div id="head-cont">

				<!-- Profile icon -->
				<div id="icon-box">
					<img
						id="icon"
						src="<?= $seller->icon() ?>"
					/>
				</div>

				<!-- Edit button -->
				@if ($vip)
					<a
						href="{{ route('seller-dashboard') }}"
						class="relative"
					>
						<p
							id="edit"
							class="bgr-c1-h"
						>modifica</p>
					</a>
				@endif

				<!-- Follow button -->
				<div class="mb-4 mt-8 flex justify-center">
					<livewire:follow-button :$seller />
				</div>

				<div
					id="title-box"
					class="text-center"
				>

					<!-- Name -->
					<h1
						id="title"
						class="txt-3rem mt-4"
					><?= $seller->name ?></h1>

					<!-- Statistics -->
					<p
						id="store-data"
						class="mar-b-1 mt-2"
					>
						<?= $productCount ?> Prodotti &middot;
						<?= $likeN ?> Like &middot;
						<?= $follN ?> Follower
					</p>

					@if ($user->contacts)
						{{-- Contacts --}}
						<div class="flex flex-wrap justify-center gap-4">

							@foreach ($contacts as $contactName => $contact)
								@if ($user->$contact())
									<a
										href="{{ $user->$contact() }}"
										class="hover:text-primary text-secondary transition-colors"
										target="_blank"
									>
										<x-dynamic-component
											:component="'icon-' . $contact"
											class="h-5 w-5"
										/>
									</a>
								@endif
							@endforeach

						</div>
					@endif

					@if (!empty($legacySeller->description))
						<p
							id="desc"
							class="mar-t-2"
						><?= $legacySeller->description ?></p>
					@endif

					@if (!$seller->status)
						<p
							id="wip"
							class="mar-v-2"
						>Il negozio ancora non è pubblico. Ti senti pronto? Il tuo negozio può essere reso pubblico? Faccelo sapere
							scrivendoci a <a
								href="<?= config('custom.contacts.email.url') ?>"
								class="hover:text-primary font-bold transition-colors"
							><?= config('custom.contacts.email.label') ?></a> o su WhatsApp a <a
								href="<?= config('custom.contacts.whatsapp.url') ?>"
								class="hover:text-primary font-bold transition-colors"
							><?= config('custom.contacts.whatsapp.label') ?></a>.</p>
					@endif

				</div>
			</div>
		</form>
	</section>

	<div
		id="body"
		class="full-width"
	>

		<!-- Official shop -->

		<?php if ($hasItems) { ?>

		<!-- Shop -->
		<div
			id="store-pan"
			class="breakout"
		>
			<div class="mar-t-2 py-8">
				<div class="stitle-box">
					<p class="stitle font-semibold">Shop <span class="item-num txt-light"><span class="itemN"></span> risultati</span>
					</p>
				</div>

				<x-shop.product-list :$seller />
			</div>
		</div>

		@if ($collN || $owner)
			<!-- Collections -->
			<section
				id="collections"
				class="full-width"
			>

				<div
					class="sort-pan"
					data-type="coll"
				>
					<h3
						id="coll-title"
						class="txt-med mar-b-2"
					>Collezioni</h3>

					<div
						id="collections-grid"
						class="sortable"
					>

						<?php if ($owner) { ?>

						<div class="coll-ele add">
							<div
								class="collection clickable lbox-open"
								data-tar="#coll-add-lbox"
							>
								<div class="center-items hei-100">

									<div class="coll-add-box cen wid-100 text-center">
										<div class="coll-add-ico text-neutral-700">
											<?= embed_icon('add') ?>
										</div>
										<p class="coll-add-txt txt-wht">Aggiungi collezione</p>
									</div>
								</div>
							</div>
						</div>

						<?php } ?>

						<x-shop.collections
							:$vip
							:$id
							:$collBlock
						/>

					</div>

					<?php if ($collN > $collBlock) { ?>
					<input
						type="hidden"
						id="collN"
						value="<?= $collN ?>"
					>
					<input
						type="hidden"
						id="collBlock"
						value="<?= $collBlock ?>"
					>
					<input
						type="hidden"
						id="collPos"
						value="<?= $collBlock ?>"
					>
					<?php } ?>

					<div class="sort-load bgr">
						<div class="sort-load-icon cen img cont">{!! embed_icon('loading') !!}</div>
					</div>
				</div>

			</section>
		@endif

		@if ($vip)
			<!-- New collection lbox -->
			<div
				id="coll-add-lbox"
				class="modal lbox"
			>

				<form
					class="modal-form"
					method="POST"
				>
					@csrf

					<div class="modal-form-header">
						<h3 class="lbox-title">Aggiungi una collezione</h3>
					</div>
					<div class="lbox-body">
						<input
							type="text"
							name="name"
							class="input"
							placeholder="Nome della collezione"
							required
						>
					</div>
					<div class="modal-form-footer">
						<x-button
							type="submit"
							name="add_folder"
							class="w-full"
						>Aggiungi</x-button>
						<x-button
							type="button"
							variant="secondary"
							class="lbox-close w-full"
						>Annulla</x-button>
					</div>
				</form>
			</div>
		@endif

		<?php } else { ?>

		<!-- If the shop is empty -->
		<div
			id="empty"
			class="pb-40 pt-20 text-center"
		>
			<div id="empty-box">
				<p
					id="empty-title"
					class="uppercase"
				>Ancora nessun lavoro caricato</p>

				<a href="/seller-dashboard/portfolio/add?id={{ $id }}">
					<x-button
						type="button"
						class="mt-8 w-full"
					>Carica un lavoro</x-button>
				</a>
			</div>
		</div>

		<?php } ?>

	</div>


</x-layouts.legacy-main>
