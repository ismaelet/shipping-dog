@php
	$clothing = $product->type->image($product->color_id, $position->basicPositionId());

	$details = $product->type->image($product->color_id, $position->basicPositionId(), '-det');
@endphp

<div class="image-panel">

	<div class="image mar-h-auto stack-content relative grid aspect-square w-full overflow-hidden rounded-[3px]">

		@if ($design->custom)

			<!-- If it's a custom static image -->
			<div
				class="h-full w-full bg-cover bg-center"
				style="background-image: url({{ public_asset($design->image(1024)) }});"
			></div>
		@else
			<!-- If it's a normal image -->
			<div
				class="background h-full w-full bg-cover bg-center"
				style="background-image: url({{ public_asset('product_types/mockups/bgr/grey.png') }});"
			></div>

			<img
				class="clothing h-full w-full object-cover"
				src="{{ public_asset($clothing) }}"
				data-type="{{ $product->type->id }}"
				data-position="{{ $position->id }}"
				data-suffix=""
			>

			<div
				class="design-box"
				style="
											width: <?= $sX_max ?>%;
											height: <?= $sY_max ?>%;
											top: <?= $pY_min ?>%;
											left: <?= $pX_min ?>%;"
			>

				<div
					class="design light <?= $product->color->dark ? '' : 'active' ?> bg-no-repeat"
					style="
												background-image: url(<?= $designLight ?>);
												background-position: <?= $pX . '% ' . $pY . '%' ?>;
												background-size: <?= $orientation ? $sX . '% auto' : 'auto ' . $sX . '%' ?>"
				>
				</div>

				<div
					class="design <?= $product->color->dark ? 'active' : '' ?> dark bg-no-repeat"
					style="
												background-image: url(<?= $designDark ?>);
												background-position: <?= $pX . '% ' . $pY . '%' ?>;
												background-size: <?= $orientation ? $sX . '% auto' : 'auto ' . $sX . '%' ?>"
				>
				</div>

			</div>

			@if ($details)
				<img
					class="clothing-details relative z-10"
					src="{{ public_asset($details) }}"
					data-type="{{ $product->type->id }}"
					data-position="{{ $position->id }}"
					data-suffix="-det"
				>
			@endif
		@endif

	</div>
</div>
