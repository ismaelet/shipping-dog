<?php

use App\Models\Ecommerce\Design;
use Illuminate\Support\Facades\Auth;

$design = Design::find($design_id);

?>

<template>

	<?php if ($design) { ?>
		<?php if (Auth::check()) {
			if ($design->seller->user_id != Auth::id()) {
		?>

				<div class="like-button grid stack-content" x-data="{ liked: <?= $design->isLiked() ? 'true' : 'false' ?> }" :data-liked="liked ? 'true' : 'false'" @click="liked = !liked; liked ? storeLike(<?= $design_id ?>) : destroyLike(<?= $design->auth_user_like->id ?? null ?>);" :title="liked ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti'">
					<?= embed_icon('love-stroke') ?>
					<?= embed_icon('love') ?>
				</div>

			<?php }
		} else { ?>

			<a class=" like-button grid stack-content" href="<?= client_url('/login?url=' . json_encode(request()->url())) ?>" data-liked="false" title="Aggiungi ai preferiti">
				<?= embed_icon('love-stroke') ?>
				<?= embed_icon('love') ?>
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
			--icon-size: 70%;
			transition: opacity .2s;
			opacity: 0;
		}

		&[data-liked="false"]:not(:hover) {
			& svg:first-child {
				opacity: 1;
			}
		}

		&[data-liked="false"]:hover {
			& svg:last-child {
				opacity: 1;
			}
		}

		&[data-liked="true"]:not(:hover) {
			& svg:last-child {
				opacity: 1;
			}
		}

		&[data-liked="true"]:hover {
			& svg:first-child {
				opacity: 1;
			}
		}
	}
</style>

<script>
	function storeLike(designId) {
		axios.post('/api/likes', {
				user_id: <?= Auth::id() ?>,
				design_id: designId
			})
			.then(response => {

				<?php if (env('DEBUG')) { ?>
					console.log(response);
				<?php } ?>

			})
			.catch(error => {
				console.error(error);
			});
	}

	function destroyLike(likeId) {
		axios.delete(`/api/likes/${likeId}`)
			.then(response => {

				<?php if (env('DEBUG')) { ?>
					console.log(response);
				<?php } ?>

			})
			.catch(error => {
				console.error(error);
			});
	}
</script>