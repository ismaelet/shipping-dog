<?php

function love_button($design) {

	global $user;

	if ($design->sellerId != $user->id) {

		if (logged()) { ?>

			<div class="love-btn <?= $design->loved ? 'active' : '' ?>" data-active="1" data-id="<?= $design->id ?>" data-loved="<?= $design->loved ?>" data-hroot="<?= CLIENT_ROOT ?>">

				<div class="icon"></div>

			</div>

		<?php } else { ?>

			<a class="love-btn" href="<?= client_url('/login?url=' . $_SERVER['REQUEST_URI']) ?>">

				<div class="icon"></div>

			</a>

<?php }
	}
}
