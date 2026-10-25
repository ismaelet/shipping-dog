<div class="header-navigation-icons flex items-center gap-1">
	<button
		type="button"
		id="header-search-toggle"
		class="header-icon-wrapper"
		aria-label="Cerca"
		aria-controls="header-search"
		aria-expanded="false"
	>
		<x-icon-search />
	</button>


	<a
		href="{{ route('user-dashboard') }}"
		class="header-icon-wrapper"
	>
		<x-icon-user />
	</a>

	{{-- @if ($showThemeSwitcher)
		<div class="header-theme-switcher header-icon-wrapper">
			<button
				type="button"
				class="js-color-scheme-switcher flex cursor-pointer items-center justify-center dark:hidden"
				data-color-scheme="dark"
				aria-label="Attiva modalità scura"
			>
				<x-icon-moon />
			</button>

			<button
				type="button"
				class="js-color-scheme-switcher hidden size-full cursor-pointer items-center justify-center dark:flex"
				data-color-scheme="light"
				aria-label="Attiva modalità chiara"
			>
				<x-icon-sun />
			</button>
		</div>
	@endif --}}

	@if ($showWishlist)
		<a
			href="{{ route('user-dashboard.wishlist') }}"
			class="header-icon-wrapper"
			aria-label="Preferiti"
			data-mobile="false"
		>
			<x-icon-heart />
		</a>
	@endif

	<livewire:header.cart-icon />
</div>

@pushOnce('styles')
	<style>
		.header-icon-wrapper {
			@apply transition-colors;
			display: none;
			align-items: center;
			justify-content: center;
			width: 2rem;
			height: 2rem;
			border-radius: 9999px;
			color: var(--color-heading-400);
			cursor: pointer;

			&:hover {
				background-color: color-mix(in srgb,
						var(--color-heading-400) 5%,
						transparent);
			}

			svg {
				flex-shrink: 0;
				width: 1.375rem;
				height: 1.375rem;
			}

			&:not([data-mobile="false"]) {
				display: flex;
			}

			@media (min-width: 40rem) {
				display: flex;
				width: 2.5rem;
				height: 2.5rem;
			}
		}
	</style>
@endpushOnce
