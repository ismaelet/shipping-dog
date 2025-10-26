@pushOnce('styles')
	<style>
		.colors {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(min(100%, 10rem), 1fr));
			gap: 1rem;
		}

		.color {
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}

		.color * {
			cursor: pointer;
		}

		.color__checkbox,
		.color__input,
		.color__button {
			display: none;
		}

		.color__icon {
			width: 2rem;
			aspect-ratio: 1;
			background: linear-gradient(135deg, var(--_color-1) 50%, var(--_color-2) 50%);
			border: 3px solid var(--color-neutral-500);
			border-radius: 50%;
			transition: border-color 0.2s;
		}

		.color__checkbox:checked+.color__icon {
			border-color: var(--color-primary-400);
		}

		.color__upload {
			display: none;
		}

		.color__upload--warning {
			color: rgb(255, 91, 91);
		}

		.color:has(.color__checkbox:checked) .color__upload {
			display: block;
		}

		.product-cover-image {
			max-height: 30rem;
		}

		.delete-button {
			--icon-size: 1em;
			gap: 0.5em;
			width: max-content;
			background: transparent;
			border: none;
			font-size: var(--font-size-300);
			color: red;
			cursor: pointer;
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script defer>
		document.addEventListener('DOMContentLoaded', function() {
			document.querySelectorAll('.js-default-color-label').forEach(function(label) {
				label.addEventListener('click', function() {
					label.closest('.js-color').querySelector('.js-default-color').click();
				});
			});

			document.querySelectorAll('.js-active-color').forEach(function(checkbox) {
				checkbox.addEventListener('click', function() {
					if (document.querySelectorAll('.js-active-color:checked').length < 1) {
						checkbox.checked = true;
					}
				});
			});

			document.querySelectorAll('.js-active-color-label').forEach(function(label) {
				label.addEventListener('click', function() {
					label.closest('.js-color').querySelector('.js-active-color').click();
				});
			});

			document.querySelectorAll('.js-upload').forEach(function(upload) {
				upload.addEventListener('click', function() {
					upload.nextElementSibling.click();
				});
			});

			document.querySelectorAll('.js-submit-upload').forEach(function(input) {
				input.addEventListener('change', function() {
					input.nextElementSibling.click();
				});
			});
		});
	</script>
@endpushOnce

<x-layouts.seller-dashboard>

	<form
		class="form pad-v-2 grid gap-8"
		method="POST"
		enctype="multipart/form-data"
		action="{{ $product ? route('seller-dashboard.portfolio.products.update', $product) : route('seller-dashboard.portfolio.products.store') }}"
	>
		@csrf
		<div class="grid gap-4">
			<h1 class="txt-2rem">
				<span class="txt-sbold">
					{!! $product ? '<span class="txt-light">Modifica:</span> ' . $product->name : 'Aggiungi un prodotto' !!}
				</span>
			</h1>

			@session('success')
				<x-alerts.success>Modifiche al prodotto salvate</x-alerts.success>
			@endsession
		</div>

		<div class="grid gap-8">
			@if ($product && !$product->status)
				<p class="error">Il prodotto non è ancora pubblico, ma verrà attivato al più presto</p>
			@endif

			<div class="form-field">
				<x-label>Nome:</x-label>

				<input
					type="text"
					class="input"
					name="name"
					value="{{ $product ? $product->name : '' }}"
					placeholder="Nome"
					required
				>

				@error('name')
					<x-alerts.error>{{ $message }}</x-alerts.error>
				@enderror
			</div>

			<div class="grid gap-4 md:grid-cols-4">
				<div class="form-field md:col-span-3">
					<x-label>Tipo di prodotto:</x-label>

					<select
						class="select"
						name="type_id"
					>

						@foreach ($types as $type)
							<option
								value="{{ $type->id }}"
								{{ $product && $product->type_id == $type->id ? 'selected' : '' }}
							>
								{{ $type->name }}
							</option>
						@endforeach

					</select>
				</div>

				<div class="form-field">
					<x-label>Prezzo:</x-label>

					<div class="price-input flex">
						<input
							type="text"
							class="input grow"
							name="price"
							value="{{ $product ? $product->fullPrice() : '0.00' }}"
							placeholder="Prezzo"
						>
					</div>
				</div>

				@error('type_id')
					<x-alerts.error class="md:col-span-4">{{ $message }}</x-alerts.error>
				@enderror

				@error('price')
					<x-alerts.error class="md:col-span-4">{{ $message }}</x-alerts.error>
				@enderror
			</div>

			<div class="form-field">
				<x-label>Descrizione (opzionale):</x-label>
				<x-textarea
					name="description"
					maxlength="500"
					placeholder="Descrizione"
				>{{ $product ? $product->description() : '' }}</x-textarea>

				@error('description')
					<x-alerts.error>{{ $message }}</x-alerts.error>
				@enderror
			</div>

			@error('default-color-id')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
			@error('colors')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
			@error('cover-image')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror

			<div class="form-field">
				<div>
					<x-label>Colore predefinito:</x-label>
					<p>Il colore che viene mostrato di base nell'anteprima del prodotto</p>
				</div>

				<div class="colors mt-4">

					@foreach ($colors as $color)
						<label class="color js-color">
							<div class="">
								<input
									type="radio"
									class="color__checkbox js-default-color"
									name="default-color-id"
									value="{{ $color->id }}"
									@checked(($product && $product->color_id == $color->id) || (!$product && $color->id == 1))
									required
								>
								<div
									class="color__icon"
									style="--_color-1: {{ $color->primary_color_hex }}; --_color-2: {{ $color->secondary_color_hex ?: $color->primary_color_hex }}"
								></div>
							</div>

							<p class="color__label js-default-color-label">
								{{ $color->name }}
							</p>
						</label>
					@endforeach

				</div>
			</div>

			<div class="form-field">
				<div>
					<x-label>Colori attivi:</x-label>
					<p>Tutte le varianti di colore possibili per il prodotto</p>
				</div>

				<div
					class="colors mt-4"
					id="active-colors"
				>

					@foreach ($colors as $color)
						<div class="color js-color">
							<label class="">
								<input
									id="active-color-{{ $color->id }}"
									type="checkbox"
									class="color__checkbox js-active-color"
									name="colors[]"
									value="{{ $color->tag }}"
									{{ $product && $product->colors()->where('tag', $color->tag)->count() ? 'checked' : '' }}
								>
								<div
									class="color__icon"
									style="--_color-1: {{ $color->primary_color_hex }}; --_color-2: {{ $color->secondary_color_hex ?: $color->primary_color_hex }}"
								></div>
							</label>

							<div class="color__content">
								<label for="active-color-{{ $color->id }}">
									<p class="color__label js-active-color-label">
										{{ $color->name }}
									</p>
								</label>

								@if ($product)
									@php
										$preview = $product ? $product->previewImage(color: $color) : null;
										$imageExists = $product ? $preview?->exists() : false;
									@endphp

									<p class="color__upload {{ !$imageExists ? 'text-red-500' : 'text-blue-400' }} js-upload text-sm">
										{{ $imageExists ? 'Ricarica' : 'Carica' }} immagine</p>
									<input
										type="file"
										class="color__input js-file-input {{ $product ? 'js-submit-upload' : '' }}"
										name="file-{{ $color->id }}"
									>
									<button
										type="submit"
										class="color__button js-color-button"
										name="upload"
										formaction="{{ route('seller-dashboard.portfolio.products.upload-image', ['product' => $product, 'color' => $color]) }}"
										value="{{ $color->id }}"
									></button>
								@endif
							</div>
						</div>
					@endforeach

				</div>
			</div>

			<div class="form-field">
				<div>
					<x-label>Immagine di copertina (opzionale):</x-label>
					<p>Apparirà di fianco alla descrizione del prodotto</p>
				</div>

				@if ($product && $product->hasSpecificCoverImage)
					<label class="replace-image">
						<input
							type="file"
							name="cover-image"
							onchange="$('.js-submit').click()"
						>
						<img
							class="product-cover-image"
							src="{{ client_path($product->coverImage) }}"
						>
					</label>
				@else
					<input
						type="file"
						class="input"
						name="cover-image"
					>
				@endif

			</div>

			@if ($product)
				<div class="form-field">
					<div>
						<x-label>Altre immagini (opzionali):</x-label>
					</div>

					<input
						type="file"
						class="input"
						name="more-images[]"
						multiple
					>
				</div>
			@endif
		</div>


		<div
			class="flex-group center-content"
			data-spacing="700"
		>
			<x-button
				type="submit"
				name="{{ $product ? 'edit' : 'add' }}"
				class="js-submit"
			>
				{{ $product ? 'Salva' : 'Aggiungi' }}
			</x-button>

			<x-button
				href="{{ route('seller-dashboard.portfolio') }}"
				variant="secondary"
			>
				Indietro
			</x-button>

			@if ($product)
				<x-button
					type="submit"
					name="delete"
					variant="secondary"
					color="danger"
					formaction="{{ route('seller-dashboard.portfolio.products.delete', $product) }}"
				>
					{!! embed_icon('delete') !!}
					Elimina il prodotto
				</x-button>
			@endif
		</div>
	</form>

</x-layouts.seller-dashboard>
