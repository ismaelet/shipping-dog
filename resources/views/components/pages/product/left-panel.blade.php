@php

	use Legacy\View\Components\Interactions\LikeButton;

@endphp

<div
	id="left-panel"
	class="mar-h-auto"
>

	<!-- Preview image -->
	<section id="img-sec">

		<div
			id="img-sqr"
			class="mar-h-auto"
		>

			<div id="item-img-box">

				<!-- Image -->
				<img
					class="img-zoom img-fill js-main-image"
					id="item-img"
					src="{{ $legacyProduct->clientFullImage }}"
					alt=""
				>

				<!-- Logos on the image -->
				@if ($legacyProduct->hasLogos && $legacyProduct->logoGroup->preview)
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
					<div id="load-ico"></div>
				</div>

				<!-- Zoom icon on mobile -->
				<div
					id="img-zoom-icon"
					class="img-zoom icon-box clickable"
				>
					{!! embed_icon('search') !!}
				</div>

				<!-- Love button -->
				{!! LikeButton::render(['design_id' => $legacyProduct->design_id]) !!}

			</div>

		</div>

		<!-- Zoom on mobile -->
		<div id="img-zoom-bgr">
			<div id="img-zoom-box">
				<img
					class="js-main-image"
					id="img-zoom"
					src="{{ $legacyProduct->clientFullImage }}"
				>
			</div>
		</div>

	</section>

	<!-- More products with the same design -->
	@if (!$legacyProduct->isCustom)

		<section id="type-sec">
			<!-- Types -->
			<div id="type-pan">
				<div
					id="item-type-titles"
					class="gap1 pad-t-2 mar-b-1 flex"
				>

					@if ($relatedGarmentCount)
						<h3
							class="item-type-title clickable txt-bold inlb marr1 {{ $legacyProduct->type->category == 'c' || !$relatedAccessoryCount ? 'active' : '' }}"
							data-tar="#more-clothing"
						>Abbigliamento</h3>
					@endif
					@if ($relatedAccessoryCount)
						<h3
							class="item-type-title clickable txt-bold inlb {{ $legacyProduct->type->category == 'm' ? 'active' : '' }}"
							data-tar="#more-accessories"
						>Accessori</h3>
					@endif
				</div>
				<!-- More clothing -->
				@if ($relatedGarmentCount)
					<div
						id="more-clothing"
						class="type-slide {{ $legacyProduct->type->category == 'c' || !$relatedAccessoryCount ? 'active' : '' }}"
					>

						<div
							id="type-slideC"
							data-pos="1"
							class="carousel mob-fluid"
						>

							@foreach ($relatedGarments as $relatedProduct)
								<div class="carousel-box">
									<a href="{{ $relatedProduct->clientUrl }}">
										<img
											src="{{ $relatedProduct->clientImage }}"
											class="img-fill"
										>
									</a>
								</div>
							@endforeach

						</div>

						@if ($relatedGarmentCount > 3)
							<div
								class="carousel-arrow left disabled"
								data-direction="left"
								data-tar="#type-slideC"
								style="--icon-size: 50%; color: #202020;"
							>
								{!! embed_icon('navigation/arrow-simple-left') !!}
							</div>

							<div
								class="carousel-arrow right"
								data-direction="right"
								data-tar="#type-slideC"
								style="--icon-size: 50%; color: #202020;"
							>
								{!! embed_icon('navigation/arrow-simple-right') !!}
							</div>
						@endif

					</div>

				@endif

				<!-- More accessories -->
				@if ($relatedAccessoryCount)

					<div
						id="more-accessories"
						class="type-slide {{ $legacyProduct->type->category == 'm' ? 'active' : '' }}"
					>

						<div
							id="type-slideM"
							data-pos="1"
							class="carousel mob-fluid"
						>

							@foreach ($relatedAccessories as $relatedProduct)
								<div class="carousel-box">
									<a href="{{ $relatedProduct->clientUrl }}">
										<img
											src="{{ $relatedProduct->clientImage }}"
											class="img-fill"
										>
									</a>
								</div>
							@endforeach

						</div>

						@if ($relatedAccessoryCount > 3)
							<div
								class="carousel-arrow left disabled"
								data-direction="left"
								data-tar="#type-slideM"
							>
								<div class="icon"></div>
							</div>

							<div
								class="carousel-arrow right"
								data-direction="right"
								data-tar="#type-slideM"
							>
								<div class="icon"></div>
							</div>
						@endif

					</div>

				@endif

			</div>
		</section>

	@endif

</div>
