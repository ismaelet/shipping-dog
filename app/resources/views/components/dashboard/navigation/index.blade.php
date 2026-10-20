@props(['navigationGroups'])

@pushOnce('styles')
	<style>
		.navigation {
			position: relative;
			width: 18rem;
			transition: width 0.4s;
		}

		@scope (.navigation)

			{
			.navigation-content {
				position: sticky;
				top: 0;
				height: 100svh;
				padding-inline: 2.5rem;
				overflow: auto;
				scrollbar-width: thin;
				scrollbar-color: var(--color-surface-400) var(--color-background-400);
			}
		}
	</style>
@endpushOnce

<aside class="navigation js-navigation">
	<div class="navigation-content">

		<div class="">
			<!-- Dashboard title -->
			<div class="navigation-header mb-12 mt-10 grid gap-2 text-center text-neutral-700">
				{!! embed_svg('logo/logo', ['w-full', 'h-10']) !!}
				<p class="font-heading text-2xl font-semibold tracking-wider">{{ config('app.name') }}</p>
			</div>

			<!-- Dashboard groups -->
			<div class="js-open-navigation grid gap-4">

				@foreach ($navigationGroups as $navigationGroup)
					<x-dashboard.navigation.group :navigation-group="$navigationGroup" />
				@endforeach
			</div>

		</div>
</aside>
