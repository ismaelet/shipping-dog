<div>
	<div class="item-img item-panel-open relative">
		<img
			src="<?= $product->image() ?>"
			alt=""
			class="img-fill"
			data-preview-image
		>
		<div class="item-over bgr"></div>
	</div>

	<h3 class="type-name txt-med mt-2 text-center">
		<?= $product->type->name ?>
	</h3>

	<form
		method="POST"
		class="img-buttons mt-2 grid"
		action="{{ route('products.status', $product) }}"
	>

		<input
			type="hidden"
			name="type_id"
			value="<?= $product->type->id ?>"
		>

		@if ($product->status)
			<x-inputs.button class="edit-btn switch-btn item-panel-open">
				{!! embed_icon('settings') !!}
				Modifica
			</x-inputs.button>

			<x-inputs.button
				type="submit"
				name="status"
				value="0"
				class="off-btn switch-btn"
				variant="secondary"
			>
				{!! embed_icon('close') !!}
				Disattiva
			</x-inputs.button>
		@else
			<x-inputs.button
				type="submit"
				name="status"
				value="1"
				class="on-btn switch-btn"
				variant="secondary"
			>
				{!! embed_icon('add') !!}
				Attiva
			</x-inputs.button>
		@endif

	</form>
</div>
