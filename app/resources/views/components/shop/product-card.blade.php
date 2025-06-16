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

		@if ($product->design)
			<livewire:wishlist-button :design="$product->design" />
		@endif
	</div>

	<div class="item-box-body">
		<p class="type uppercase"><?= $product->type->name ?></p>
		<p class="name"><?= $product->name ?></p>
		<p class="seller">by <a
				class="hover:text-primary"
				href="<?= $product->seller->url() ?>"
			><?= $product->seller->name ?></a></p>
	</div>
</div>
