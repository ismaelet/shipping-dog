@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/home-shops.css') }}"
	>
@endpushOnce

<section id="shops">
	<div class="content-grid">
		<div class="section-heading-box">
			<h2 class="section-heading">Negozi in evidenza</h2>
		</div>

		<div class="relative">
			<section
				id="carousel-shops"
				class="carousel mob-fluid"
				data-pos="1"
				data-loop="1"
				style="--itemN: <?= $sellers->count() ?>;"
			>

				<?php foreach ($sellers as $seller) { ?>

				<div class="carousel-box">
					<a
						href="<?= $seller->url() ?>"
						class="shop"
					>
						<img
							src="<?= $seller->cover() ?>"
							loading="lazy"
							class="cover img-fill"
						>
						<div class="content pad-h-2 pad-b-2 text-center">
							<img
								src="<?= $seller->icon() ?>"
								loading="lazy"
								class="logo"
							>

							<div class="data">
								<p class="name txt-line"><?= $seller->name ?></p>
								<p class="text mar-t-1 line-clamp-2"><?= $seller->description ?></p>
							</div>

							<div class="footer mt-8">
								<h3 class="explore">Scopri</h3>
							</div>
						</div>
					</a>
				</div>

				<?php } ?>

			</section>

			<div
				class="carousel-dots"
				data-tar="#carousel-shops"
			>

				<?php for ($i = 1; $i <= 4; $i++) { ?>
				<div
					class="carousel-dot <?= $i == 1 ? 'active' : '' ?>"
					data-page="<?= $i ?>"
				></div>
				<?php } ?>

			</div>
		</div>
	</div>
</section>
