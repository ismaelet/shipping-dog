<section id="designs">
	<div class="main-wrap">
		<div class="section-title-box">
			<h2 class="section-title">Design popolari</h2>

			<a href="<?= client_url('/shop') ?>">
				<p class="more txt-rig">Esplora tutti</p>
			</a>
		</div>

		<div class="pos-rel">
			<section id="carousel-designs" class="carousel mob-fluid" data-pos="1" data-loop="1">

				<?php foreach ($popularDesigns as $design) { ?>

					<div class="carousel-box">
						<a href="<?= $design->url ?>">
							<img src="<?= $design->clientImage ?>" alt="" class="img-fill">
						</a>

						<h3 class="name mar-t-1 txt-line"><?= $design->name ?></h3>

						<p class="seller txt-line">by <a href="<?= $design->seller->clientUrl ?>" class="txt-c1-h"><?= $design->seller->name ?></a></p>
						<p class="mar-t-05 txt-sbold txt-c2">
							<a href="<?= $design->url ?>">
								<?= $design->itemN ?> prodotti
							</a>
						</p>
					</div>

				<?php } ?>

			</section>

			<div class="carousel-dots" data-tar="#carousel-designs">
				<?php for ($i = 1; $i <= 5; $i++) { ?>
					<div class="carousel-dot <?= $i == 1 ? 'active' : '' ?>" data-page="<?= $i ?>"></div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>