<?php

use Ecommerce\Design;

?>

{% block main %}

<section id="page-header" class="pad-v-7">

	<div class="bgr" <?= background_image(IMG_DIR . 'blank.png') ?>></div>

	<h1 class="txt-upp txt-cen txt-ls2"><span class="txt-c1">Wish</span>list</h1>

</section>

<?php if ($selN) { ?>

	<div class="main-wrap pad-t-4 pad-b-5">

		<div id="products" class="">

			<?php while ($row = $res_sel->fetch_assoc()) {

				$design = new Design($row['desID']);
			?>

				<div class="product">

					<a class="img-box" href="<?= $design->url ?>">
						<img src="<?= client_path($design->image) ?>" alt="" loading="lazy">
					</a>

					<p class="name mar-t-1"><?= $design->name ?></p>

					<p class="shop">by <a class="txt-c1-h" href="<?= $design->url ?>"><?= $design->seller->name ?></a></p>

				</div>

			<?php } ?>

		</div>

	</div>

<?php } else { ?>

	<div class="main-wrap">
		<div id="empty-box" class="txt-cen">

			<div id="empty-icon" class="inlb mar-b-2"></div>

			<p id="empty-txt">Nessun articolo preferito. Cerca nel nostro <a href="<?= CLIENT_ROOT ?>/shop" class="txt-bold txt-c3 txt-c1-h">negozio</a> un prodotto che possa piacerti.</p>

		</div>
	</div>

<?php } ?>

{% end main %}