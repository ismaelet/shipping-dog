@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/contacts.css') }}"
	>
	<style>
		.form-inp,
		.form-textarea {
			display: inline-block;
			background-color: transparent;
			width: 100%;
			border: none;
			border-bottom: 1px solid var(--color-neutral-400);
			border-radius: 0;
			padding: 0;
			margin: 0;
			color: var(--color-neutral-700);
			font-size: 0.9rem;
			box-sizing: border-box;
			outline: none !important;
		}

		.form-textarea {
			height: 11rem;
			padding-top: 1rem;
			resize: none;
		}


		.form-inp:-webkit-autofill,
		.form-textarea:-webkit-autofill {
			-webkit-box-shadow: 0 0 0 1000vw var(--color-background) inset;
			-webkit-text-fill-color: var(--color-neutral-700);
		}
	</style>
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
					class="grid-cell overflow-hidden"
				>
					<form
						method="POST"
						autocomplete="off"
						class="grid grid-cols-1 gap-4"
					>
						@csrf

						<x-typography.section-heading class="text-center">Scrivici</x-typography.section-heading>

						@if (session('success'))
							<x-alerts.success>{{ session('success') }}</x-alerts.success>
						@endif

						@if (session('error'))
							<x-alerts.error>{!! session('error') !!}</x-alerts.error>
						@endif

						<div class="">
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
						</div>

						<div class="txt-rig">
							<x-button
								type="submit"
								class="mt-4"
							>Invia</x-button>
						</div>
					</form>
				</section>

			</div>
		</div>
	</div>

</x-layouts.legacy-main>
