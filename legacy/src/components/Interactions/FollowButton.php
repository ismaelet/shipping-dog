<?php

use App\Models\Ecommerce\Seller;
use Illuminate\Support\Facades\Auth;

$seller = Seller::find($seller_id);

?>

<template>

	<?php if ($seller) { ?>
		<?php if (Auth::check()) {
			if ($seller_id != Auth::id()) {
		?>

				<div class="follow-button" x-data="{ followed: <?= $seller->isFollowed() ? 'true' : 'false' ?> }" :data-followed="followed ? 'true' : 'false'" @click="followed = !followed; followed ? storeFollower(<?= $seller_id ?>) : destroyFollower(<?= $seller->auth_follower->id ?? null ?>);">
					<span>segui</span>
					<span>seguendo</span>
					<span>non seguire</span>
				</div>

			<?php } ?>

		<?php } else { ?>

			<a href="<?= client_url('/login?url=' . $_SERVER['REQUEST_URI']) ?>" class="follow-button">segui</a>

		<?php } ?>
	<?php } ?>

</template>

<style>
	.follow-button {
		display: inline-block;
		background-color: #739fb1;
		border: none;
		border-radius: 10px;
		padding-block: 0.5em;
		width: 6rem;
		color: #fff;
		font-size: var(--font-size-300);
		font-family: Arial;
		letter-spacing: 0.03rem;
		text-align: center;
		cursor: pointer;
		transition: background-color 0.2s ease-in-out;

		& span {
			display: none;
		}

		&[data-followed="false"] {
			& span:nth-child(1) {
				display: block;
			}
		}

		&[data-followed="true"]:not(:hover) {
			& span:nth-child(2) {
				display: block;
			}
		}

		&[data-followed="true"]:hover {
			& span:nth-child(3) {
				display: block;
			}
		}

		&:hover {
			background-color: #5c7a8c;
		}
	}
</style>

<script>
	function storeFollower(sellerId) {
		axios.post('/api/followers', {
				user_id: sellerId,
				follower_id: <?= Auth::id() ?>
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

	function destroyFollower(followerId) {
		axios.delete(`/api/followers/${followerId}`)
			.then(response => {

				<?php if (env('DEBUG')) { ?>
					console.log(response.data);
				<?php } ?>

			})
			.catch(error => {
				console.error(error);
			});
	}
</script>