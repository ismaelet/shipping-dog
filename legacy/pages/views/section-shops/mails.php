{% block content %}

<h1 class="marb2 txt-3rem txt-med">Lista e-email</h1>

<div id="emails" class="grid gap2 mar-t-2">

	<?php for ($i = 1; $i <= $blocksN; $i++) { ?>

		<div class="panel block marb1">

			<h2 class="marb1 txt-med">Blocco <?= $i ?></h2>

			<p class="emails  mar-t-1" style="word-break: break-all;">

				<?php $j = 0;
				while ($j < $block && $shop = $shops->fetch_assoc()) {
					$j++;

					echo $shop['email'] . '<br>';
				} ?>

			</p>

			<div class="copy pbtn wid-100 mar-t-2">Copia lista</div>

		</div>

	<?php } ?>

</div>

{% end content %}