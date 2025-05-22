@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/home-designs.css') }}"
	>
@endpushOnce

<section id="designs">
	<div class="content-grid">
		<div class="section-heading-box">
			<h2 class="section-heading">Design popolari</h2>

			<a href="<?= client_url('/shop') ?>">
				<p class="more txt-rig">Esplora tutti</p>
			</a>
		</div>

		<div class="relative">
			<section
				id="carousel-designs"
				class="carousel mob-fluid"
				data-pos="1"
				data-loop="1"
			>

				<?php foreach ($designs as $design) { ?>

				<div class="carousel-box">
					<a href="<?= $design->url() ?>">
						<img
							src="<?= $design->image() ?>"
							alt=""
							class="img-fill"
						>
					</a>

					<h3 class="name mar-t-1 txt-line"><?= $design->name ?></h3>

					<p class="seller txt-line">by <a
							href="<?= $design->seller->url() ?>"
							class="text-primary-h"
						><?= $design->seller->name ?></a></p>
					<p class="txt-sbold txt-c2 mt-2">
						<a href="<?= $design->url() ?>">
							<?= $design->productCount() ?> prodotti
						</a>
					</p>
				</div>

				<?php } ?>

			</section>

			<div
				class="carousel-dots"
				data-tar="#carousel-designs"
			>
				<?php for ($i = 1; $i <= 5; $i++) { ?>
				<div
					class="carousel-dot <?= $i == 1 ? 'active' : '' ?>"
					data-page="<?= $i ?>"
				></div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
