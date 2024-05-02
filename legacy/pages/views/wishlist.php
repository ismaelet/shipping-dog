<?php

use App\Models\Ecommerce\Design;

?>

{% block main %}

<section id="page-header" class="py-16 text-center">
	<h1 class="text-main-heading uppercase tracking-widest"><span class="txt-c1">Wish</span>list</h1>
</section>

<?php if ($selN) { ?>

	<div class="main-wrap pad-t-4 pad-b-5">

		<div id="products" class="">

			<?php while ($row = $res_sel->fetch_assoc()) {

				$design = EcommerceDesign::find($row['design_id']);
			?>

				<div class="product">

					<a class="img-box" href="<?= $design->url() ?>">
						<img src="<?= ($design->image()) ?>" alt="" loading="lazy">
					</a>

					<p class="name mar-t-1 text-neutral-700"><?= $design->name ?></p>

					<p class="shop">by <a class="txt-c1-h" href="<?= $design->url() ?>"><?= $design->seller->name ?></a></p>

				</div>

			<?php } ?>

		</div>

	</div>

<?php } else { ?>

	<div class="main-wrap">
		<div id="empty-box" class="txt-cen">

			<div id="empty-icon" class="inlb mar-b-2"></div>

			<p id="empty-txt">Nessun articolo preferito. Cerca nel nostro <a href="/shop" class="txt-bold txt-c3 txt-c1-h">negozio</a> un prodotto che possa piacerti.</p>

		</div>
	</div>

<?php } ?>

{% end main %}