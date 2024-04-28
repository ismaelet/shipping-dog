<?php

use Legacy\View\Components\Interactions\FollowButton;

?>

<!-- Artist -->
<section id="art-sec">
	<div class="main-wrap">
		<div id="art-tbl">
			<div id="art-ico-cell">
				<a id="art-ico" href="<?= $product->seller->url() ?>">
					<img src="<?= $product->seller->icon() ?>" alt="">
				</a>
			</div>

			<div id="art-name-cell">

				<!-- Name and data -->
				<h1 id="art-name" class="txt-bold"><a href="<?= $product->seller->url() ?>"><?= $product->seller->name ?></a></h1>
				<p id="art-date">membro dal <?= $product->seller->timeString() ?></p>

				<!-- Follow artist -->
				<?= FollowButton::render(['seller_id' => $product->seller->id]) ?>
			</div>
		</div>
	</div>
</section>

<!-- Tags -->
<?php if ($legacyProduct->design->tags) { ?>

	<section id="tag-sec" class="">
		<div id="tag-block" class="main-wrap txt-cen pad-t-3">

			<?php foreach ($legacyProduct->design->tags as $slug) {
				$slug = str_replace('#', '', $slug);
			?>

				<a href="<?= client_url('/search?search=' . $slug) ?>">
					<p class="tag inlb"><?= $slug ?></p>
				</a>

			<?php } ?>

		</div>
	</section>

<?php } ?>

<!-- More items -->
<section id="more-sec" class="">

	<?php if ($relatedProducts->count()) { ?>

		<div class="main-wrap pad-t-3 pad-b-5 txt-cen">
			<p class="more-title">Altro di questo artista</p>

			<div class="more-line"></div>

			<div id="more-gall-box" class="mar-t-1">
				<div id="more-gall" class="grid4 gap1">

					<?php foreach ($relatedProducts as $relatedProduct) { ?>

						<a href="<?= $relatedProduct->url() ?>">
							<img src="<?= $relatedProduct->image() ?>" class="img-fill">
						</a>

					<?php } ?>

				</div>
			</div>
		</div>

	<?php } ?>

</section>