@pushOnce('styles')
	<style>
		#page-header { height: 15rem; }
		#searcher {
			display: grid;
			grid-template-columns: minmax(0, 1fr) min(6rem, 20vw);
			width: 100%;
			max-width: 600px;
			gap: 0.5rem;
			font-size: min(1.25rem, 5vw);
		}
		#srch-inp {
			--inp-color: #202020;
			--inp-clr-bgr: #fff;
			width: 100%;
			border: none;
			border-radius: 2rem 0 0 2rem;
			padding-left: 1em;
		}
		#srch-btn {
			width: 100%;
			border: none;
			border-radius: 0 30px 30px 0;
			background-color: #c92528;
			cursor: pointer;
		}
		#srch-btn .icon { display: block; height: 1.25em; aspect-ratio: 1; }
		#shops .gallery { grid-template-columns: repeat(auto-fill, minmax(18rem, 1fr)); gap: 2rem; }
		#items .gallery { grid-template-columns: repeat(auto-fill, minmax(14rem, 1fr)); gap: 2rem 1rem; }
		.shop .cover { aspect-ratio: 1.5; border-radius: 0.25rem 0.25rem 0 0; box-shadow: 0 0.5rem 1.5rem rgb(0 0 0 / 40%); }
		.shop .icon { width: 3rem; aspect-ratio: 1; border: 2px solid #eee; border-radius: 50%; }
		.shop .name {
			width: 100%; align-self: end; padding-inline: 0.5rem;
			background: rgb(255 255 255 / 60%); color: #202020;
			font-weight: 500; letter-spacing: 0.05em; line-height: 4; text-align: center;
		}
		.item .image { width: 100%; aspect-ratio: 1; border-radius: 0.25rem; object-fit: cover; }
		.item .cta { transition: border 0.2s, color 0.2s; }
	</style>
@endpushOnce

<x-layouts.main
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
				<span class="icon txt-wht"><x-icon-search /></span>
			</button>
		</form>
	</section>

	<div class="main-wrap pt-16 pb-20 breakout">
		<p
			id="title"
			class="mb-12 text-lg"
		>
			Cerca: <span class="text-strong"><?= $search ?></span>
		</p>

		<?php if ($resultCount) { ?>

		<?php if ($sellerCount) { ?>

		<section
			id="shops"
			class="mb-16"
		>
			<x-heading.component>Negozi:</x-typography>
				<div class="gallery mt-4 grid">

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
						<p class="name overflow-hidden text-ellipsis whitespace-nowrap"><?= $seller->name ?></p>
					</a>

					<?php } ?>

				</div>
		</section>

		<?php } ?>


		<?php if ($designCount) { ?>

		<section id="items">
			<x-heading.component>Prodotti:</x-typography>
				<div class="gallery mt-4 grid">

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
						<h3 class="overflow-hidden text-ellipsis whitespace-nowrap font-medium mt-4"><?= $product->name ?></h3>
						<p class="mb-2 text-[0.8rem]">
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
</x-layouts.main>
