<x-layouts.form
	:$title
	:action="route('seller.payment.update')"
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

		<div class="grid gap-2">
			<x-form.group>
				<x-inputs.label text="Nome e cognome" />
				<x-inputs.input
					name="first_name"
					placeholder="Nome"
					value="{{ $paymentRequest?->first_name }}"
					required
					data-size="2"
				/>
				<x-inputs.input
					name="last_name"
					placeholder="Cognome"
					value="{{ $paymentRequest?->last_name }}"
					required
					data-size="2"
				/>
			</x-form.group>

			<x-form.group>
				<x-inputs.label text="Data di nascita" />
				<x-inputs.input
					type="date"
					name="birthdate"
					value="{{ $paymentRequest?->birthdate }}"
					required
				/>
			</x-form.group>

			<x-form.group>
				<x-inputs.label text="Città e provincia di nascita" />
				<x-inputs.input
					name="birthplace"
					placeholder="Città"
					value="{{ $paymentRequest?->birthplace }}"
					required
					data-size="3"
				/>
				<x-inputs.input
					class="uppercase"
					name="birthprovince"
					placeholder="Provincia"
					value="{{ $paymentRequest?->birthprovince }}"
					required
					data-size="1"
				/>
			</x-form.group>

			<x-form.group>
				<x-inputs.label text="Indirizzo di residenza" />
				<x-inputs.input
					name="address"
					placeholder="Indirizzo"
					value="{{ $paymentRequest?->address }}"
					required
				/>
				<x-inputs.input
					name="city"
					placeholder="Città"
					value="{{ $paymentRequest?->city }}"
					required
					data-size="2"
				/>
				<x-inputs.input
					class="uppercase"
					name="province"
					placeholder="Provincia"
					value="{{ $paymentRequest?->province }}"
					required
					data-size="1"
				/>
				<x-inputs.input
					name="zip"
					placeholder="CAP"
					value="{{ $paymentRequest?->zip }}"
					required
					data-size="1"
				/>
			</x-form.group>

			<x-form.group>
				<x-inputs.label text="Dati personali" />
				<x-inputs.input
					class="uppercase"
					name="cf"
					placeholder="Codice fiscale"
					value="{{ $paymentRequest?->cf }}"
					required
				/>
				<x-inputs.input
					name="national_id"
					placeholder="Numero della carta di identità"
					value="{{ $paymentRequest?->national_id }}"
					required
				/>
			</x-form.group>

			<x-form.group>
				<x-inputs.label text="Indirizzo PayPal" />
				<x-inputs.input
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
				:href="route('seller.payment')"
				variant="secondary"
			>
				Indietro
			</x-button>
		@endif
	</x-slot>

</x-layouts.form>
