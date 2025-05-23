<?php

use Legacy\View\Components\Interactions\FollowButton;

?>

@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/product-related-products.css') }}"
	>
@endpushOnce

<!-- Artist -->
<section
	id="art-sec"
	class="full-width"
>
	<div class="">
		<div id="art-tbl">
			<div id="art-ico-cell">
				<a
					id="art-ico"
					href="<?= $product->seller->url() ?>"
				>
					<img
						src="<?= $product->seller->icon() ?>"
						alt=""
					>
				</a>
			</div>

			<div id="art-name-cell">

				<!-- Name and data -->
				<h1
					id="art-name"
					class="font-semibold"
				><a href="<?= $product->seller->url() ?>"><?= $product->seller->name ?></a></h1>
				<p id="art-date">membro dal <?= $product->seller->createdAtString() ?></p>

				<!-- Follow artist -->
				<x-shop.follow-button :seller="$product->seller" />
			</div>
		</div>
	</div>
</section>

<!-- More items -->
<section
	id="more-sec"
	class=""
>

	<?php if ($relatedProducts->count()) { ?>

	<div class="main-wrap pad-t-3 pad-b-4 text-center">
		<p class="more-title">Altro di questo negozio</p>

		<div class="more-line"></div>

		<div
			id="more-gall-box"
			class="mar-t-1"
		>
			<div
				id="more-gall"
				class="grid4 gap1"
			>

				<?php foreach ($relatedProducts as $relatedProduct) { ?>

				<a href="<?= $relatedProduct->url() ?>">
					<img
						src="<?= $relatedProduct->image() ?>"
						class="img-fill"
					>
				</a>

				<?php } ?>

			</div>
		</div>
	</div>

	<?php } ?>

</section>
