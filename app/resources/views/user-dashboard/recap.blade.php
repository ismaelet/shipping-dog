@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/user-dashboard-recap.css') }}"
	>
@endpushOnce

<x-user-dashboard :$title>
	<x-user-dashboard.panel
		:icon="'user'"
		:$title
	>
		<div class="form grid grid-cols-1 gap-8">

			<x-typography.component-heading
				id="title"
				class="text-center"
			>Attività recenti</x-typography.component-heading>

			<p class="text-center">

				<?php

			if (App\Helpers\App::isSeller()) { ?>

				Qui puoi vedere le nostre comunicazioni, le vendite più recenti, quando un utente inizia a seguiriti o quando
				aggiunge uno dei tuoi prodotti ai preferiti.

				<?php } else { ?>

				Qui puoi vedere le nostre comunicazioni e gli aggiornamenti degli artisti che segui

				<?php } ?>

			</p>


			<!-- Feed -->
			<div class="feed grid gap-4">

				<?php foreach ($feedEntries as $feedEntry) { ?>

				<div class="feed__row grid">
					<img
						class="feed__image"
						src="<?= $feedEntry->clientImage ?>"
						loading="lazy"
					>
					<p class="feed__text"><?= $feedEntry->text ?></p>
				</div>

				<?php } ?>

			</div>
		</div>
	</x-user-dashboard.panel>
</x-user-dashboard>
