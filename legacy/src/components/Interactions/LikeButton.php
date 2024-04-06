<?php

use App\Models\Ecommerce\Design;
use Illuminate\Support\Facades\Auth;

$design = Design::find($designId);

?>

<template>

	<?php if ($design) { ?>
		<?php if (Auth::check()) {
			if ($design->seller->userID != Auth::id()) {
		?>

				<div class="like-button grid stack-content" x-data="{ liked: <?= $design->isLiked() ? 'true' : 'false' ?> }" :data-liked="liked ? 'true' : 'false'" @click="liked = !liked; toggleLike(<?= $designId ?>, liked)" :title="liked ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti'">
					<?= icon_embed('love-stroke') ?>
					<?= icon_embed('love') ?>
				</div>

			<?php }
		} else { ?>

			<a class=" like-button grid stack-content" href="<?= client_url('/login?url=' . $_SERVER['REQUEST_URI']) ?>" data-liked="false" title="Aggiungi ai preferiti">
				<?= icon_embed('love-stroke') ?>
				<?= icon_embed('love') ?>
			</a>

	<?php }
	} ?>

</template>

<style>
	.like-button {
		position: absolute;
		top: 0.5rem;
		right: 0.5rem;
		width: 1.8rem;
		aspect-ratio: 1;
		background-color: #fff;
		border-radius: 50%;
		box-shadow: 0 5px 7px rgba(0, 0, 0, 0.15);
		color: #404040;
		cursor: pointer;
		transition: color .2s;

		&:hover {
			color: var(--color-primary-400);
		}

		& svg {
			--icon-size: 60%;
			transition: opacity .2s;
		}

		&[data-liked="true"]:not(:hover) {
			& svg:first-child {
				opacity: 0;
			}
		}

		&[data-liked="true"]:hover {
			& svg:last-child {
				opacity: 0;
			}
		}

		&[data-liked="false"]:not(:hover) {
			& svg:last-child {
				opacity: 0;
			}
		}

		&[data-liked="false"]:hover {
			& svg:first-child {
				opacity: 0;
			}
		}
	}
</style>

<script>
	function toggleLike(designId, liked) {
		if (liked) {
			axios.post('/api/like', {
					designId: designId
				})
				.then(response => {

					<?php if (env('DEBUG')) { ?>
						console.log(response.data);
					<?php } ?>

				})
				.catch(error => {
					console.error(error);
				});
		} else {
			axios.post('/api/unlike', {
					designId: designId
				})
				.then(response => {

					<?php if (env('DEBUG')) { ?>
						console.log(response.data);
					<?php } ?>

				})
				.catch(error => {
					console.error(error);
				});
		}
	}
</script>