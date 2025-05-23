<?php
$desktopCount = 6;
$i = $i;
$otherProductTypes = $otherProductTypes;
?>

<div id="more-clothing" class="type-slide relative">

	<div id="type-slide<?= $i ?>" data-pos="1" class="carousel mob-fluid">

		<?php foreach ($otherProductTypes as $otherProduct) { ?>

			<div class="carousel-box">
				<a href="<?= $otherProduct->url() ?>">
					<img src="<?= $otherProduct->image() ?>" class="img-fill">
				</a>
			</div>

		<?php } ?>

	</div>

	<?php if (count($otherProductTypes) > $desktopCount) { ?>

		<div class="carousel-arrow left disabled" data-direction="left" data-tar="#type-slide<?= $i ?>" style="--icon-size: 60%; color: #202020;">
			<?= embed_icon('arrow-simple-left') ?>
		</div>

		<div class="carousel-arrow right" data-direction="right" data-tar="#type-slide<?= $i ?>" style="--icon-size: 60%; color: #202020;">
			<?= embed_icon('arrow-simple-right') ?>
		</div>

	<?php } ?>

</div>