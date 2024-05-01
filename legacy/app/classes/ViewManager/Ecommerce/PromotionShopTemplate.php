<?php

namespace Legacy\Classes\ViewManager\Ecommerce;

use LegacyDB;
use Legacy\Classes\Ecommerce\Product\Type;
use Legacy\Classes\Ecommerce\ViewManager;

class PromotionShopTemplate {

	/*
	* Render methods
	*/
	public function render(object $shopData) {
		ob_start();
?>

		<form action="#store" method="GET" autocomplete="off" id="store">
			<input type="hidden" id="js-itemN" value="<?= $shopData->promotionCount ?>">

			<!-- Sort items -->
			<section id="shop-sort">
				<label>
					<input type="checkbox" class="sort-cbx hidden" name="sort" value="new" <?= $shopData->sortBy == 'new' ? 'checked' : null ?>>
					<p class="sort_txt clickable">Nuovi</p>
				</label>

				<p class="sort_txt">&middot;</p>

				<label>
					<input type="checkbox" class="sort-cbx hidden" name="sort" value="price" <?= $shopData->sortBy == 'price' ? 'checked' : null ?>>
					<p class="sort_txt clickable">Prezzo</p>
				</label>
			</section>

			<!-- Search bar -->
			<section id="shop-search">

				<p id="filter-text" onclick="$(this).toggleClass('open'); $(this).next().toggleClass('open');">
					Filtra la ricerca
					<span class="search-ddown-ico icon " <?= embed_icon('navigation/arrow1-down') ?>></span>
				</p>

				<div id="search-sec">
					<div id="search-mob-box" class="pad-b-3">
						<div class="mar-b-2">

							<div id="search-inp-box" class="grid al-mid">

								<input type="text" id="search-inp" class="" name="search" value="<?= $shopData->search ?>" placeholder="Cerca un prodotto" maxlength="30" onfocus="$(this).parent().addClass('focus');" onblur="$(this).parent().removeClass('focus');" autocomplete="off">

								<button type="submit" id="search-ico" class="clickable icon" <?= embed_icon('actions/search') ?>></button>

							</div>

						</div>

						<div class="srch-box">
							<h3 class="srch-title">Modello</h3>

							<!-- Men clothing -->
							<div>
								<input type="checkbox" id="gender-cbx-m" name="gender" class="gender-cbx hidden" value="m" <?php if ($shopData->gender == 'm') echo 'checked' ?>>

								<label class="gender-title-box" for="gender-cbx-m">
									<p class="gender-title">
										<span class="gender-ico icon " <?= embed_icon('navigation/arrow1-down') ?>></span>
										<span class="">Uomo</span>
									</p>
								</label>

								<div class="gender-ddown">
									<?php
									if ($shopData->id) $res = \Legacy\System\Classes\LegacyDB::select("
										id FROM product_types WHERE category='c' AND (gender=1 OR gender=3) AND hidden = 0 AND ID IN (SELECT type_id FROM items WHERE user_id = ? AND status = 1) ORDER BY id ASC", [$shopData->id]);

									else $res = \Legacy\System\Classes\LegacyDB::select("
										id FROM product_types WHERE category='c' AND (gender=1 OR gender=3) AND hidden = 0 AND ID IN (SELECT type_id FROM items WHERE status = 1) ORDER BY id ASC");

									while ($row = $res->fetch_assoc()) {

										$type = new Type($row['id']) ?>

										<label>
											<input type="checkbox" class="type-cbx hidden" name="type" value="<?= $type->slug ?>" <?php if ($shopData->gender == 'm' && $shopData->type == $type->slug) echo 'checked' ?>>
											<p class="type-title"><?= $type->names ?></p>
										</label>
									<?php } ?>
								</div>
							</div>

							<!-- Women clothing -->
							<div>
								<input type="checkbox" id="gender-cbx-f" name="gender" class="gender-cbx hidden" value="f" <?php if ($shopData->gender == 'f') echo 'checked' ?>>

								<label class="gender-title-box" for="gender-cbx-f">
									<p class="gender-title">
										<span class="gender-ico icon " <?= embed_icon('navigation/arrow1-down') ?>></span>
										<span class="">Donna</span>
									</p>
								</label>

								<div class="gender-ddown">
									<?php

									if ($shopData->id) {
										$res = \Legacy\System\Classes\LegacyDB::select("
										id FROM product_types WHERE category='c' AND (gender=2 OR gender=3) AND (hidden = 0 OR ID=115) AND ID IN (SELECT type_id FROM items WHERE user_id = ? AND status = 1) ORDER BY id ASC", [$shopData->id]);
									} else {
										$res = \Legacy\System\Classes\LegacyDB::select("
										id FROM product_types WHERE category='c' AND (gender=2 OR gender=3) AND (hidden = 0 OR ID=115) AND ID IN (SELECT type_id FROM items WHERE status = 1) ORDER BY id ASC");
									}

									while ($row = $res->fetch_assoc()) {
										$type = new Type($row['id']);
									?>

										<label>
											<input type="checkbox" class="type-cbx hidden" name="type" value="<?= $type->slug ?>" <?php if ($shopData->gender == 'f' && $shopData->type == $type->slug) echo 'checked' ?>>
											<p class="type-title"><?= $type->names ?></p>
										</label>
									<?php } ?>
								</div>
							</div>

							<!-- Accessories -->
							<div>
								<input type="checkbox" id="gender-cbx-u" name="gender" class="gender-cbx hidden" value="u" <?php if ($shopData->gender == 'u') echo 'checked' ?>>

								<label class="gender-title-box" for="gender-cbx-u">
									<p class="gender-title">
										<span class="gender-ico icon " <?= embed_icon('navigation/arrow1-down') ?>></span>
										<span class="">Accessori</span>
									</p>
								</label>

								<div class="gender-ddown">
									<?php

									if ($shopData->id) {
										$res = \Legacy\System\Classes\LegacyDB::select("
										id FROM product_types WHERE (category='m' OR ID=111) AND ID IN (SELECT type_id FROM items WHERE user_id = ? AND status = 1) ORDER BY name ASC", [$shopData->id]);
									} else {
										$res = \Legacy\System\Classes\LegacyDB::select("
										id FROM product_types WHERE (category='m' OR ID=111) AND ID IN (SELECT type_id FROM items WHERE status = 1) ORDER BY name ASC");
									}

									while ($row = $res->fetch_assoc()) {

										$type = new Type($row['id']) ?>

										<label>
											<input type="checkbox" class="type-cbx hidden" name="type" value="<?= $type->slug ?>" <?php if ($shopData->type == $type->slug) echo 'checked' ?>>
											<p class="type-title"><?= $type->names ?></p>
										</label>
									<?php } ?>
								</div>
							</div>

							<button type="submit" id="filter-type" class="hidden"></button>

							<?php if ($shopData->type || $shopData->gender) { ?>
								<p id="remove-filter-type" class="remove-filter clickable pad-t-1">
									<span class="txt-bold txt-blk">x</span>
									rimuovi filtro
								</p>
							<?php } ?>
						</div>

						<!-- Tags -->
						<?php if (!$shopData->id) {
							$res_tags = \Legacy\System\Classes\LegacyDB::select('tag FROM sys_tags WHERE hide=0 ORDER BY count DESC LIMIT 20') ?>

							<div class="srch-box gallery">
								<h3 class="srch-title pad-t-3 mar-b-05">Tag popolari</h3>
								<?php while ($row_tags = $res_tags->fetch_assoc()) { ?>
									<label>
										<input type="checkbox" name="tag" value="<?= $row_tags['tag'] ?>" class="tag-cbx hidden" <?php if ($row_tags['tag'] == $shopData->slug) echo 'checked' ?>>
										<p class="tag"><?= $row_tags['tag'] ?></p>
									</label>
								<?php } ?>

								<button type="submit" id="filter_tag" class="hidden"></button>

								<?php if ($shopData->slug) { ?>
									<p id="remove-filter-tag" class="remove-filter clickable pad-t-1">
										<span class="txt-bold txt-blk">x</span>
										rimuovi filtro
									</p>
								<?php } ?>
							</div>
						<?php } ?>

					</div>
				</div>
			</section>

			<!-- Items -->
			<section id="shop-items" class="pad-b-3">

				<div id="items" class="items_panel" method="GET">

					<?php if ($shopData->edit) { ?>
						<button type="submit" id="hide-items" name="hide-items" class="hidden"></button>
					<?php } ?>

					<div id="load" class="js-shop-load"></div>

					<div class="grid5 grid4-1440w grid3-1024w grid2-480w col-gap1 row-gap2">

						<?php foreach ($shopData->products as $product) {
							echo ViewManager::renderProductCard($product);
						} ?>

					</div>

					<?php if (!$shopData->products) { ?>

						<p id="store-empty">
							<span class="store-empty-ico"></span><br><br>
							Nessun articolo trovato
						</p>

					<?php } ?>

					<!-- Pages -->
					<?php

					$pageN = ceil($shopData->resN / $shopData->size);

					if ($pageN > 1) {

						$base = $shopData->page - 2;

						if ($base < 1) $base = 1;

						$top = $base + 4;

						if ($top > $pageN) {
							$top = $pageN;
							$i = $top - 4;
						} else $i = $base;

						if ($i < 1) $i = 1 ?>

						<div id="page-box" class="txt-cen mar-t-3">

							<input type="hidden" id="page-inp" name="page" value="<?= $shopData->page ?>">

							<?php if ($base > 1) { ?><p class="page_txt disabled">...</p><?php } ?>

							<?php while ($i <= $top) { ?>
								<h1 class="page_txt clickable <?php if ($i == $shopData->page) echo 'select disabled' ?>" data-page="<?= $i ?>"><?= $i ?></h1>
							<?php $i++;
							} ?>

							<?php if ($pageN > $top) { ?><p class="page_txt disabled">...</p><?php } ?>

							<button type="submit" id="page-btn" class="hidden"></button>

						</div>

					<?php } ?>
				</div>
			</section>

		</form>
<?php

		return ob_get_clean();
	}
}
