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

<x-layouts.seller-dashboard>

	<form
		class="form pad-v-2 grid gap-8"
		method="POST"
		enctype="multipart/form-data"
		action="{{ isset($product) ? route('seller-dashboard.portfolio.products.update', $product) : route('seller-dashboard.portfolio.products.store') }}"
	>
		@csrf

		<input
			type="hidden"
			name="id"
			value="{{ isset($product) ? $product->id : $tempId }}"
		>

		<div class="grid gap-4">
			<h1 class="txt-2rem">
				<span class="txt-sbold">
					{!! isset($product) ? '<span class="txt-light">Modifica:</span> ' . $product->name : 'Aggiungi un prodotto' !!}
				</span>
			</h1>

			@session('success')
				<x-alerts.success>Modifiche al prodotto salvate</x-alerts.success>
			@endsession
		</div>

		<div class="grid gap-8">
			@if (isset($product) && !$product->status)
				<p class="error">Il prodotto non è ancora pubblico, ma verrà attivato al più presto</p>
			@endif

			<div class="form-field">
				<x-label>Nome:</x-label>

				<input
					type="text"
					class="input"
					name="name"
					value="{{ isset($product) ? $product->name : '' }}"
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
								{{ isset($product) && $product->type_id == $type->id ? 'selected' : '' }}
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
							value="{{ isset($product) ? $product->fullPrice() : '0.00' }}"
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
				>{{ isset($product) ? $product->description() : '' }}</x-textarea>

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

			<div class="surface grid grid-cols-1 gap-8 p-4">
				<livewire:portfolio.product.active-colors :product="isset($product) ? $product : null" />

				<span class="border-neutral-250 border-b"></span>

				<livewire:portfolio.product.default-color :product="isset($product) ? $product : null" />
			</div>

			@foreach ($imagePositionTypes->where('base_of', null) ?? [] as $position)
				<div class="surface grid grid-cols-1 gap-8 p-4">
					<h3>Immagine {{ strtolower($position->name) }}</h3>

					<livewire:portfolio.product.upload-image
						:positionId="$position->id"
						:product="isset($product) ? $product : null"
						:tempId="$tempId ?? null"
					/>
				</div>
			@endforeach

			<div class="form-field">
				<div>
					<x-label>Immagine di copertina (opzionale):</x-label>
					<p>Apparirà di fianco alla descrizione del prodotto</p>
				</div>

				@if (isset($product) && $product->hasSpecificCoverImage)
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

			@if (isset($product))
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
				name="{{ isset($product) ? 'edit' : 'add' }}"
				class="js-submit"
			>
				{{ isset($product) ? 'Salva' : 'Aggiungi' }}
			</x-button>

			<x-button
				href="{{ route('seller-dashboard.portfolio') }}"
				variant="secondary"
			>
				Indietro
			</x-button>

			@if (isset($product))
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
