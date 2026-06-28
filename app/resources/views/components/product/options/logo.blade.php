<div class="logo-field field">
	<x-label class="field-label text-sm">{{ $product->logoGroup()->name }}</x-label>

	<div
		class="grid-flow"
		data-spacing="400"
	>
		<div class="logo-field__list">

			{{-- Checkbox for no logo --}}
			@if (!$product->logoGroup()->forced)
				<label class="logo-field__item">
					<input
						type="radio"
						class="js-logo"
						name="logo_id"
						value="0"
						checked
					>
					{!! embed_icon('close') !!}
				</label>
			@endif

			{{-- checkboxes for extras --}}
			@foreach ($product->logos() as $logoKey => $logo)
				<label
					class="logo-field__item"
					title="{{ $logo->name }}"
				>
					<input
						type="radio"
						class="js-logo"
						name="logo_id"
						value="{{ $logo->id }}"
						{{ $product->logoGroup()->forced && !$logoKey ? 'checked' : null }}
					>
					<img
						class="img-fill"
						src="{{ $logo->image() }}"
						alt=""
					>
				</label>
			@endforeach

		</div>

		<div class="logo-field__preview js-logo-image-background {{ $product->logoGroup()->forced ? 'active' : null }}">

			@if ($product->logoGroup()->logos?->first()?->customPreview($product->type_id))
				<img
					class="logo-field__static-preview"
					src="{{ $product->logoGroup()->logos?->first()?->customPreview($product->type_id) }}"
					alt=""
				>
			@else
				<div class="logo-field__preview-wrapper grid-flow stack-content">

					<img
						class="logo-field__preview-image js-front-image"
						src="{{ $product->image(1024) }}"
					>

					<img
						class="logo-field__preview-logo js-logo-image"
						src=""
						alt=""
					>

				</div>
			@endif
		</div>
	</div>
</div>
