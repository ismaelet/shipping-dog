@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/search.css') }}"
	>
@endpushOnce

<x-layouts.legacy-main
	:$title
	:$location
>
	<section
		id="page-header"
		class="full-width dark:bg-surface bg-surface-500 py-20"
	>

		<form
			method="GET"
			id="searcher"
			class="mx-auto w-full content-center justify-center"
		>
			<div
				id="srch-inp-cell"
				class=""
			>
				<input
					type="search"
					class="input"
					id="srch-inp"
					name="search"
					placeholder="Cerca articolo, negozio o tag"
					maxlength="30"
				>
			</div>

			<button
				type="submit"
				id="srch-btn"
				class="center-content self-stretch"
			>
				<span class="icon txt-wht"><?= embed_icon('search') ?></span>
			</button>
		</form>
	</section>

	<div class="main-wrap pad-t-4 pad-b-5 breakout">
		<p
			id="title"
			class="mar-b-3 text-lg"
		>
			Cerca: <span class="text-strong"><?= $search ?></span>
		</p>

		<?php if ($resultCount) { ?>

		<?php if ($sellerCount) { ?>

		<section
			id="shops"
			class="mar-b-4"
		>
			<x-heading.component>Negozi:</x-typography>
				<div class="gallery mt-4">

					<?php foreach ($sellers as $seller) { ?>

					<a
						href="<?= $seller->url() ?>"
						class="shop stack-items"
					>
						<img
							src="<?= $seller->cover() ?>"
							alt=""
							loading="lazy"
							class="cover img-fill"
						>
						<img
							src="<?= $seller->icon() ?>"
							alt=""
							loading="lazy"
							class="icon"
						>
						<p class="name txt-line"><?= $seller->name ?></p>
					</a>

					<?php } ?>

				</div>
		</section>

		<?php } ?>


		<?php if ($designCount) { ?>

		<section id="items">
			<x-heading.component>Prodotti:</x-typography>
				<div class="gallery mt-4">

					<?php foreach ($designs as $design) {
						$product = $design->main_product;

						if ($product) {
					?>
					<div class="item">
						<a href="<?= $product->url() ?>">
							<img
								src="<?= $product->image() ?>"
								alt=""
								loading="lazy"
								class="image"
							>
						</a>
						<h3 class="txt-line txt-med mt-4"><?= $product->name ?></h3>
						<p class="mar-b-05 txt-08rem">
							by <a
								class="link"
								href="<?= $design->seller->url() ?>"
							><?= $design->seller->name ?></a>
						</p>
						<p class="">Su t-shirt e altri <strong><?= $design->productCount() ?> articoli</strong></p>
						<a
							class=""
							href="<?= $design->url() ?>"
						>
							<p class="cta inlb br-b-2 br-c2 br-c1-h txt-c2 txt-c1-h mt-2 font-semibold uppercase">Scopri</p>
						</a>
					</div>

					<?php }
					} ?>

				</div>
		</section>

		<?php } ?>

		<?php } else { ?>

		<p class="py-16 text-center text-xl">Ci dispiace, la ricerca non ha prodotto risultati.</p>

		<?php } ?>

	</div>
</x-layouts.legacy-main>
