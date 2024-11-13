<?php

use App\Models\Ecommerce\Product;
use Legacy\View\Components\Interactions\LikeButton;
use Legacy\Classes\Ecommerce\Product\Size as LegacyProductSize;

?>

<div class="main-wrap pad-t-3 item-type--<?= $product->type->tag ?>" id="item" data-id="<?= $product->id ?>" data-hroot="/legacy" data-seller-id="<?= $product->seller->id ?>">

	<form action="<?= route('order-items.store') ?>" method="POST" class="content grid gap3 al-top">

		<input type="hidden" name="product_id" value="<?= $product->id ?>">

		<div id="left-panel" class="mar-h-auto">

			<!-- Preview image -->
			<section id="img-sec">

				<div id="img-sqr" class="mar-h-auto">

					<div id="item-img-box">

						<!-- Image -->
						<img class="img-zoom img-fill js-main-image" id="item-img" src="<?= $product->image(1024) ?>" alt="">

						<!-- Logos on the image -->
						<?php if ($product->logoGroup() && $product->logoGroup()->preview) { ?>

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
						<?= LikeButton::render(['design' => $product->design]) ?>

					</div>

				</div>

				<!-- Zoom on mobile -->
				<div id="img-zoom-bgr">
					<div id="img-zoom-box">
						<img class="js-main-image" id="img-zoom" src="<?= $product->image(1024) ?>">
					</div>
				</div>

			</section>

			<!-- More products with the same design -->
			<?php if (!$product->isCustom()) { ?>

				<section id="type-sec">
					<!-- Types -->
					<div id="type-pan">
						<div id="item-type-titles" class="flex gap1 pad-t-2 mar-b-1">

							<?php if ($relatedGarmentCount) { ?>

								<h3 class="item-type-title clickable font-semibold inlb marr1 <?php if ($product->type->category == 'c' || !$relatedAccessoryCount) echo 'active' ?>" data-tar="#more-clothing">Abbigliamento</h3>

							<?php } ?>

							<?php if ($relatedAccessoryCount) { ?>

								<h3 class="item-type-title clickable font-semibold inlb <?php if ($product->type->category == 'm') echo 'active' ?>" data-tar="#more-accessories">Accessori</h3>

							<?php } ?>

						</div>

						<!-- More clothing -->
						<?php if ($relatedGarmentCount) { ?>

							<div id="more-clothing" class="type-slide <?php if ($product->type->category == 'c' || !$relatedAccessoryCount) echo 'active' ?>">

								<div id="type-slideC" data-pos="1" class="carousel mob-fluid">

									<?php foreach ($relatedGarments as $relatedProduct) { ?>

										<div class="carousel-box">
											<a href="<?= $relatedProduct->url() ?>">
												<img src="<?= $relatedProduct->image() ?>" class="img-fill">
											</a>
										</div>

									<?php } ?>

								</div>

								<?php if ($relatedGarmentCount > 3) { ?>

									<div class="carousel-arrow left disabled" data-direction="left" data-tar="#type-slideC" style="--icon-size: 50%; color: #202020;">
										<?= embed_icon('arrow-simple-left') ?>
									</div>

									<div class="carousel-arrow right" data-direction="right" data-tar="#type-slideC" style="--icon-size: 50%; color: #202020;">
										<?= embed_icon('arrow-simple-right') ?>
									</div>

								<?php } ?>

							</div>

						<?php } ?>

						<!-- More accessories -->
						<?php if ($relatedAccessoryCount) { ?>

							<div id="more-accessories" class="type-slide <?php if ($product->type->category == 'm') echo 'active' ?>">

								<div id="type-slideM" data-pos="1" class="carousel mob-fluid">

									<?php foreach ($relatedAccessories as $relatedProduct) { ?>

										<div class="carousel-box">
											<a href="<?= $relatedProduct->url() ?>">
												<img src="<?= $relatedProduct->image() ?>" class="img-fill">
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
						<p class="price font-semibold price-def heading-like js-price"><?= $product->customerPriceString() ?></p>

					<?php } ?>

				</div>

				<!-- Colors -->
				<?php if ($productColors) { ?>

					<section id="color" class="field <?php if ($productColorCount < 2) echo 'hide' ?>">
						<p class="lbl">Colore</p>
						<div class="grid">

							<?php foreach ($productColors as $color) {

								if (
									($product->type->colors->contains($color->id) || $product->isCustom())
									&& in_array($color->tag, $productColors->pluck('tag')->toArray())
								) { ?>

									<input type="radio" class="cbx" id="cbx-color-<?= $color->tag ?>" name="color_id" value="<?= $color->id ?>" data-color="<?= $color->tag ?>" data-id="<?= $color->id ?>" <?php if ($color->id == $product->color?->id) echo 'checked' ?>>

									<label for="cbx-color-<?= $color->tag ?>" class="color-box" title="<?= $color->name ?>">

										<div class="color-lbl" style="background-color: <?= $color->primary_color_hex ?>">

											<?php if ($color->secondary_color_hex) { ?>
												<span class="secondary-color" style="background-color: <?= $color->secondary_color_hex ?>"></span>
											<?php } ?>

										</div>
									</label>

								<?php } ?>

							<?php } ?>

						</div>

					</section>

				<?php } ?>

				<!-- Size -->
				<?php if (!empty($product->type->sizes)) {
					$new = true;
					$size_table = \Legacy\System\Classes\LegacyDB::exists('items_types_measures WHERE type_id=?', [$product->type->id]) && $product->type->category == 'c';
					$res = \Legacy\System\Classes\LegacyDB::select('id FROM items_sizes');
				?>

					<section id="size" class="field">
						<p class="lbl"><?= $product->type->category == 'c' ? 'Taglia' : 'Dimensione' ?>

							<?php if (!$product->isCustom() && $size_table) { ?>
								<span class="lbox-open clickable info txt-c1-h" data-tar="#size-lbox">info taglie</span>
							<?php } ?>

						</p>

						<div class="grid">

							<?php while ($row = $res->fetch_assoc()) {

								$sizeM = new LegacyProductSize($row['id']);
								$sizes = $product->type->sizes->pluck('tag')->toArray();

								if ($sizes && in_array($sizeM->slug, $sizes)) { ?>

									<div class="size_box">
										<input type="hidden" value="<?= $sizeM->price ?>">
										<input type="radio" class="cbx" id="cbx-type_<?= $sizeM->slug ?>" name="size_id" value="<?= $sizeM->id ?>" <?php if ($new) {
																																						echo 'checked';
																																						$new = false;
																																					} ?>>

										<label class="size_lbl" for="cbx-type_<?= $sizeM->slug ?>">
											<h1 class="size_txt uppercase"><?= $sizeM->name ?></h1>
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
				<?php if ($product->type->gender) {
					$new = true;

					$genders = [];
					$genders[] = $product->type->gender;
					$sibling = $product->type->sibling;

					if ($sibling) {
						$genders[] = $sibling->gender;

				?>

						<section id="gender" class="field">

							<div class="">
								<p class="lbl">Modello</p>

								<?php if ($product->type->unisex) info_box("Qui puoi scegliere se vedere l'anteprima del prodotto su un modello maschile o femminile, ma ha scopo puramente illustrativo, in quanto tutte le taglie per questo articolo sono unisex.") ?>
							</div>

							<div class="grid">

								<?php foreach ($genders as $key => $gender) {
									$relatedProduct = null;

									if ($key && $product->design_id) {
										$relatedProduct = Product::where('design_id', $product->design_id)
											->where('type_id', $sibling->id)
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

				<?php }
				} ?>

				<!-- Position -->
				<?php
				$productPositions = $product->positions();
				?>
				<div id="position" class="field <?= count($productPositions) < 2 || $product->isCustom() ? 'hidden' : '' ?>">

					<p class="lbl">Posizione della stampa</p>

					<div class="data-area">

						<?php foreach ($productPositions as $key => $position) {
						?>

							<label class="data-selector data-selector--position">
								<input type="radio" class="js-change-position" name="position_id" value="<?= $position->id ?>" <?php if (!$key) echo 'checked' ?>>
								<p class="data-selector__label"><?= $position->name ?></p>
							</label>

						<?php } ?>

					</div>
				</div>

				<!-- Logos -->
				<?php if ($product->logoGroup()) { ?>

					<div class="logo-field field">
						<p class="lbl"><?= $product->logoGroup()->name ?></p>

						<div class="grid-flow" data-spacing="400">
							<div class="logo-field__list">

								<!-- Checkbox for no logo -->
								<?php if (!$product->logoGroup()->forced) { ?>

									<label class="logo-field__item">
										<input type="radio" class="js-logo" name="logo_id" value="0" checked>
										<?= embed_icon('close') ?>
									</label>

								<?php } ?>

								<!--checkboxs for extras-->
								<?php foreach ($product->logos() as $logoKey => $logo) { ?>

									<label class="logo-field__item" title="<?= $logo->name ?>">
										<input type="radio" class="js-logo" name="logo_id" value="<?= $logo->id ?>" <?= $product->logoGroup()->forced && !$logoKey ? 'checked' : null ?>>
										<img class="img-fill" src="<?= $logo->image() ?>" alt="">
									</label>

								<?php } ?>

							</div>

							<div class="logo-field__preview js-logo-image-background <?= $product->logoGroup()->forced ? 'active' : null ?>">

								<?php if ($product->logoGroup()->logos?->first()?->customPreview($product->type_id)) { ?>

									<img class="logo-field__static-preview" src="<?= $product->logoGroup()->logos?->first()?->customPreview($product->type_id) ?>" alt="">

								<?php } else { ?>

									<div class="logo-field__preview-wrapper grid-flow stack-content">

										<img class="logo-field__preview-image js-front-image" src="<?= $product->image(1024) ?>">

										<img class="logo-field__preview-logo js-logo-image" src="" alt="">

									</div>

								<?php } ?>
							</div>
						</div>
					</div>

				<?php } ?>

				<!-- Pencilcase back -->
				<?php if (false && $product->type->slug == 'cas') { ?>

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

					<p class="error-warning mb-2">A causa dei continui scioperi che stanno rallentando l'operatività dei centri logistici GLS, le consegne potrebbero subire dei ritardi di qualche giorno.<br><br>

						Ci scusiamo per il disguido ma questo non dipende dalla nostra volontà. Noi di Shipping Dog faremo tutto il possibile per limitare i danni causati da questo problema inatteso.
					</p>

					<button type="submit" id="submit" class="add-to-cart btn button--full-width">
						<?= embed_icon('cart') ?>
						Aggiungi al carrello
					</button>

					<?php if (!$product->isCustom() && $product->blankProduct()) { ?>

						<p class="mar-t-1">Vuoi acquistare il prodotto senza grafica? <a class="font-semibold txt-c-title" href="<?= $product->blankProduct()->url() ?>">Clicca qui</a>.</p>

					<?php } ?>

				</div>
			</div>
		</section>
	</form>
</div>