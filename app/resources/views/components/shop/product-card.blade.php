@props(['product'])

<div class="item-box">
	<div class="image">
		<a
			href="<?= $product->url() ?>"
			class="block"
		>
			<img
				src="<?= $product->image() ?>"
				alt=""
				loading="lazy"
			>
			<p class="price blu"><?= $product->customerPriceString() ?></p>
		</a>

		<x-shop.like-button :design="$product->design" />

	</div>

	<div class="item-box-body">
		<p class="type uppercase"><?= $product->type->name ?></p>
		<p class="name"><?= $product->name ?></p>
		<p class="seller">by <a
				class="txt-c1-h"
				href="<?= $product->seller->url() ?>"
			><?= $product->seller->name ?></a></p>
	</div>
</div>
