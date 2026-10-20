@php
	$header = [
	    'wishlist' => $showWishlist,
	    'light-mode-switcher' => $showThemeSwitcher,
	    'login-link' => ['Login', route('login')],
	    'logged-link' => ['Profilo', '/user-dashboard'],
	];
@endphp

{{-- <x-header.banner /> --}}

<div class="header-placeholder hidden"></div>

<header class="header z-100 sticky top-0">
	<div class="border-neutral-border bg-background-400/85 px-content-padding border-b backdrop-blur-xl">
		<div class="mx-auto flex h-20 w-full max-w-7xl items-center justify-between">
			{{-- Left bar --}}
			<div class="flex items-center gap-6">
				<x-header.hamburger-icon />
				<x-header.name />
			</div>

			{{-- Center bar --}}
			<div>
				<x-header.desktop-navigation
					:links="$desktopLinks"
					:location-tag="$location"
				/>
			</div>

			{{-- Right bar --}}
			<div>
				<x-header.navigation-icons
					:show-wishlist="$showWishlist"
					:show-theme-switcher="$showThemeSwitcher"
				/>
			</div>
		</div>
	</div>

	{{-- Expandable search bar --}}
	<x-header.search-bar />

	{{-- Mobile navigation panel --}}
	<x-header.mobile-navigation :links="$mobileLinks" />
</header>

@pushOnce('styles')
	<style>
		:root {
			--header-height: 5rem;
		}

		.header {
			height: var(--header-height);
		}

		.header:has(.header-mobile-toggle-input:checked) {
			position: fixed;
			left: 0;
			width: 100%;
		}

		.header-placeholder:has(+ .header .header-mobile-toggle-input:checked) {
			display: block;
			height: var(--header-height);
		}

		/* Search bar */
		.header-search {
			display: none;
		}

		.header-search.is-open {
			display: block;
			animation: header-search-enter 180ms ease-out both;
		}

		@keyframes header-search-enter {
			from {
				opacity: 0;
				transform: translateY(-8px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@media (prefers-reduced-motion: reduce) {
			.header-search.is-open {
				animation: none;
			}
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const toggle = document.getElementById('header-search-toggle');
			const search = document.getElementById('header-search');
			const input = document.getElementById('header-search-input');

			if (!toggle || !search || !input) return;

			function setSearchOpen(open) {
				search.classList.toggle('is-open', open);
				toggle.setAttribute('aria-expanded', String(open));
				search.setAttribute('aria-hidden', String(!open));

				if (open) {
					const mobileMenu = document.getElementById('header-mobile-toggle');

					if (mobileMenu) {
						mobileMenu.checked = false;
					}

					input.focus();
				}
			}

			toggle.addEventListener('click', () => {
				const isOpen = search.classList.contains('is-open');
				setSearchOpen(!isOpen);
			});

			document.addEventListener('keydown', (event) => {
				if (event.key === 'Escape' && search.classList.contains('is-open')) {
					setSearchOpen(false);
					toggle.focus();
				}
			});

			document.addEventListener('click', (event) => {
				const header = document.querySelector('.header');

				if (
					header &&
					!header.contains(event.target) &&
					search.classList.contains('is-open')
				) {
					setSearchOpen(false);
				}
			});
		});
	</script>
@endPushOnce
