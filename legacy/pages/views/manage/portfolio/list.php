<?php

use App\Models\Ecommerce\Design as EcommerceDesign;
use App\Models\Ecommerce\Product as EcommerceProduct;
use Legacy\Classes\Ecommerce\Design;
use Legacy\Classes\Ecommerce\Product;

?>

{% block content %}

<form method="POST" class="form pad-b-2">
	<h1 class="stitle br-c1 txt-2rem">Portfolio</h1>

	<div class="mar-t-2 mar-b-3 txt-cen">

		<?php if (!$totalCount) { ?>

			<p class="txt-c-title txt-med txt-ls05 txt-1rem mar-b-1">Ancora non hai caricato nessuna grafica. Clicca qui sotto per cominciare:</p>

		<?php } ?>

		<div class="flex-group center-content" data-spacing="700">
			<a href="<?= client_url('/manage/portfolio/add?id=' . $seller->id) ?>" class="btn btn--secondary button--auto-width">
				<?= embed_icon('upload') ?>
				Carica una grafica
			</a>

			<?php if ($seller->custom || $customProductsCount) { ?>

				<a href="<?= client_url('/manage/portfolio/product') ?>" class="btn btn--secondary button--auto-width button--dashed">
					<?= embed_icon('upload') ?>
					Carica un prodotto
				</a>

			<?php } ?>

		</div>
	</div>

	<?php if ($totalCount) { ?>

		<div id="sort-box" class="txt-cen mar-b-2">
			<div id="sort-icon" class="icon inlb mar-b-1"><?= embed_icon('move') ?></div>

			<p id="sort-txt" class="mar-h-auto mar-b-1">
				Puoi riordinare i lavori a piacimento trascinandoli nella posizione desiderata.
				Se vuoi che i tuoi clienti vedano i prodotti in quest'ordine, vai nelle <a class="link" href="<?= client_url('/manage/settings/general?id=' . auth()->id()) ?>">impostazioni generali</a> e scegli <strong>"ordine personalizzato"</strong> sotto la voce <strong>"ordina prodotti"</strong>.
			</p>

			<button type="submit" id="sort-btn" name="sort" class="sbtn hidden">Applica</button>
		</div>

		<?php if ($designCount) { ?>

			<div id="gallery" class="mar-t-2 txt-cen grid gap1 js-sortable">

				<?php while ($row = $designs->fetch_assoc()) {
					$design = EcommerceDesign::find($row['id']);
				?>

					<div class="img-box">
						<input type="hidden" name="id[]" value="<?= $design->id ?>">
						<input type="hidden" class="pos js-order" name="pos[]" value="<?= $row['sort'] ?>">

						<a class="des-img block sqr" href="/manage/portfolio/edit?id=<?= $design->id ?>">
							<img class="img-fill" src="<?= $design->image() ?>" loading="lazy">
						</a>

						<p class="name fa-center txt-c-title txt-med mar-t-1 txt-line"><?= $design->name ?></p>
					</div>

				<?php } ?>

			</div>

		<?php } ?>

		<?php if ($customProductsCount) { ?>

			<div class="grid-flow" data-spacing="700">
				<h2 class="text-component-heading fa-center mar-t-4">Prodotti personalizzati</h2>

				<div class="grid-flow js-sortable" data-spacing="400" data-columns="15rem">

					<?php while ($productRow = $customProducts->fetch_assoc()) {
						$product = EcommerceProduct::find($productRow['id']);
					?>

						<div class="img-box grid-flow fa-center" data-spacing="200">
							<input type="hidden" name="product-id[]" value="<?= $product->id ?>">
							<input type="hidden" class="pos js-order" name="product-order[]" value="<?= $product->sort ?>">

							<a class="des-img block sqr" href="<?= '/manage/portfolio/product?id=' . $product->id ?>">
								<img class="img-fill" src="<?= $product->image() ?>" loading="lazy">
							</a>

							<div>
								<p class="fc-titles"><?= $product->name ?></p>
								<p class=""><?= $product->type->name ?></p>

								<?php if (!$product->status) { ?>
									<p class="fc-warning">Non pubblicato</p>
								<?php } ?>

							</div>
						</div>

					<?php } ?>

				</div>
			</div>

		<?php } ?>

	<?php } ?>

</form>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>

{% end content %}