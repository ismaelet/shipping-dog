@php
	$links = [
	    [
	        'title' => 'Attività recenti',
	        'dashboard_location' => 'recap',
	        'url' => route('user-dashboard'),
	        'icon' => 'user',
	    ],
	    [
	        'title' => 'Wishlist',
	        'dashboard_location' => 'wishlist',
	        'url' => route('user-dashboard.wishlist'),
	        'icon' => 'love',
	    ],
	    [
	        'title' => 'Lista ordini',
	        'dashboard_location' => 'orders',
	        'url' => route('user-dashboard.orders'),
	        'icon' => 'cart',
	    ],
	    [
	        'title' => 'Il mio negozio',
	        'dashboard_location' => null,
	        'url' => route('seller.show', ['seller' => auth()->user()->id]),
	        'icon' => 'shop',
	        'condition' => auth()->user()->isSeller(),
	    ],
	    [
	        'title' => 'I miei dati',
	        'dashboard_location' => 'settings',
	        'url' => route('user-dashboard.settings'),
	        'icon' => 'settings',
	    ],
	    [
	        'title' => 'Logout',
	        'dashboard_location' => null,
	        'url' => route('logout'),
	        'icon' => 'logout',
	    ],
	];
@endphp

@pushOnce('styles')
	<style>
		.user-dashboard-navigation {
			position: sticky;
			top: 2rem;

			.dark & {
				border: none;
			}
		}

		.user-dashboard-navigation ul {
			overflow: hidden;
		}

		@media (max-width: 768px) {
			.user-dashboard-navigation {
				grid-template-rows: max-content 0fr;
				transition: grid-template-rows 0.3s;

				[data-open="true"] & {
					grid-template-rows: max-content 1fr;
				}
			}
		}
	</style>
@endpushOnce

<nav
	x-data="{ open: false }"
	:data-open="open ? 'true' : 'false'"
>
	<div class="user-dashboard-navigation bg-surface-400 surface grid overflow-clip rounded-lg">
		{{-- Title --}}
		<label
			class="bg-surface-500 md:hidden"
			@click="open = !open"
		>
			<h3 class="flex items-center gap-4 px-4 py-4 text-xl">
				<span class="w-5">
					{!! embed_icon('list') !!}
				</span>
				Profilo
			</h3>
		</label>

		{{-- Navigation link --}}
		<ul class="">

			@foreach ($links as $link)
				@if (!isset($link['condition']) || $link['condition'])
					<x-user-dashboard.link :$link />
				@endif
			@endforeach

		</ul>
	</div>
</nav>
