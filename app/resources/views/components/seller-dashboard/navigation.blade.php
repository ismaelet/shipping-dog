@php

	$currentLocation = explode('seller-dashboard.', request()->route()->getName())[1] ?? 'index';

	$navigation = [
	    [
	        'title' => 'Impostazioni',
	        'icon' => 'settings',
	        'links' => [
	            'index' => 'Generali',
	        ],
	    ],
	    [
	        'title' => 'Grafiche',
	        'icon' => 'portfolio',
	        'links' => [
	            'portfolio' => 'Lista grafiche',
	            'portfolio.add' => 'Carica una grafica',
	        ],
	    ],
	    [
	        'title' => 'Vendite',
	        'icon' => 'graph',
	        'links' => [
	            'sales' => 'Lista vendite',
	            'prices' => 'Modifica prezzi',
	        ],
	    ],
	    // [
	    //     'title' => 'Foto clienti',
	    //     'icon' => 'pic',
	    //     'links' => [
	    //         'photos' => 'Lista foto',
	    //         'photos.add' => 'Carica una foto',
	    //     ],
	    // ],
	];

@endphp

@pushOnce('styles')
	<style>
		.navigation-category {
			grid-template-rows: max-content 0fr;
			transition: grid-template-rows 0.3s;
		}

		.navigation-title {
			grid-template-columns: max-content minmax(0, 1fr) max-content;
			align-items: center;
		}

		.navigation-title::before {
			content: '';
			opacity: 0;
			background-color: var(--color-primary-400);
			box-shadow: -5px 0 1rem 0 var(--color-primary-400), -5px 0 2rem 0 var(--color-primary-400);
			width: 3px;
			height: 2rem;
			position: absolute;
			top: 50%;
			left: 0;
			transform: translateY(-50%);
			transition: opacity 0.2s;
		}

		.navigation-dropdown {
			overflow: hidden;
			opacity: 0;
			transition: opacity 0.3s;
		}

		.navigation-category:has(:checked) {
			grid-template-rows: max-content 1fr;

			.navigation-title {
				/* cursor: default; */
			}

			.navigation-title::before {
				opacity: 1;
			}

			.navigation-arrow {
				transform: rotate(180deg);
			}

			.navigation-dropdown {
				opacity: 1;
			}
		}

		@media (max-width: 1023px) {
			.dashboard-navigation {
				display: grid;
				grid-template-rows: max-content 0fr;
				transition: grid-template-rows 0.3s;
			}

			.dashboard-categories {
				overflow: hidden;
			}

			.dashboard-navigation:has(.mobile-toggler:checked) {
				grid-template-rows: max-content 1fr;
			}
		}
	</style>
@endpushOnce

<div class="dashboard-navigation">

	{{-- Mobile toggler --}}
	<label class="font-heading dashboard-mobile-toggler flex justify-center gap-4 py-6 text-lg text-neutral-700 lg:hidden">
		<input
			type="checkbox"
			class="mobile-toggler hidden"
		>
		<span class="w-5 shrink-0">
			{!! embed_icon('list') !!}</span>
		Menu Gestione
	</label>

	{{-- Categories --}}
	<div
		class="dashboard-categories border-surface-500 grid grid-cols-1 gap-6 border-b lg:sticky lg:top-8 lg:mt-8 lg:border-b-0"
	>

		@foreach ($navigation as $category)
			<div class="navigation-category group grid last:pb-8 lg:last:pb-0">
				<label
					class="navigation-title relative grid cursor-pointer gap-4 pl-8 pr-8 text-neutral-300 transition-colors group-[:has(:checked)]:text-neutral-700 lg:pr-0"
				>
					<input
						type="radio"
						class="hidden"
						name="nav-category"
					>

					<div class="h-4 w-4">
						{!! embed_icon($category['icon']) !!}
					</div>

					<p class="font-heading font-semibold">{{ $category['title'] }}</p>

					<span class="navigation-arrow w-3 transition-transform">
						{!! embed_icon('arrow-simple-down') !!}
					</span>

				</label>

				<!-- Dropdown menu -->
				<ul class="navigation-dropdown grid gap-4 pl-16">

					@foreach ($category['links'] as $linkSlug => $linkLabel)
						<li class="navigation-link text-sm first:pt-3">
							<label>
								<a
									class="transition-colors hover:text-neutral-700 has-[:checked]:text-neutral-700"
									href="{{ route($linkSlug == 'index' ? 'seller-dashboard' : 'seller-dashboard.' . $linkSlug) }}"
								>
									<input
										type="radio"
										class="hidden"
										name="nav-link"
										{{ $currentLocation == $linkSlug ? 'checked' : '' }}
									>
									{{ $linkLabel }}
								</a>
							</label>
						</li>
					@endforeach

				</ul>
			</div>
		@endforeach

	</div>
</div>
