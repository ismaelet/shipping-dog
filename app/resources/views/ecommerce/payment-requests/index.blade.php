<x-layouts.form
	:$title
	:action="route('seller-dashboard.payment.download')"
>
	{{-- Body --}}
	<x-slot name="body">
		<p class="">
			Per poter procedere al pagamento ti chiediamo di stampare il <span class="font-bold text-neutral-700">PDF</span> che
			puoi scaricare qui sotto,
			firmarlo ed inviarlo all'indirizzo e-email <a href="{{ config('custom.contacts.email.url') }}"><span
					class="font-bold text-neutral-700"
				>{{ config('custom.contacts.email.label') }}</span></a>.
		</p>

		@if ($toBePayed >= 70)
			<p class="">
				Il campo della marca da bollo <span class="txt-red font-semibold">è da lasciare vuoto</span>.
			</p>
		@endif

		<p class="">
			Procederemo al pagamento entro
			<span
				class="font-bold text-neutral-700">{{ $waitingDays > 1 ? $waitingDays . ' giorni lavorativi' : 'un giorno lavorativo' }}</span>
			dal momento
			in cui riceveremo il documento firmato.<br>
			{{ $waitingDays > 2 ? 'Ci scusiamo per il tempo di attesa elevato, putroppo dipende dalle nuove normative burocratiche introdotte nel 2022.' : '' }}
		</p>
	</x-slot>

	{{-- Footer --}}
	<x-slot name="footer">

		<x-button type="submit">
			{!! embed_icon('download') !!}
			Scarica il <strong>PDF</strong>
		</x-button>

		<x-button
			name="send"
			:href="route('seller-dashboard.payment.edit')"
			variant="secondary"
		>
			{!! embed_icon('edit') !!}
			Modifica i dati di fatturazione
		</x-button>
	</x-slot>

</x-layouts.form>
