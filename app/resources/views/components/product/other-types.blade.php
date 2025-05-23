<?php if (!$product->isCustom()) { ?>

<section
	id="type-sec"
	class="main-wrap mb-8 mt-16"
>
	<div class="text-center">
		<p class="more-title">Disponibile anche su</p>
		<div class="more-line"></div>
	</div>

	@if ($relatedGarments->count())
		<x-product.other-type-carousel
			:otherProductTypes="$relatedGarments"
			:i="1"
		/>
	@endif

	@if ($relatedAccessories->count())
		<x-product.other-type-carousel
			:otherProductTypes="$relatedAccessories"
			:i="2"
		/>
	@endif

</section>

<?php } ?>
