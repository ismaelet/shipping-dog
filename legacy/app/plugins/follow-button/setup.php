<?php

function element_follow_button($seller) {

	global $user;

	if ($seller->id != auth()->id()) {

		if (auth()->check()) { ?>

			<div class="follow-btn working" data-id="<?= $seller->id ?>" data-followed="<?= $seller->followed ?>" data-root="<?= __SERVER_ROOT__ ?>" data-hroot="<?= CLIENT_ROOT ?>">

				<?= $seller->followed ? 'seguendo' : 'segui' ?>

			</div>

		<?php } else { ?>

			<a href="<?= client_url('/login?url=' . $_SERVER['REQUEST_URI']) ?>" class="follow-btn">
				segui
			</a>

<?php }
	}
}
