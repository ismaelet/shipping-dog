@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/contacts.css') }}"
	>
@endpushOnce

<x-layouts.legacy-main
	:$title
	:$locationTag
>

	<div
		id="slider"
		class="center-content full-width grid"
	>

		<x-typography.main-heading class="text-center">Conta<span class="text-primary">tt</span>i</x-typography.main-heading>

	</div>

	<!-- Body -->
	<div id="body">

		<div class="main-wrap">

			<div
				id="grid"
				class="grid2 gap3 al-top"
			>

				<!-- Contact data -->
				<section
					id="data"
					class="grid-cell grid grid-cols-1 content-start gap-4"
				>

					<div class="">
						<x-typography.section-heading>Shipping Dog</x-typography.section-heading>
						<p class="data-txt">Andrea Trentini</p>
					</div>

					<p class="data-txt"><a
							class="text-link"
							href="<?= config('custom.contacts.email.url') ?>"
						><?= config('custom.contacts.email.label') ?></a></p>
					<p class="data-txt">Piazza Scopoli, 9<br>38033 Cavalese (TN)</p>

					<section
						id="contacts"
						class="flex gap-4"
					>
						<a
							href="<?= config('custom.contacts.whatsapp.url') ?>"
							class="hover:text-primary w-5 text-neutral-700 transition-colors"
						>
							<?= embed_icon('whatsapp') ?>
						</a>
						<a
							href="<?= config('custom.contacts.instagram.url') ?>"
							class="hover:text-primary w-5 text-neutral-700 transition-colors"
						>
							<?= embed_icon('instagram') ?>
						</a>
					</section>

					<div class="border-surface-500 my-4 border-b"></div>

					<p class="">
						<a
							href="{{ route('support.refund') }}"
							class="text-link"
						>Cambio taglia o reso</a>
					</p>

				</section>

				<!-- Contact form -->
				<section
					id="form"
					class="grid-cell"
				>
					<form
						method="POST"
						autocomplete="off"
					>

						<div
							id="form-anch"
							class="anchor"
						></div>

						<x-typography.section-heading class="text-center">Scrivici</x-typography.section-heading>

						<?php if ($sent) { ?>
						<p class="confirmation mar-t-2">Messaggio inviato, ti risponderemo al più presto</p>
						<?php } ?>

						<?php if ($error == 'email_bad') { ?>
						<p class="error">Errore: indirizzo email non riconosciuto. Puoi comunque a scriverci direttamente a
							<?= config('custom.contacts.email.url') ?>, invece di usare questo modulo.</p>
						<?php } ?>

						<?php if ($error == 'word_bad') { ?>
						<p class="error">Errore: il messaggio non ha superato i nostri filtri anti-spam. Puoi comunque scriverci
							direttamente a <?= config('custom.contacts.email.url') ?>, invece di usare questo modulo.</p>
						<?php } ?>

						<div class="form-lbl-box">
							<p class="form-lbl">Nome e cognome</p>
						</div>
						<input
							type="text"
							name="name"
							class="form-inp"
							onfocus="$(this).prev().addClass('focus')"
							onblur="if (!$(this).val().length) {$(this).prev().removeClass('focus')}"
							required
						>

						<div class="form-lbl-box">
							<p class="form-lbl">Indirizzo email</p>
						</div>
						<input
							type="email"
							name="email"
							class="form-inp"
							onfocus="$(this).prev().addClass('focus')"
							onblur="if (!$(this).val().length) {$(this).prev().removeClass('focus')}"
							required
						>

						<div class="form-lbl-box">
							<p class="form-lbl">Testo del messaggio</p>
						</div>
						<textarea
						 name="text"
						 class="form-textarea"
						 maxlength="500"
						 onfocus="$(this).prev().addClass('focus')"
						 onblur="if (!$(this).val().length) {$(this).prev().removeClass('focus')}"
						 required
						></textarea>

						<div class="txt-rig mar-t-2">
							<x-button
								type="submit"
								id="submit"
								name="submit"
								class="mt-4"
							>Invia</x-button>
						</div>
					</form>
				</section>

			</div>
		</div>
	</div>

</x-layouts.legacy-main>
