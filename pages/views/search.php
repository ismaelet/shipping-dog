<?php

use Ecommerce\Design;
use Ecommerce\Seller;

?>

{% block main %}

<section id="page-header" class="center-items">
	<div class="page-background bgr" <?= background_image('bgr.jpg') ?>></div>

	<form method="GET" id="searcher" class="pad-h-2">
		<div id="srch-inp-cell" class="">
			<input type="text" id="srch-inp" name="search" placeholder="Cerca articolo, negozio o tag" maxlength="30">
		</div>

		<div id="srch-btn-cell" class="">
			<button type="submit" id="srch-btn" class="center-content">
				<span class="icon bgr-wht" <?= icon_svg('actions/search') ?>></span>
			</button>
		</div>
	</form>
</section>

<div class="main-wrap pad-t-4 pad-b-5">
	<h2 id="title" class="mar-b-3">Cerca: <span class="word txt-reg"><?= $search ?></span></h2>

	<?php if ($searchN) { ?>

		<?php if ($sellN) { ?>

			<section id="shops" class="mar-b-4">
				<h3 class="section-title">Negozi:</h3>
				<div class="gallery">

					<?php while ($row = $res_sell->fetch_assoc()) {

						$seller = new Seller($row['userID'], 'name') ?>

						<a href="<?= $seller->clientUrl ?>" class="shop stack-items">
							<img src="<?= client_path($seller->cover[1]) ?>" alt="" loading="lazy" class="cover img-fill">
							<img src="<?= client_path($seller->icon) ?>" alt="" loading="lazy" class="icon">
							<p class="name txt-line"><?= $seller->name ?></p>
						</a>

					<?php } ?>

				</div>
			</section>

		<?php } ?>


		<?php if ($desN) { ?>

			<section id="items">
				<h3 class="section-title">Prodotti:</h3>
				<div class="gallery">

					<?php while ($row = $res_des->fetch_assoc()) {
						$design = new Design($row['ID']);
						$product = $design->mainProduct;
					?>
						<div class="item">
							<a href="<?= $product->clientUrl ?>">
								<img src="<?= client_path($product->image) ?>" alt="" loading="lazy" class="image">
							</a>
							<h3 class="mar-t-1 txt-1rem txt-line txt-med"><?= $product->name ?></h3>
							<p class="mar-b-05 txt-08rem">
								by <a class="link" href="<?= $design->seller->clientUrl ?>"><?= $design->seller->name ?></a>
							</p>
							<p class="">Su t-shirt e altri <strong><?= $design->productCount ?> articoli</strong></p>
							<a class="" href="<?= $design->url ?>">
								<p class="cta inlb br-b-2 br-c2 br-c1-h mar-t-05 txt-c2 txt-c1-h txt-bold txt-upp">Scopri</p>
							</a>
						</div>

					<?php } ?>

				</div>
			</section>

		<?php } ?>

	<?php } else { ?>

		<p class="srch_empty">Ci dispiace, la ricerca non ha prodotto risultati.</p>

	<?php } ?>

</div>

{% end main %}