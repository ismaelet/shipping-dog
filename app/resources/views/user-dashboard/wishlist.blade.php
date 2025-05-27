@pushOnce('styles')
	<style>
		/* Products */
		#products {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(12rem, 1fr));
			gap: 2rem;
		}

		#products img {
			object-fit: cover;
			border-radius: 10px;
			box-shadow: 5px 10px 1rem rgba(0, 0, 0, 0.1);
			width: 100%;
			aspect-ratio: 1 / 1;
		}

		#products .name {
			font-size: 0.9rem;
			font-weight: 600;
		}

		#products .shop {
			color: var(--text-color);
			font-size: 0.8rem;
		}
	</style>
@endpushOnce

<x-user-dashboard :$title>
	<x-user-dashboard.panel
		:icon="'love'"
		:$title
	>

		<?php if (!$likes->count()) { ?>

		<div class="main-wrap pad-t-4 pad-b-5">

			<div
				id="products"
				class=""
			>

				<?php foreach ($likes as $like) {
				$design = $like->design;
			?>

				<div class="product">

					<a
						class="img-box"
						href="<?= $design->url() ?>"
					>
						<img
							src="<?= $design->image() ?>"
							alt=""
							loading="lazy"
						>
					</a>

					<p class="name mar-t-1 text-neutral-700"><?= $design->name ?></p>

					<p class="shop">by <a
							class="txt-c1-h"
							href="<?= $design->url() ?>"
						><?= $design->seller->name ?></a></p>

				</div>

				<?php } ?>

			</div>

		</div>

		<?php } else { ?>

		<div class="main-wrap">
			<div
				id="empty-box"
				class="text-center"
			>

				<div
					id="empty-icon"
					class="inlb mar-b-2"
				></div>

				<div class="grid grid-cols-1 justify-items-center gap-8">
					<p id="empty-txt">Nessuna grafica nella wishlist. Cerca nel nostro negozio un prodotto che possa piacerti.</p>

					<x-button href="{{ route('shop') }}">
						Vai al negozio
					</x-button>
				</div>

			</div>
		</div>

		<?php } ?>


	</x-user-dashboard.panel>
</x-user-dashboard>
