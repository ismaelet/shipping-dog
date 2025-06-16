<?php

use Legacy\View\Components\Interactions\LikeButton;

?>

<div
	id="left-panel"
	class="h-full"
>

	<!-- Preview image -->
	<section class="image-section">

		<!-- Other images -->
		<?php if ($product->type->unique_positions->count() > 1) { ?>
		<div class="other-images js-other-images">
			<img
				class="js-main-image"
				src="<?= $product->image(1024) ?>"
				alt=""
			>

			<?php foreach ($product->type->unique_positions as $position) { ?>
			<img
				class="js-secondary-image"
				src="<?= $product->type->rasterizedImage($product->color_id, $position->id) ?>"
				alt=""
				data-position-id="<?= $position->id ?>"
				data-active="<?= $position->id == $product->position_id ? 'false' : 'true' ?>"
			>
			<?php } ?>
		</div>
		<?php } ?>

		<!-- Main image -->
		<div class="main-image">

			<div id="item-img-box">

				<!-- Image -->
				<img
					class="img-zoom img-fill js-main-image js-primary-image"
					id="item-img"
					src="<?= $product->image(1024) ?>"
					alt=""
				>

				<!-- Logos on the image -->
				<?php if ($product->logoGroup() && $product->logoGroup()->preview) { ?>

				<img
					class="logo-field__preview-logo js-logo-image"
					alt=""
				>

				<?php } ?>

				<!-- Loading icon on image change -->
				<div
					id="img-load"
					class="bgr"
				>
					<div id="load-ico">
						<?= embed_icon('loading') ?>
					</div>
				</div>

				<!-- Zoom icon on mobile -->
				<div
					id="img-zoom-icon"
					class="img-zoom icon-box clickable"
				>
					<?= embed_icon('search') ?>
				</div>

				<!-- Love button -->
				@if ($product->design)
					<livewire:wishlist-button :design="$product->design" />
				@endif

			</div>

		</div>

		<!-- Zoom on mobile -->
		<div id="img-zoom-bgr">
			<div id="img-zoom-box">
				<img
					class="js-main-image"
					id="img-zoom"
					src="{{ $product->image(1024) }}"
				>
			</div>
		</div>

	</section>

</div>
