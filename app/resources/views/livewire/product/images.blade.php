<div
	id="left-panel"
	class="h-full"
>

	{{-- Preview image --}}
	<section
		class="image-section grid w-full grid-cols-1 gap-4 lg:sticky lg:top-8 lg:has-[.other-images]:grid-cols-[max-content_minmax(0,1fr)]"
	>

		{{-- Other images --}}
		@if (count($imagesUrls) > 1)
			<div class="other-images js-other-images row-start-2 flex-wrap justify-center lg:row-auto">

				@foreach ($imagesUrls as $positionId => $image)
					<label wire:key="preview-image-{{ $positionId }}">
						<input
							type="radio"
							name="preview-position-id"
							value={{ $positionId }}
							class="hidden"
							wire:model.live="previewPositionId"
						>
						<img
							class="{{ $previewPositionId == $positionId ? 'outline-primary-400' : 'outline-transparent' }} aspect-square w-20 object-cover outline-2 transition-colors"
							src="{{ $image }}"
							alt=""
						>
					</label>
				@endforeach
			</div>
		@endif

		{{-- Main image --}}
		<div class="main-image max-w-full">

			<div
				id="item-img-box"
				class="stack-content grid aspect-square items-center justify-items-center"
			>

				{{-- Image --}}
				<img
					class="img-zoom img-fill js-main-image js-primary-image w-full transition-all"
					id="item-img"
					src="{{ $mainImageUrl }}"
					alt=""
					wire:loading.class="opacity-50 saturate-0"
				>

				{{-- Logos on the image --}}
				@if ($product->logoGroup() && $product->logoGroup()->preview)
					<img
						class="logo-field__preview-logo js-logo-image"
						alt=""
					>
				@endif

				{{-- Loading icon on image change --}}
				<x-svg-loading
					wire:loading.class="opacity-100"
					class="pointer-events-none w-16 opacity-0 transition-opacity"
				/>

				{{-- Zoom icon on mobile --}}
				<div
					id="img-zoom-icon"
					class="img-zoom icon-box clickable"
				>
					<x-icon-search />
				</div>

				{{-- Wishlist button --}}
				<livewire:wishlist-button
					:product="$product"
					:key="'wishlist-button-' . $product->id"
				/>

			</div>

		</div>

		{{-- Zoom on mobile --}}
		<div id="img-zoom-bgr">
			<div id="img-zoom-box">
				<img
					class="js-main-image"
					id="img-zoom"
					src="{{ $mainImageUrl }}"
				>
			</div>
		</div>
	</section>
</div>
