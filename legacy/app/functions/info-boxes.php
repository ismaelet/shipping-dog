<?php

function info_box($text) { ?>

	<div class="tip-box inlb clickable">

		<div class="icon-box">
			<?= embed_icon('question') ?>
		</div>

		<p class="tip-icon-txt"><?= $text ?></p>

	</div>

<?php }
