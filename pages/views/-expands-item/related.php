<?php

use App\View\Components\Interactions\FollowButton;

?>

<!-- Artist -->
<section id="art-sec">
	<div class="main-wrap">
		<div id="art-tbl">
			<div id="art-ico-cell">
				<a id="art-ico" <?= background_image($seller->icon, 1) ?> href="<?= $seller->clientUrl ?>"></a>
			</div>

			<div id="art-name-cell">

				<!-- Name and data -->
				<h1 id="art-name" class="txt-bold"><a href="<?= $seller->clientUrl ?>"><?= $seller->name ?></a></h1>
				<p id="art-date">membro dal <?= $seller->timeString ?></p>

				<!-- Follow artist -->
				<?= FollowButton::render(['sellerId' => $seller->id]) ?>
			</div>
		</div>
	</div>
</section>

<!-- Fan pics -->
<?php if (false && $fanN > 2) { ?>

	<div id="fan-pan" class="mar-t-1 mar-b-2 gallery txt-cen fan<?= $fanN ?>">
		<div id="fan-row">
			<?php $i = 0;
			while ($row_fan = $res_fan->fetch_assoc()) {
				$i++ ?>
				<div class="fan-box inlb">
					<div class="fan-pic img" style="background-image: url(/uploads/img_fans/<?= $row_fan['ID'] ?>.jpg)"></div>
				</div>
			<?php } ?>
		</div>
	</div>

<?php } ?>

<!-- Tags -->
<?php if ($oldProduct->design->tags) { ?>

	<section id="tag-sec" class="">
		<div id="tag-block" class="main-wrap txt-cen pad-t-3">

			<?php foreach ($oldProduct->design->tags as $slug) {
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

	<?php if ($relatedProducts) { ?>

		<div class="main-wrap pad-t-3 pad-b-5 txt-cen">
			<p class="more-title">Altro di questo artista</p>

			<div class="more-line"></div>

			<div id="more-gall-box" class="mar-t-1">
				<div id="more-gall" class="grid4 gap1">

					<?php foreach ($relatedProducts as $relatedProduct) { ?>

						<a href="<?= $relatedProduct->clientUrl ?>">
							<img src="<?= $relatedProduct->clientImage ?>" class="img-fill">
						</a>

					<?php } ?>

				</div>
			</div>
		</div>

	<?php } ?>

</section>