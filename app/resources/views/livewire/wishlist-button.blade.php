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
			cursor: pointer;
			transition: color .2s;

			& svg {
				--icon-size: 50%;
				transition: opacity .2s;
				opacity: 0;
				margin: auto;
			}

			&[data-wishlisted="false"]:not(:hover) {
				& svg:first-child {
					opacity: 1;
				}
			}

			&[data-wishlisted="false"]:hover {
				& svg:last-child {
					opacity: 1;
				}
			}

			&[data-wishlisted="true"]:not(:hover) {
				& svg:last-child {
					opacity: 1;
				}
			}

			&[data-wishlisted="true"]:hover {
				& svg:first-child {
					opacity: 1;
				}
			}
		}
	</style>
@endpushOnce

<div>
	@if (Auth::check())
		@if ($design->seller->user_id != Auth::id())
			<div
				class="like-button text-neutral-light-400 stack-content hover:text-primary grid place-items-center"
				title="{{ $wishlisted ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' }}"
				data-wishlisted="{{ $wishlisted ? 'true' : 'false' }}"
				wire:click="{{ $wishlisted ? 'destroy' : 'store' }}"
			>
				<x-icon-love-stroke class="w-1/2" />
				<x-icon-love class="w-1/2" />
			</div>
		@endif
	@else
		<a
			class="like-button text-neutral-light-400 stack-content hover:text-primary grid place-items-center"
			href="{{ route('login', ['url' => json_encode(request()->url())]) }}"
			data-wishlisted="false"
			title="Aggiungi ai preferiti"
		>
			<x-icon-love-stroke class="w-1/2" />
			<x-icon-love class="w-1/2" />
		</a>
	@endif
</div>
