@pushOnce('styles')
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

			&:hover {
				background-color: #5c7a8c;
			}
		}
	</style>
@endpushOnce

<div @class(['hidden' => !$isVisible])>
	@if ($isVisible)
		@if ($isAuthenticated)
			<div
				class="follow-button group"
				wire:click="{{ $isFollowing ? 'unfollow' : 'follow' }}"
			>

				@if ($isFollowing)
					<span class="group-hover:hidden">seguendo</span>
					<span class="hidden group-hover:block">non seguire</span>
				@else
					<span>segui</span>
				@endif
			</div>
		@else
			<a
				href="{{ route('login') }}"
				class="follow-button"
			>segui</a>
		@endif
	@endif
</div>
