{% block content %}

<form class="grid-flow" data-columns="20rem" data-spacing="700" method="POST">

	<?php foreach ($panels as $panel) { ?>

		<div class="surface grid-flow center-content" data-spacing="200">
			<h2 class="subsection-heading"><?= $panel['count'] . ' ' . $panel['title'] ?></h2>
			<p><?= $panel['subtitle'] ?></p>
			<button type="submit" class="btn button--full-width mar-t-1" name="orders">
				<?= embed_icon('clean') ?>
				Pulisci
			</button>
		</div>

	<?php } ?>

</form>

{% end content %}