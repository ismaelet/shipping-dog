<section id="shops">
	<div class="main-wrap">
		<h2 class="section-title">Negozi in evidenza</h2>

		<div class="pos-rel">
			<section id="carousel-shops" class="carousel mob-fluid" data-pos="1" data-loop="1" style="--itemN: <?= count($sellers) ?>;">

				<?php foreach ($sellers as $seller) { ?>

					<div class="carousel-box">
						<a href="<?= $seller->clientUrl ?>" class="shop">
							<img src="<?= client_path($seller->cover[1]) ?>" loading="lazy" class="cover img-fill">
							<div class="content txt-cen pad-h-2 pad-b-2">
								<img src="<?= client_path($seller->icon) ?>" loading="lazy" class="logo">

								<div class="data">
									<p class="name txt-line"><?= $seller->name ?></p>
									<p class="text mar-t-1 txt-line2"><?= $seller->description ?></p>
								</div>

								<div class="footer mar-t-2">
									<h3 class="explore">Scopri</h3>
								</div>
							</div>
						</a>
					</div>

				<?php } ?>

			</section>

			<div class="carousel-dots" data-tar="#carousel-shops">

				<?php for ($i = 1; $i <= 4; $i++) { ?>
					<div class="carousel-dot <?= $i == 1 ? 'active' : '' ?>" data-page="<?= $i ?>"></div>
				<?php } ?>

			</div>
		</div>
	</div>
</section>