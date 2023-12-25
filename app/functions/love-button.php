<?php

function element_love_button($id, $loved) {

	if (logged()) { ?>

		<div class="love-btn working <?php if ($loved) echo 'active' ?>" data-id="<?= $id ?>" data-loved="<?= $loved ?>" data-root="<?= SERVER_ROOT ?>" data-hroot="<?= CLIENT_ROOT ?>">

			<div class="love-icon"></div>

		</div>

	<?php } else { ?>

		<a class="love-btn" href="<?= CLIENT_ROOT ?>/login?url=<?= $_SERVER['REQUEST_URI'] ?>" data-root="<?= SERVER_ROOT ?>" data-hroot="<?= CLIENT_ROOT ?>">

			<div class="love-icon"></div>

		</a>

<?php }
}
