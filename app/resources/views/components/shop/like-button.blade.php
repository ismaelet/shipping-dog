<?php

use App\Models\Ecommerce\Design;
use Illuminate\Support\Facades\Auth;

$design = $design ?? (isset($design_id) ? Design::find($design_id) : null);

?>

@pushOnce('styles')
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
				margin: auto;
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
@endpushOnce

@pushOnce('scripts')
	<script defer>
		function toggleLike(target) {
			const button = target.closest('.js-like-button');
			const designId = parseInt(button.dataset.designId);
			const likeId = parseInt(button.dataset.likeId);

			if (likeId) {
				destroyLike(likeId, designId);
			} else {
				storeLike(designId);
			}
		}

		function storeLike(designId) {
			axios.post('/api/likes', {
					user_id: <?= Auth::id() ?>,
					design_id: designId
				})
				.then(response => {

					const buttons = document.querySelectorAll('.js-like-button[data-design-id="' + designId + '"]');

					buttons.forEach(button => {
						button.dataset.likeId = response.data.like.id;
						button.dataset.liked = 'true';
					});
				})
				.catch(error => {
					console.error(error);
				});
		}

		function destroyLike(likeId, designId) {
			axios.delete(`/api/likes/${likeId}`)
				.then(response => {

					const buttons = document.querySelectorAll('.js-like-button[data-design-id="' + designId + '"]');
					buttons.forEach(button => {
						button.dataset.likeId = null;
						button.dataset.liked = 'false';
					});

				})
				.catch(error => {
					console.error(error);
				});
		}
	</script>
@endpushOnce

@if ($design)
	@if (Auth::check())
		@if ($design->seller->user_id != Auth::id())
			<div
				class="like-button stack-content js-like-button grid"
				x-data="{ liked: {{ $design->isLiked() ? 'true' : 'false' }} }"
				:data-liked="liked ? 'true' : 'false'"
				:title="liked ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti'"
				data-design-id="{{ $design->id }}"
				data-like-id="{{ $design->auth_user_like->id ?? null }}"
				@click="liked = !liked; toggleLike(event.target)"
			>
				{!! embed_icon('love-stroke') !!}
				{!! embed_icon('love') !!}
			</div>
		@endif
	@else
		<a
			class="like-button stack-content grid"
			href="{{ client_url('/login?url=' . json_encode(request()->url())) }}"
			data-liked="false"
			title="Aggiungi ai preferiti"
		>
			{!! embed_icon('love-stroke') !!}
			{!! embed_icon('love') !!}
		</a>

	@endif
@endif
