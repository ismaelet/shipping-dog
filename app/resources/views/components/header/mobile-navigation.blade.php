@props([
    'links' => [],
])

<nav
	aria-label="Navigazione mobile"
	class="header-mobile-nav bg-background-400/85 fixed z-10 w-full overflow-y-auto backdrop-blur-xl xl:hidden"
>
	<ul class="relative grid grid-cols-1 gap-2 px-4 pt-8 sm:px-8">
		@foreach ($links as $link)
			<li class="header-mobile-link group/mobile relative">

				@if (isset($link['dropdown']))
					<div class="mobile-nav-dropdown relative">
						<input
							type="checkbox"
							id="header-mobile-dropdown-{{ $loop->index }}"
							class="mobile-nav-dropdown-input sr-only"
						/>

						<label
							for="header-mobile-dropdown-{{ $loop->index }}"
							class="mobile-nav-link-header relative flex cursor-pointer items-center gap-4 rounded-sm pl-4 transition-colors"
						>
							<x-dynamic-component
								class="size-5 shrink-0"
								:component="'icon-' . $link['icon']"
							/>

							<span class="text-neutral-heading relative flex items-center gap-2 py-4 text-sm font-medium tracking-widest">
								<span class="">
									{{ $link['title'] }}
								</span>

								<x-icon-chevron class="chevron size-4 shrink-0 rotate-90 transition-transform" />
							</span>
						</label>

						<div class="overflow-hidden">
							@foreach ($link['dropdown'] as $dropdownLink)
								<a
									href="{{ $dropdownLink[1] }}"
									class="mobile-nav-dropdown-link leading-16 ml-6 block overflow-hidden text-sm tracking-widest"
								>
									{{ $dropdownLink[0] }}
								</a>
							@endforeach
						</div>
					</div>
				@else
					<a
						href="{{ $link['url'] }}"
						class="flex items-center gap-4 pl-4"
					>
						<x-dynamic-component
							class="size-5 shrink-0"
							:component="'icon-' . $link['icon']"
						/>

						<span class="mobile-nav-link text-neutral-heading block py-4 text-sm font-medium tracking-widest">
							{{ $link['title'] }}
						</span>
					</a>
				@endif
			</li>
		@endforeach
	</ul>
</nav>

@pushOnce('styles')
	<style>
		/* Mobile navigation panel */
		.header-mobile-nav {
			--td: 400ms;
			--tf: cubic-bezier(0.34, 1.28, 0.82, 1);
			visibility: hidden;
			width: 30rem;
			max-width: 100%;
			height: calc(100dvh - var(--header-height));
			opacity: 0;
			translate: -30vw 0;
			transition: opacity 0.15s, translate 0.15s;
		}

		.header:has(.header-mobile-toggle-input:checked) .header-mobile-nav {
			visibility: visible;
			opacity: 1;
			translate: 0 0;
		}

		/* Mobile navigation links */
		.header-mobile-link {
			translate: -4rem 0;
			opacity: 0;
			transition: height var(--td) var(--tf),
				opacity var(--td) var(--tf),
				translate var(--td) var(--tf);
			transition-delay: calc((sibling-index() - 1) * var(--td)/6);
		}

		.header:has(.header-mobile-toggle-input:checked) .header-mobile-link {
			translate: 0;
			opacity: 1;
		}

		/* Dropdown background */
		.mobile-nav-dropdown-input:checked~.mobile-nav-link-header {
			background-color: var(--color-primary-400);
			color: white;
		}

		/* Dropdown links */
		.mobile-nav-dropdown-link {
			height: 0;
			opacity: 0;
			line-height: 3rem;
			transition: height 0.4s,
				opacity 0.4s;
		}

		.mobile-nav-dropdown-input:checked~div .mobile-nav-dropdown-link {
			height: 3rem;
			opacity: 1;
			translate: 0 0;
		}

		.mobile-nav-dropdown-input:checked~label .chevron {
			rotate: -90deg;
		}
	</style>
@endPushOnce
