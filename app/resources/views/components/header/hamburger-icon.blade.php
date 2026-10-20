@props([
    'links' => [],
])

<input
	type="checkbox"
	id="header-mobile-toggle"
	class="header-mobile-toggle-input sr-only"
/>

<label
	for="header-mobile-toggle"
	aria-label="Apri menu"
	class="header-mobile-toggle h-16.25 relative inline-block w-6 shrink-0 cursor-pointer xl:hidden"
>
	<span class="hamburger-top absolute right-0 top-1/2 h-0.5 w-full rounded-full">
		<span class="bg-neutral-heading block size-full"></span>
	</span>

	<span class="hamburger-middle bg-neutral-heading absolute right-0 top-1/2 h-0.5 w-full rounded-full"></span>

	<span class="hamburger-bottom absolute right-0 top-1/2 h-0.5 w-full rounded-full">
		<span class="bg-neutral-heading block size-full"></span>
	</span>
</label>

@pushOnce('styles')
	<style>
		/* Hamburger: initial state */
		.header-mobile-toggle .hamburger-top {
			transform: translateY(-11px);
			transition: transform 0.2s 0.2s;
		}

		.header-mobile-toggle .hamburger-middle {
			transform: translateY(-1px);
			opacity: 1;
			transition: opacity 0.1s 0.1s, transform 0.2s 0.1s;
		}

		.header-mobile-toggle .hamburger-bottom {
			transform: translateY(9px);
			transition: transform 0.2s 0.2s;
		}

		.header-mobile-toggle .hamburger-top>span,
		.header-mobile-toggle .hamburger-bottom>span {
			transform: rotate(0deg);
			transition: transform 0.2s;
		}

		/* Hamburger: opened state */
		.header-mobile-toggle-input:checked+.header-mobile-toggle .hamburger-top,
		.header-mobile-toggle-input:checked+.header-mobile-toggle .hamburger-bottom {
			transform: translateY(-1px);
			transition: transform 0.2s;
		}

		.header-mobile-toggle-input:checked+.header-mobile-toggle .hamburger-top>span {
			transform: rotate(45deg);
			transition: transform 0.2s 0.2s;
		}

		.header-mobile-toggle-input:checked+.header-mobile-toggle .hamburger-bottom>span {
			transform: rotate(-45deg);
			transition: transform 0.2s 0.2s;
		}

		.header-mobile-toggle-input:checked+.header-mobile-toggle .hamburger-middle {
			transform: translate(1rem, -1px);
			opacity: 0;
			transition: opacity 0.1s, transform 0.1s;
		}
	</style>
@endPushOnce
