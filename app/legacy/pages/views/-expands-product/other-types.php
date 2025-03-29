<?php if (!$product->isCustom()) { ?>

	<section id="type-sec" class="mt-16 mb-8 main-wrap">
		<div class="text-center">
			<p class="more-title">Disponibile anche su</p>
			<div class="more-line"></div>
		</div>

		<!-- More clothing -->
		<?php if ($relatedGarmentCount) {
			$otherProductTypes = $relatedGarments;
			$i = 1;

			include('other-type-carousel.php');
		} ?>

		<!-- More accessories -->
		<?php if ($relatedAccessoryCount) {
			$otherProductTypes = $relatedAccessories;
			$i = 2;

			include('other-type-carousel.php');
		} ?>

	</section>

<?php } ?>