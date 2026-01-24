<?php
$desktopCount = 6;
$i = $i;
$otherProductTypes = $otherProductTypes;
?>

<div
	id="more-clothing"
	class="type-slide relative"
>

	<div
		id="type-slide<?= $i ?>"
		data-pos="1"
		class="carousel mob-fluid"
	>

		<?php foreach ($otherProductTypes as $otherProduct) { ?>

		<div class="carousel-box">
			<a href="<?= $otherProduct->url() ?>">
				<img
					src="<?= $otherProduct->image() ?>"
					class="img-fill"
				>
			</a>
		</div>

		<?php } ?>

	</div>

	<?php if (count($otherProductTypes) > $desktopCount) { ?>

	<div
		class="carousel-arrow left disabled"
		data-direction="left"
		data-tar="#type-slide<?= $i ?>"
		style="--icon-size: 40%; color: #202020;"
	>
		<x-icon-arrow-simple-left class="h-1/3 w-1/3" />
	</div>

	<div
		class="carousel-arrow right"
		data-direction="right"
		data-tar="#type-slide<?= $i ?>"
		style="--icon-size: 40%; color: #202020;"
	>
		<x-icon-arrow-simple-right class="h-1/3 w-1/3" />
	</div>

	<?php } ?>

</div>
