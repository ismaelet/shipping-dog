<?php

use App\Models\Ecommerce\Product;
use Legacy\View\Components\Interactions\LikeButton;
use Legacy\Classes\Ecommerce\Product\Color as LegacyProductColor;
use Legacy\Classes\Ecommerce\Product\Gender;
use Legacy\Classes\Ecommerce\Product\Position as LegacyProductPosition;
use Legacy\Classes\Ecommerce\Product\Size as LegacyProductSize;
use Legacy\Classes\Ecommerce\Product\Type as LegacyProductType;

?>

<div class="main-wrap pad-t-3 item-type--<?= $legacyProduct->type->slug ?>" id="item" data-id="<?= $legacyProduct->id ?>" data-hroot="<?= CLIENT_ROOT ?>">

	<!-- Warning -->
	<?php

	$warningBanner = warning_banner();

	if ($warningBanner) { ?>

		<p id="news" class="mar-b-1 <?= $warningBanner['color'] ?>"><?= $warningBanner['text'] ?></p>

	<?php } ?>

	<form action="<?= route('order-items.store') ?>" method="POST" class="content grid gap3 al-top">

		<input type="hidden" name="product_id" value="<?= $product->id ?>">

		<div id="left-panel" class="mar-h-auto">

			<!-- Preview image -->
			<section id="img-sec">

				<div id="img-sqr" class="mar-h-auto">

					<div id="item-img-box">

						<!-- Image -->
						<img class="img-zoom img-fill js-main-image" id="item-img" src="<?= $legacyProduct->clientFullImage ?>" alt="">

						<!-- Logos on the image -->
						<?php if ($legacyProduct->hasLogos && $legacyProduct->logoGroup->preview) { ?>

							<img class="logo-field__preview-logo js-logo-image" alt="">

						<?php } ?>

						<!-- Loading icon on image change -->
						<div id="img-load" class="bgr">
							<div id="load-ico">
								<?= embed_icon('loading') ?>
							</div>
						</div>

						<!-- Zoom icon on mobile -->
						<div id="img-zoom-icon" class="img-zoom icon-box clickable">
							<?= embed_icon('search') ?>
						</div>

						<!-- Love button -->
						<?= LikeButton::render(['design_id' => $legacyProduct->design_id]) ?>

					</div>

				</div>

				<!-- Zoom on mobile -->
				<div id="img-zoom-bgr">
					<div id="img-zoom-box">
						<img class="js-main-image" id="img-zoom" src="<?= $legacyProduct->clientFullImage ?>">
					</div>
				</div>

			</section>

			<!-- More products with the same design -->
			<?php if (!$legacyProduct->isCustom) { ?>

				<section id="type-sec">
					<!-- Types -->
					<div id="type-pan">
						<div id="item-type-titles" class="flex gap1 pad-t-2 mar-b-1">

							<?php if ($relatedGarmentCount) { ?>

								<h3 class="item-type-title clickable txt-bold inlb marr1 <?php if ($legacyProduct->type->category == 'c' || !$relatedAccessoryCount) echo 'active' ?>" data-tar="#more-clothing">Abbigliamento</h3>

							<?php } ?>

							<?php if ($relatedAccessoryCount) { ?>

								<h3 class="item-type-title clickable txt-bold inlb <?php if ($legacyProduct->type->category == 'm') echo 'active' ?>" data-tar="#more-accessories">Accessori</h3>

							<?php } ?>

						</div>

						<!-- More clothing -->
						<?php if ($relatedGarmentCount) { ?>

							<div id="more-clothing" class="type-slide <?php if ($legacyProduct->type->category == 'c' || !$relatedAccessoryCount) echo 'active' ?>">

								<div id="type-slideC" data-pos="1" class="carousel mob-fluid">

									<?php foreach ($relatedGarments as $relatedProduct) { ?>

										<div class="carousel-box">
											<a href="<?= $relatedProduct->clientUrl ?>">
												<img src="<?= $relatedProduct->clientImage ?>" class="img-fill">
											</a>
										</div>

									<?php } ?>

								</div>

								<?php if ($relatedGarmentCount > 3) { ?>

									<div class="carousel-arrow left disabled" data-direction="left" data-tar="#type-slideC" style="--icon-size: 50%; color: #202020;">
										<?= embed_icon('navigation/arrow-simple-left') ?>
									</div>

									<div class="carousel-arrow right" data-direction="right" data-tar="#type-slideC" style="--icon-size: 50%; color: #202020;">
										<?= embed_icon('navigation/arrow-simple-right') ?>
									</div>

								<?php } ?>

							</div>

						<?php } ?>

						<!-- More accessories -->
						<?php if ($relatedAccessoryCount) { ?>

							<div id="more-accessories" class="type-slide <?php if ($legacyProduct->type->category == 'm') echo 'active' ?>">

								<div id="type-slideM" data-pos="1" class="carousel mob-fluid">

									<?php foreach ($relatedAccessories as $relatedProduct) { ?>

										<div class="carousel-box">
											<a href="<?= $relatedProduct->clientUrl ?>">
												<img src="<?= $relatedProduct->clientImage ?>" class="img-fill">
											</a>
										</div>

									<?php } ?>

								</div>

								<?php if ($relatedAccessoryCount > 3) { ?>

									<div class="carousel-arrow left disabled" data-direction="left" data-tar="#type-slideM">
										<div class="icon"></div>
									</div>

									<div class="carousel-arrow right" data-direction="right" data-tar="#type-slideM">
										<div class="icon"></div>
									</div>

								<?php } ?>

							</div>

						<?php } ?>

					</div>
				</section>

			<?php } ?>

		</div>

		<section id="right-panel" class="grid">
			<div class="">
				<!-- Title -->
				<h1 id="title"><?= $product->name ?></h1>

				<p id="author"><?= $product->type->name ?>
					by <a class="txt-c1-h" href="<?= $product->seller->url() ?>">
						<?= $product->seller->name ?>
					</a>
				</p>

				<!-- Price -->
				<div class="price-block">

					<?php if ($product->userIsOwner()) { ?>

						<!-- Price when there's a discount -->
						<p class="price price-old heading-like"><?= $product->fullPriceString() ?></p>
						<p class="price price-promo heading-like"><?= $product->sellerPriceString() ?></p>

						<div class="flex-group">
							<p class="price-txt">Prezzo scontato<?= $product->userIsOwner() ? ' solo per te' : '' ?></p>

							<?php if ($product->userIsOwner()) {
								info_box("Questo prezzo speciale è visibile a te, perché sei il proprietario di questo articolo. I clienti vedranno solo il prezzo normale di <span class=''>" . $product->fullPriceString() . "</span>");
							} ?>
						</div>

					<?php } else { ?>

						<!-- Normal price -->
						<p class="price txt-bold price-def heading-like js-price"><?= $product->customerPriceString() ?></p>

					<?php } ?>

				</div>

				<!-- Colors -->
				<?php if (!empty($legacyProduct->type->availableColorSlugs)) {
					$res = \Legacy\System\Classes\LegacyDB::select('id FROM items_colors ORDER BY reorder ASC, name ASC');
				?>

					<section id="color" class="field <?php if (count($legacyProduct->availableColorSlugs) < 2) echo 'hide' ?>">
						<p class="lbl">Colore</p>
						<div class="grid">

							<?php while ($row = $res->fetch_assoc()) {

								$color = new LegacyProductColor($row['id']);

								if ((in_array($color->slug, $legacyProduct->type->availableColorSlugs) || $legacyProduct->isCustom) && in_array($color->slug, $legacyProduct->availableColorSlugs)) { ?>

									<input type="radio" class="cbx" id="cbx-color-<?= $color->slug ?>" name="color_id" value="<?= $color->id ?>" data-color="<?= $color->slug ?>" data-id="<?= $color->id ?>" <?php if ($color->slug == $legacyProduct->color->slug) echo 'checked' ?>>

									<label for="cbx-color-<?= $color->slug ?>" class="color-box">

										<div class="color-lbl <?= $color->bicolor ? 'bicolor' : false ?>" style="background-color: #<?= $color->hex ?>">
											<?php if ($color->bicolor) { ?>
												<div class="bicolor-fill" style="background-color: #<?= $color->hex ?>"></div>
												<div class="bicolor-fill" style="background-color: #<?= $color->secondHex ?>"></div>
											<?php } ?>
										</div>

									</label>

								<?php } ?>

							<?php } ?>

						</div>

					</section>

				<?php } ?>

				<!-- Size -->
				<?php if (!empty($legacyProduct->type->sizes)) {
					$new = true;
					$size_table = \Legacy\System\Classes\LegacyDB::exists('items_types_measures WHERE type_id=?', [$legacyProduct->type->id]) && $product->type->category == 'c';
					$res = \Legacy\System\Classes\LegacyDB::select('id FROM items_sizes');
				?>

					<section id="size" class="field">
						<p class="lbl"><?= $product->type->category == 'c' ? 'Taglia' : 'Dimensione' ?>

							<?php if (!$legacyProduct->isCustom && $size_table) { ?>
								<span class="lbox-open clickable info txt-c1-h" data-tar="#size-lbox">info taglie</span>
							<?php } ?>

						</p>

						<div class="grid">

							<?php while ($row = $res->fetch_assoc()) {

								$sizeM = new LegacyProductSize($row['id']);

								if (in_array($sizeM->slug, $legacyProduct->type->sizes)) { ?>

									<div class="size_box">
										<input type="hidden" value="<?= $sizeM->price ?>">
										<input type="radio" class="cbx" id="cbx-type_<?= $sizeM->slug ?>" name="size_id" value="<?= $sizeM->id ?>" <?php if ($new) {
																																						echo 'checked';
																																						$new = false;
																																					} ?>>

										<label class="size_lbl" for="cbx-type_<?= $sizeM->slug ?>">
											<h1 class="size_txt txt-upp"><?= $sizeM->name ?></h1>
										</label>
									</div>
							<?php }
							} ?>
						</div>

						<!-- Size table -->
						<?php if ($size_table) {
							include_once('measure-table.php');
						} ?>

					</section>

				<?php } ?>

				<!-- Gender -->
				<?php if ($legacyProduct->type->genderId) {
					$new = true;

					$genders = [];
					$genders[] = $legacyProduct->type->genderId;
					if ($legacyProduct->type->sibling) $genders[] = (new LegacyProductType($legacyProduct->type->sibling))->genderId;

				?>

					<section id="gender" class="field">

						<div class="">
							<p class="lbl">Modello</p>

							<?php if ($legacyProduct->type->sibling && $legacyProduct->type->unisex) info_box("Qui puoi scegliere se vedere l'anteprima del prodotto su un modello maschile o femminile, ma ha scopo puramente illustrativo, in quanto tutte le taglie per questo articolo sono unisex.") ?>
						</div>

						<div class="grid">

							<?php foreach ($genders as $key => $genderId) {
								$gender = new Gender($genderId);
								$relatedProduct = null;

								if ($key && $product->design_id) {
									$relatedProduct = Product::where('design_id', $product->design_id)
										->where('type_id', $product->type->sibling)
										->first();
								}

								if (!$key || $relatedProduct) {
							?>

									<div class="gend_box">
										<input type="radio" class="cbx" id="cbx-gend_<?= $gender->slug ?>" name="gender_id" value="<?= $gender->id ?>" <?= !$relatedProduct ? 'checked' : '' ?>>

										<a <?= $relatedProduct ? 'href="' . $relatedProduct->url() . '"' : '' ?> class="gend_lbl">
											<h1 class="gend_txt"><?= $gender->name ?></h1>
										</a>
									</div>

							<?php }
							} ?>

						</div>

					</section>

				<?php } ?>

				<!-- Position -->
				<div id="position" class="field <?= count($legacyProduct->availablePositionIds) < 2 || $legacyProduct->isCustom ? 'hidden' : '' ?>">

					<p class="lbl">Posizione della stampa</p>

					<div class="data-area">

						<?php foreach ($legacyProduct->availablePositionIds as $key => $position_id) {
							$position = new LegacyProductPosition($position_id);
						?>

							<label class="data-selector data-selector--position">
								<input type="radio" class="js-change-position" name="position_id" value="<?= $position->id ?>" <?php if (!$key) echo 'checked' ?>>
								<p class="data-selector__label"><?= $position->name ?></p>
							</label>

						<?php } ?>

					</div>
				</div>

				<!-- Logos -->
				<?php if ($legacyProduct->hasLogos) { ?>

					<div class="logo-field field">
						<p class="lbl"><?= $legacyProduct->logoGroup->name ?></p>

						<div class="grid-flow" data-spacing="400">
							<div class="logo-field__list">

								<!-- Checkbox for no logo -->
								<?php if (!$legacyProduct->logoGroup->forced) { ?>

									<label class="logo-field__item">
										<input type="radio" class="js-logo" name="logo_id" value="0" checked>
										<?= embed_icon('close') ?>
									</label>

								<?php } ?>

								<!--checkboxs for extras-->
								<?php foreach ($product->logos() as $logoKey => $logo) { ?>

									<label class="logo-field__item" title="<?= $logo->name ?>">
										<input type="radio" class="js-logo" name="logo-id" value="<?= $logo->id ?>" <?= $legacyProduct->logoGroup->forced && !$logoKey ? 'checked' : null ?>>
										<img class="img-fill" src="<?= $logo->image() ?>" alt="">
									</label>

								<?php } ?>

							</div>

							<div class="logo-field__preview js-logo-image-background" class="<?= $legacyProduct->logoGroup->forced ? 'active' : null ?>">
								<div class="logo-field__preview-wrapper grid-flow stack-content">
									<img class="logo-field__preview-image js-front-image" src="<?= $legacyProduct->clientFullImage ?>">
									<img class="logo-field__preview-logo js-logo-image" src="" alt="">
								</div>
							</div>
						</div>
					</div>

				<?php } ?>

				<!-- Pencilcase back -->
				<?php if (false && $legacyProduct->type->slug == 'cas') { ?>

					<div class="field">
						<p class="lbl">Retro</p>

						<div>
							<input type="checkbox" id="cbx-back_0" class="cbx" name="cbx-back[]" value="0" checked>
							<label class="back_lbl" id="cbx-back_0" for="cbx-back_0" style="background-image: url(/uploads/shops/logos/0.png);"></label>

							<input type="checkbox" id="cbx-back_1" class="cbx" name="cbx-back[]" value="1">
							<label class="back_lbl" for="cbx-back_1" style="background-image: url(/app/assets/icons/correct/apply1_wht.png)"></label>
						</div>

						<div id="back_img_cont">
							<div class="bgr" style="background-image: url(/uploads/product_types/mockups/bgr/gry.png);"></div>
							<div id="back_img" class="bgr" style="background-image: url(/uploads/product_types/mockups/cas/wht_m.png);"></div>
						</div>
					</div>

				<?php } ?>

				<!-- Quantity -->
				<div id="qty_cont" class="field hidden">
					<p class="lbl">Quantità</p>

					<input type="text" id="quantity" name="quantity" value="1">
					<div id="qty_box">
						<h1 id="qty_pls" class="qty_edit on">+</h1>
						<h1 id="qty_min" class="qty_edit off">-</h1>
					</div>
				</div>

				<!-- Add to cart -->
				<div class="mar-t-4">

					<?php if (($legacyProduct->statusId && !$legacyProduct->deleted) || $owner) { ?>

						<button type="submit" id="submit" class="add-to-cart btn button--full-width">
							<?= embed_icon('cart') ?>
							Aggiungi al carrello
						</button>

					<?php } ?>

					<?php if (!$legacyProduct->isCustom && $product->blankProduct()) { ?>

						<p class="mar-t-1">Vuoi acquistare il prodotto senza grafica? <a class="txt-bold txt-c-title" href="<?= $product->blankProduct()->url() ?>">Clicca qui</a>.</p>

					<?php } ?>

				</div>
			</div>
		</section>
	</form>
</div>