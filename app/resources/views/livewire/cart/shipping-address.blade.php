<section
	id="address"
	@class(['address-form cart-panel surface'])
>
	<x-heading.component>Indirizzo</x-heading.component>

	<div class="grid gap-4">

		<x-input
			name="full_name"
			placeholder="Nominativo di spedizione"
			:value="$address->full_name ?? ''"
			:required="true"
			:trim="true"
		/>

		<div @class([
			'field grid gap-[inherit] md:grid-cols-2',
			'hidden!' => !$isShipped,
		])>
			<input
				type="text"
				id="inp_addr"
				class="input delivery-input"
				name="address"
				placeholder="Indirizzo"
				value="{{ $address->address ?? '' }}"
				maxlength="255"
				autocomplete="address-line1"
				@required($isShipped)
			>

			<input
				type="text"
				id="inp_num"
				class="input delivery-input"
				name="number"
				placeholder="Numero civico"
				value="{{ $address->number ?? '' }}"
				maxlength="10"
				@required($isShipped)
			>
		</div>

		<div @class([
			'hidden!' => !$isShipped,
		])>
			<input
				type="text"
				class="input delivery-input w-full"
				name="city"
				placeholder="Città"
				value="{{ $address->city ?? '' }}"
				maxlength="255"
				autocomplete="address-level2"
				@required($isShipped)
			>
		</div>

		<div @class([
			'field grid gap-[inherit] md:grid-cols-2',
			'hidden!' => !$isShipped,
		])>
			<input
				type="text"
				class="input delivery-input"
				name="zip"
				placeholder="Codice postale"
				value="{{ trim($address->zip ?? '') }}"
				maxlength="5"
				autocomplete="postal-code"
				@required($isShipped)
			>

			<select
				name="region"
				class="input delivery-input"
				placeholder="Provincia"
				@required($isShipped)
			>
				<option
					value=""
					disabled
					@selected(empty($address))
				>Provincia</option>

				@foreach ($provinces as $province)
					<option
						value="{{ $province->tag }}"
						@selected($address?->region == $province->tag)
					>{{ $province->name . ' (' . $province->tag . ')' }}</option>
				@endforeach

			</select>
		</div>

		<input
			type="email"
			class="input"
			name="email"
			placeholder="Indirizzo e-mail"
			value="{{ $address->email ?? '' }}"
			maxlength="50"
			required
			autocomplete="email"
		>
		<input
			type="text"
			class="input js-input-no-space"
			name="phone"
			placeholder="Numero di telefono"
			value="{{ $address->phone ?? '' }}"
			maxlength="30"
			autocomplete="tel"
			required
		>

		<div @class([
			'hidden!' => !$isShipped,
		])>
			<textarea
			 class="input w-full"
			 name="courier_notes"
			 placeholder="Note per il corriere (opzionale)"
			 maxlength="250"
			>{{ $cart->courier_notes ?? '' }}</textarea>
		</div>
	</div>
</section>
