@php
	$initialTypeId = old('type_id', isset($product) ? $product->type_id : $types->first()?->id);

	$initialPrice = old('price', isset($product) ? number_format($product->price()->price, 2, '.', '') : null);
@endphp

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
	<script>
		document.addEventListener('alpine:init', () => {
			Alpine.data('productPricing', (
				references,
				initialTypeId,
				initialPrice = null,
			) => ({
				references,
				typeId: Number(initialTypeId),
				price: initialPrice,
				gainW: 0,
				gainC: 0,
				minimumPrice: 0,

				init() {
					const reference = this.currentReference();

					if (this.price === null || this.price === '') {
						this.price = reference.price;
					}

					this.calculate();
				},

				currentReference() {
					return this.references[this.typeId] ?? {
						price: 0,
						gain_w: 0,
						gain_c: 0,
						minimum_price: 0,
					};
				},

				changeType() {
					const reference = this.currentReference();

					/*
					 * Reset to the standard price of the newly selected type.
					 * This avoids carrying a price from one product type to
					 * another product type with different underlying costs.
					 */
					this.price = reference.price;

					this.calculate();
				},

				calculate() {
					const reference = this.currentReference();

					let price = this.parseNumber(this.price);
					const referencePrice = this.parseNumber(reference.price);
					const referenceGainW = this.parseNumber(reference.gain_w);
					const referenceGainC = this.parseNumber(reference.gain_c);

					const baseW = referencePrice - referenceGainW / 0.75;
					const baseC = referencePrice - referenceGainC / 0.75;

					this.minimumPrice = this.parseNumber(
						reference.minimum_price
					);

					if (price < 0) {
						price = 0;
					}

					this.gainW = Math.max(
						0,
						(price - baseW) *
						0.75,
					).toFixed(2);

					this.gainC = Math.max(
						0,
						(price - baseC) * 0.75,
					).toFixed(2);
				},

				normalizePrice() {
					let price = this.parseNumber(this.price);

					if (price < this.minimumPrice) {
						price = this.minimumPrice;
					}

					this.price = price.toFixed(2);

					this.calculate();
				},

				parseNumber(value) {
					const normalized = String(value ?? '')
						.replace(',', '.')
						.replace(/[^\d.-]/g, '');

					const number = Number.parseFloat(normalized);

					return Number.isFinite(number) ? number : 0;
				},
			}));
		});
	</script>
@endpushOnce

<x-layouts.seller-dashboard>

	<form
		class="form pad-v-2 grid gap-8"
		method="POST"
		enctype="multipart/form-data"
		action="{{ isset($product) ? route('seller-dashboard.portfolio.products.update', $product) : route('seller-dashboard.portfolio.products.store') }}"
	>
		@csrf

		@if (!isset($product))
			<input
				type="hidden"
				name="temp_id"
				value="{{ $tempId }}"
			>
		@endif

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

			<div
				class="grid gap-4 md:grid-cols-5"
				x-data="productPricing(
	    @js($priceReferences),
	    @js($initialTypeId),
	    @js($initialPrice)
	)"
			>
				<livewire:portfolio.product.type-select
					:types="$types"
					:initial-type-id="$initialTypeId"
				/>

				<div class="form-field">
					<x-label>Prezzo:</x-label>

					<div class="price-input flex self-end">
						<input
							type="number"
							class="input grow"
							name="price"
							x-model="price"
							x-on:input="calculate"
							x-on:blur="normalizePrice"
							x-bind:min="minimumPrice"
							step="0.01"
							placeholder="Prezzo"
							required
						>
					</div>
				</div>

				<div class="form-field">
					<x-label>Guadagno capi chiari:</x-label>
					<p>Calcolato automaticamente</p>

					<div class="price-input flex">
						<input
							type="text"
							class="input grow"
							x-bind:value="`${gainW} €`"
							placeholder="Guadagno capi chiari"
							readonly
						>
					</div>
				</div>

				<div class="form-field">
					<x-label>Guadagno capi scuri:</x-label>
					<p>Calcolato automaticamente</p>

					<div class="price-input flex">
						<input
							type="text"
							class="input grow"
							x-bind:value="`${gainC} €`"
							placeholder="Guadagno capi scuri"
							readonly
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
							src="{{ asset($product->coverImage) }}"
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
					onclick="return confirm('Sei sicuro di voler eliminare questo prodotto? Questa azione non può essere annullata.')"
				>
					<x-icon-delete />
					Elimina il prodotto
				</x-button>
			@endif
		</div>
	</form>

</x-layouts.seller-dashboard>
