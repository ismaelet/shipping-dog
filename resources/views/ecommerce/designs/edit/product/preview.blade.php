@php
	$detailsPath = str_replace('.', '-det.', $clothing);
	$details = file_exists(base_path() . '/public/storage/' . $detailsPath) ? public_asset($detailsPath) : null;
@endphp

<div class="image-panel">

	<div class="image mar-h-auto relative">

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

			<div
				class="clothing h-full w-full bg-cover bg-center"
				style="background-image: url({{ public_asset($clothing) }});"
			></div>

			<div
				class="design-box"
				style="
											width: <?= $sX_max ?>%;
											height: <?= $sY_max ?>%;
											top: <?= $pY_min ?>%;
											left: <?= $pX_min ?>%;"
			>

				<div
					class="design light <?= $product->color->dark ? '' : 'active' ?>"
					style="
												background-image: url(<?= $designLight ?>);
												background-position: <?= $pX . '% ' . $pY . '%' ?>;
												background-size: <?= $orientation ? $sX . '% auto' : 'auto ' . $sX . '%' ?>"
				>
				</div>

				<div
					class="design <?= $product->color->dark ? 'active' : '' ?> dark"
					style="
												background-image: url(<?= $designDark ?>);
												background-position: <?= $pX . '% ' . $pY . '%' ?>;
												background-size: <?= $orientation ? $sX . '% auto' : 'auto ' . $sX . '%' ?>"
				>
				</div>

			</div>

			@if ($details)
				<div
					class="details h-full w-full bg-cover bg-center"
					style="background-image: url({{ public_asset($details) }});"
				></div>
			@endif
		@endif

	</div>
</div>
