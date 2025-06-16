<x-layouts.form
	:$title
	:action="route('seller-dashboard.payment.update')"
>

	<x-slot name="body">
		<p>
			Invieremo il pagamento al tuo account <span class="font-bold">PayPal</span>. Se non possiedi un
			account PayPal puoi crearlo <a
				href="https://www.paypal.com/it/webapps/mpp/account-selection"
				class="text-primary font-bold"
			>cliccando qui</a>, è rapido e completamente gratuito.<br><br>
			Per poter generare i documenti necessari per il pagamento, abbiamo bisogno di alcuni tuoi dati:
		</p>

		<div class="grid gap-6">
			<x-form.group>
				<x-label text="Nome e cognome" />
				<x-input
					name="first_name"
					placeholder="Nome"
					value="{{ $paymentRequest?->first_name }}"
					required
					data-size="2"
				/>
				<x-input
					name="last_name"
					placeholder="Cognome"
					value="{{ $paymentRequest?->last_name }}"
					required
					data-size="2"
				/>
			</x-form.group>

			<x-form.group>
				<x-label text="Data di nascita" />
				<x-input
					type="date"
					name="birthdate"
					value="{{ $paymentRequest?->birthdate }}"
					required
				/>
			</x-form.group>

			<x-form.group>
				<x-label text="Città e provincia di nascita" />
				<x-input
					name="birthplace"
					placeholder="Città"
					value="{{ $paymentRequest?->birthplace }}"
					required
					data-size="2"
				/>
				<select
					class="input"
					name="birthprovince"
					placeholder="Provincia"
					required
					data-size="2"
				>
					<option
						value=""
						disabled
						@selected(empty($paymentRequest?->birthprovince))
					>Provincia</option>

					@foreach ($provinces as $province)
						<option
							value="{{ $province->tag }}"
							@selected($paymentRequest?->birthprovince == $province->tag)
						>{{ $province->name . ' (' . $province->tag . ')' }}</option>
					@endforeach
				</select>
			</x-form.group>

			<x-form.group>
				<x-label text="Indirizzo di residenza" />
				<x-input
					name="address"
					placeholder="Indirizzo"
					value="{{ $paymentRequest?->address }}"
					required
				/>
				<x-input
					name="zip"
					placeholder="CAP"
					value="{{ $paymentRequest?->zip }}"
					required
					data-size="1"
				/>
				<x-input
					name="city"
					placeholder="Città"
					value="{{ $paymentRequest?->city }}"
					required
					data-size="2"
				/>
				<select
					class="input"
					name="province"
					placeholder="Provincia"
					required
					data-size="1"
				>
					<option
						value=""
						disabled
						@selected(empty($paymentRequest?->province))
					>Provincia</option>

					@foreach ($provinces as $province)
						<option
							value="{{ $province->tag }}"
							@selected($paymentRequest?->province == $province->tag)
						>{{ $province->name . ' (' . $province->tag . ')' }}</option>
					@endforeach
				</select>
			</x-form.group>

			<x-form.group>
				<x-label text="Dati personali" />
				<x-input
					class="uppercase"
					name="cf"
					placeholder="Codice fiscale"
					value="{{ $paymentRequest?->cf }}"
					required
				/>
				<x-input
					name="national_id"
					placeholder="Numero della carta di identità"
					value="{{ $paymentRequest?->national_id }}"
					required
				/>
			</x-form.group>

			<x-form.group>
				<x-label text="Indirizzo PayPal" />
				<x-input
					type="email"
					name="paypal"
					placeholder="Indirizzo PayPal"
					value="{{ $paymentRequest?->paypal }}"
					required
				/>
			</x-form.group>
		</div>
	</x-slot>

	<x-slot name="footer">
		<x-button type="submit">
			{{ $paymentRequest ? 'Modifica' : 'Invia' }}
		</x-button>

		@if ($paymentRequest)
			<x-button
				:href="route('seller-dashboard.payment')"
				variant="secondary"
			>
				Indietro
			</x-button>
		@endif
	</x-slot>

</x-layouts.form>
