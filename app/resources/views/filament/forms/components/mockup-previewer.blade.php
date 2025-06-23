@use ('App\Helpers\PreviewHelper')

@php
	$mockup = $getRecord();
	$productType = $getRecord()->type;
	$productColor = $productType->colors->first();
	$productPosition = $getRecord()->position;
	$clothing = $productType->image($productColor->id, $productPosition->basicPositionId());
	$details = $productType->image($productColor->id, $productPosition->basicPositionId(), '-det');
@endphp

<style>
	.mockup-image {
		display: grid;
		position: relative;
		overflow: hidden;
		width: 100%;
		max-width: 30rem;
		aspect-ratio: 1;
		margin-inline: auto;
		border-radius: 0.5rem;

		>* {
			grid-row: 1;
			grid-column: 1;
			transition: top .2s ease-in-out,
				left .2s ease-in-out,
				width .2s ease-in-out,
				height .2s ease-in-out;
		}
	}

	.fill-container {
		width: 100%;
		height: 100%;
		object-fit: cover;
		position: absolute;
	}

	.printable-area {
		background-color: hsl(0, 100%, 50%, .5);
		outline: 2px dashed red;
	}

	.mockup-center {
		--_color: aqua;
		position: absolute;
		aspect-ratio: 1;
		translate: -50% 0;
		outline: 2px dashed var(--_color);

		&:before,
		&:after {
			content: '';
			display: block;
			background-color: var(--_color);
			position: absolute;
		}

		&:before {
			top: 50%;
			width: 100%;
			height: 2px;
			translate: 0 -50%;
		}

		&:after {
			left: 50%;
			height: 100%;
			width: 2px;
			translate: -50% 0;
		}
	}
</style>

<div
	class="mockup-image"
	style="--siz_x_max: {{ $mockup->siz_x_max }}%;
	--siz_y_max: {{ $mockup->siz_y_max }}%;
	--pos_x_min: {{ $mockup->pos_x_min }}%;
	--pos_y_min: {{ $mockup->pos_y_min }}%;
	--siz_x_def: {{ $mockup->siz_x_def }}%;
	--pos_x_def: {{ $mockup->pos_x_def }}%;
	--pos_y_def: {{ $mockup->pos_y_def }}%;"
>

	<img
		class="fill-container"
		src="{{ public_asset('product_types/mockups/bgr/grey.png') }}"
	>

	<img
		class="clothing-image fill-container"
		src="{{ public_asset($clothing) }}"
	>

	<div
		class="printable-area fill-container"
		style="
			width: var(--siz_x_max);
			height: var(--siz_y_max);
			top: var(--pos_y_min);
			left: var(--pos_x_min);"
	>
		<div
			class="mockup-center"
			style="
			width: var(--siz_x_def);
			left: var(--pos_x_def);
			top: var(--pos_y_def);"
		>
		</div>
	</div>

	@if ($details)
		<img
			class="clothing-overlay fill-container"
			src="{{ public_asset($details) }}"
		>
	@endif

</div>

@script
	<script>
		const siz_x_maxInput = document.addEventListener('input', (e) => {
			const mockupImage = document.querySelector('.mockup-image');
			const input = e.target;
			const value = input.value;
			const parameter = input.id.split('.')[2];

			if (!parameter) {
				return;
			}

			mockupImage.style.setProperty('--' + parameter, input.value + '%');
		});
	</script>
@endscript
