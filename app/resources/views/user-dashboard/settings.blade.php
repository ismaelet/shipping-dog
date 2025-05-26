@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/user-dashboard-settings.css') }}"
	>
@endpushOnce

<x-user-dashboard :$title>
	<x-user-dashboard.panel
		:icon="'settings'"
		:$title
	>

		<div class="grid gap-12">
			<form
				class="grid grid-cols-1 gap-8"
				method="POST"
			>
				<div
					id="data-anch"
					class="anchor"
				></div>

				<x-typography.component-heading class="dashboard-heading">Dati profilo</x-typography.component-heading>

				<div class="grid grid-cols-1 gap-4">

					@if ($done == 'data')
						<x-alerts.success>Dati modificati con successo</x-alerts.success>
					@endif

					<x-inputs.label>Username:</x-inputs.label>
					<input
						type="text"
						class="input inp-nospace"
						name="username"
						value="<?= Auth::user()->username ?>"
						placeholder="Username"
						maxlength="16"
						required
					>

					@if ($error == 'user_match')
						<x-alerts.error>L'username scelto è già associato ad un altro
							profilo</x-alerts.error>
					@endif

					<x-inputs.label>Nome e cognome:</x-inputs.label>
					<div class="grid gap-[inherit] md:grid-cols-2">
						<input
							type="text"
							class="input"
							name="first_name"
							value="<?= Auth::user()->first_name ?>"
							placeholder="Nome"
						>
						<input
							type="text"
							class="input"
							name="last_name"
							value="<?= Auth::user()->last_name ?>"
							placeholder="Cognome"
						>
					</div>

					<x-inputs.label>E-mail:</x-inputs.label>
					<input
						type="email"
						class="input"
						name="email"
						value="<?= Auth::user()->email ?>"
						placeholder="email"
						required
					>

					@if ($error == 'email_match')
						<x-alerts.error>L'email scelta è già associata ad un altro profilo</x-alerts.error>
					@endif

				</div>

				<div class="footer flex justify-center">
					<x-button
						type="submit"
						name="edit_data"
					>Modifica</x-button>
				</div>
			</form>

			<form
				class="grid grid-cols-1 gap-8"
				method="POST"
			>
				<div
					id="pass-anch"
					class="anchor"
				></div>

				<x-typography.component-heading class="dashboard-heading">Password</x-typography.component-heading>

				<div class="grid grid-cols-1 gap-4">

					<?php if ($done == 'pass') { ?>

					<x-alerts.success>Password modificata con successo</x-alerts.success>

					<?php } ?>

					<x-inputs.label>Password:</x-inputs.label>

					<input
						type="password"
						class="input"
						name="passOld"
						placeholder="Password attuale"
						maxlength="16"
						required
					>

					<?php if ($error == 'pass_bad') { ?>

					<x-alerts.error>La password inserita non corrisponde alla password attuale</x-alerts.error>

					<?php } ?>

					<input
						type="password"
						class="input"
						name="passNew"
						placeholder="Nuova password"
						maxlength="16"
						required
					>
					<input
						type="password"
						class="input"
						name="passConf"
						placeholder="Conferma nuova password"
						maxlength="16"
						required
					>

					<?php if ($error == 'pass_match') { ?>

					<x-alerts.error>La nuova password e la conferma non corrispondono</x-alerts.error>

					<?php } ?>

				</div>

				<div class="footer flex justify-center">
					<x-button
						type="submit"
						name="edit_pass"
						class=""
					>Modifica</x-button>
				</div>
			</form>

			<form
				class="grid grid-cols-1 gap-8"
				method="POST"
				enctype="multipart/form-data"
			>
				<x-typography.component-heading class="dashboard-heading">Immagine profilo</x-typography.component-heading>

				<div class="grid grid-cols-1 gap-4">
					<x-inputs.label>Modifica l'immagine profilo:</x-inputs.label>
					<input
						type="file"
						name="file"
						class="input file-inp"
					>
					<button
						type="submit"
						name="edit_icon"
						class="file-submit hidden"
					></button>
					<div class="file-load"></div>
				</div>
			</form>

			<form
				class="grid grid-cols-1 gap-8"
				method="POST"
			>
				<x-typography.component-heading class="dashboard-heading">Elimina l'account</x-typography.component-heading>

				<div class="grid grid-cols-1 gap-4">
					<p>Clicca qui sotto se vuoi cancellare definitivamente il tuo account. <strong>L'operazione NON può essere
							annullata.</strong></p>
				</div>

				<div class="footer flex justify-center">
					<x-button
						type="submit"
						name="delete-account"
						class=""
						confirmation="Sei sicuro di voler eliminare l'account? Non potrà essere ripristinato."
						color="danger"
					>
						{!! embed_icon('delete') !!}
						Elimina l'account
					</x-button>
				</div>
			</form>
		</div>

	</x-user-dashboard.panel>
</x-user-dashboard>
