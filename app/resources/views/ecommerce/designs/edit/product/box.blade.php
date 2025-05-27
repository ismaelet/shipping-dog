<div id="product-{{ $product->id }}">
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
		action="{{ route('products.status', $product) . '#product-' . $product->id }}"
	>
		@csrf

		<input
			type="hidden"
			name="type_id"
			value="<?= $product->type->id ?>"
		>

		@if ($product->status)
			<x-button class="edit-btn switch-btn item-panel-open">
				{!! embed_icon('settings') !!}
				Modifica
			</x-button>

			<x-button
				type="submit"
				name="status"
				value="0"
				class="off-btn switch-btn"
				variant="secondary"
			>
				{!! embed_icon('close') !!}
				Disattiva
			</x-button>
		@else
			<x-button
				type="submit"
				name="status"
				value="1"
				class="on-btn switch-btn"
				variant="secondary"
			>
				{!! embed_icon('add') !!}
				Attiva
			</x-button>
		@endif

	</form>
</div>
