<section id="popular">
	<div class="main-wrap">
		<div class="section-heading-box">
			<h2 class="section-heading">Articoli popolari</h2>
			<a href="<?= client_url('/shop') ?>">
				<p class="more txt-rig">Esplora tutti</p>
			</a>
		</div>

		<section class="relative">
			<div
				id="carousel-popular"
				class="carousel mob-fluid"
				data-pos="1"
				data-loop="1"
			>

				<?php foreach ($products as $product) { ?>

				<div class="carousel-box">
					<?= generate_item_box($product) ?>
				</div>

				<?php } ?>

			</div>

			<div
				class="carousel-dots"
				data-tar="#carousel-popular"
			>
				<?php for ($i = 1; $i <= 5; $i++) { ?>
				<div
					class="carousel-dot <?= $i == 1 ? 'active' : '' ?>"
					data-page="<?= $i ?>"
				></div>
				<?php } ?>
			</div>
		</section>
	</div>
</section>
