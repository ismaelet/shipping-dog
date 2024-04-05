<?php

use User\FeedEntry;

?>

{% block content %}

<div class="form">

	<!-- Title -->
	<div id="title-box" class="brb1 br-e">

		<h1 id="title" class="txt-cen txt-15rem pad-v-2">Attività recenti</h1>

		<p class="pad-b-1 txt-cen">

			<?php

			if (is_seller()) { ?>

				Qui puoi vedere le nostre comunicazioni, le vendite più recenti, quando un utente inizia a seguiriti o quando aggiunge uno dei tuoi prodotti ai preferiti.

			<?php } else { ?>

				Qui puoi vedere le nostre comunicazioni e gli aggiornamenti degli artisti che segui

			<?php } ?>

		</p>

	</div>

	<!-- Feed -->
	<div class="feed grid-flow pad-v-1" data-spacing="400">

		<?php foreach ($feedEntries as $feedEntry) { ?>

			<div class="feed__row grid-flow">
				<img class="feed__image" src="<?= $feedEntry->clientImage ?>" loading="lazy">
				<p class="feed__text"><?= $feedEntry->text ?></p>
			</div>

		<?php } ?>

	</div>
</div>

{% end content %}