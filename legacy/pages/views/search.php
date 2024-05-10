<?php

use App\Models\Ecommerce\Design;
use Legacy\Classes\Ecommerce\Seller;

?>

{% block main %}

<section id="page-header" class="center-items">
	<div class="page-background bgr" <?= background_image('bgr.jpg') ?>></div>

	<form method="GET" id="searcher" class="px-4">
		<div id="srch-inp-cell" class="">
			<input type="text" class="input" id="srch-inp" name="search" placeholder="Cerca articolo, negozio o tag" maxlength="30">
		</div>

		<div id="srch-btn-cell" class="">
			<button type="submit" id="srch-btn" class="center-content">
				<span class="icon txt-wht"><?= embed_icon('actions/search') ?></span>
			</button>
		</div>
	</form>
</section>

<div class="main-wrap pad-t-4 pad-b-5">
	<h2 id="title" class="mar-b-3">Cerca: <span class="word txt-reg"><?= $search ?></span></h2>

	<?php if ($searchN) { ?>

		<?php if ($sellN) { ?>

			<section id="shops" class="mar-b-4">
				<h3 class="section-heading">Negozi:</h3>
				<div class="gallery">

					<?php while ($row = $res_sell->fetch_assoc()) {

						$seller = new Seller($row['user_id'], 'name') ?>

						<a href="<?= $seller->clientUrl ?>" class="shop stack-items">
							<img src="<?= public_asset($seller->cover()) ?>" alt="" loading="lazy" class="cover img-fill">
							<img src="<?= public_asset($seller->icon) ?>" alt="" loading="lazy" class="icon">
							<p class="name txt-line"><?= $seller->name ?></p>
						</a>

					<?php } ?>

				</div>
			</section>

		<?php } ?>


		<?php if ($desN) { ?>

			<section id="items">
				<h3 class="section-heading">Prodotti:</h3>
				<div class="gallery">

					<?php while ($row = $res_des->fetch_assoc()) {
						$design = Design::find($row['id']);
						$product = $design->main_product;
					?>
						<div class="item">
							<a href="<?= $product->url() ?>">
								<img src="<?= $product->image() ?>" alt="" loading="lazy" class="image">
							</a>
							<h3 class="mar-t-1  txt-line txt-med"><?= $product->name ?></h3>
							<p class="mar-b-05 txt-08rem">
								by <a class="link" href="<?= $design->seller->url() ?>"><?= $design->seller->name ?></a>
							</p>
							<p class="">Su t-shirt e altri <strong><?= $design->productCount() ?> articoli</strong></p>
							<a class="" href="<?= $design->url() ?>">
								<p class="cta inlb br-b-2 br-c2 br-c1-h mt-2 txt-c2 txt-c1-h font-semibold uppercase">Scopri</p>
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