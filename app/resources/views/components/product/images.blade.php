<?php

use Legacy\View\Components\Interactions\LikeButton;

?>

<div
	id="left-panel"
	class="h-full"
>

	<!-- Preview image -->
	<section
		class="image-section grid w-full grid-cols-1 gap-4 lg:sticky lg:top-8 lg:has-[.other-images]:grid-cols-[max-content_minmax(0,_1fr)]"
	>

		<!-- Other images -->
		@if ((!$product->isCustom() && $product->type->unique_positions->count() > 1) || $product->customImages()->count())
			<div class="other-images js-other-images row-start-2 flex-wrap justify-center lg:row-auto">
				<img
					class="js-main-image aspect-square w-20 object-cover"
					src="{{ $product->image(1024) }}"
					alt=""
				>

				@if (!$product->isCustom())
					@foreach ($product->type->unique_positions as $position)
						<img
							class="js-secondary-image aspect-square w-20 object-cover"
							src="{{ $product->type->rasterizedImage($product->color_id, $position->id) }}"
							alt=""
							data-position-id="{{ $position->id }}"
							data-active="{{ $position->id == $product->position_id ? 'false' : 'true' }}"
						>
					@endforeach
				@else
					@foreach ($product->customImages() as $image)
						<img
							class="js-secondary-image aspect-square w-20 object-cover"
							src="{{ $image }}"
							alt=""
						>
					@endforeach
				@endif
			</div>
		@endif

		<!-- Main image -->
		<div class="main-image max-w-full">

			<div id="item-img-box">

				<!-- Image -->
				<img
					class="img-zoom img-fill js-main-image js-primary-image w-full"
					id="item-img"
					src="{{ $product->image(1024) }}"
					alt=""
				>

				<!-- Logos on the image -->
				@if ($product->logoGroup() && $product->logoGroup()->preview)
					<img
						class="logo-field__preview-logo js-logo-image"
						alt=""
					>
				@endif

				<!-- Loading icon on image change -->
				<div
					id="img-load"
					class="bgr"
				>
					<div id="load-ico">
						{!! embed_icon('loading') !!}
					</div>
				</div>

				<!-- Zoom icon on mobile -->
				<div
					id="img-zoom-icon"
					class="img-zoom icon-box clickable"
				>
					{!! embed_icon('search') !!}
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
