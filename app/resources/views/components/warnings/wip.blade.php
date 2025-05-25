@if (!(env('APP_ENV') == 'local'))
	<article
		class="wip-warning text-error-400 border-error-400 z-100 fixed bottom-4 left-4 right-4 rounded border bg-neutral-50/80 px-4 py-2 text-center text-sm font-semibold sm:bottom-8 sm:left-8 sm:right-8 sm:text-base"
	>
		<p>
			Il sito è in fase di aggiornamento, alcune funzionalità potrebbero non essere disponibili. In caso si riscontrassero
			problemi, potete contattarci tramite email a
			<a
				href="{{ config('custom.contacts.email.url') }}"
				class="text-neutral-700 underline"
			>
				{{ config('custom.contacts.email.label') }}
			</a>. Ci scusiamo per il disagio e vi ringraziamo per la comprensione.
		</p>
	</article>
@endif
