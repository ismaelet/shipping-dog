@props(['navigationGroup'])

@pushOnce('styles')
	<style>
		@scope (.navigation-group)

			{

			.category {
				grid-template-rows: max-content 0fr;
				overflow: hidden;
				transition: grid-template-rows 0.4s;

				&:has(:checked) {
					grid-template-rows: max-content 1fr;
				}
			}

			.category-header {
				display: grid;
				grid-template-columns: 1.1rem 1fr;
				transition: grid-template-columns 0.4s, color .3s;

				.category:has(:checked) & {
					color: var(--color-heading-400);
				}
			}

			.category-title {
				white-space: nowrap;
				overflow: hidden;
				line-height: 3.5rem;
			}

			.category-dropdown {
				overflow: hidden;
			}
		}

		.navigation-divisor:last-child {
			display: none;
		}
	</style>
@endpushOnce

<ul class="navigation-group">

	@foreach ($navigationGroup as $category)
		<li>
			<label class="category grid">
				@if (isset($category['url']))
					<a
						class="category-header categorys-center cursor-pointer"
						href="{{ $category['url'] }}"
					>
						<x-dynamic-component :component="'icon-' . $category['icon']" />
						<p class="category-title font-heading ml-4">{{ $category['name'] }}</p>
					</a>
				@else
					<div class="category-header categorys-center cursor-pointer">
						{{-- <input type="checkbox" name="navigation_category" class="hidden js-radio-checkbox"> --}}
						<x-dynamic-component :component="'icon-' . $category['icon']" />
						<p class="category-title font-heading ml-4">{{ $category['name'] }}</p>
					</div>

					<!-- Dropdown -->
					<ul class="category-dropdown">

						@foreach ($category['items'] as $item)
							<x-dashboard.navigation.item :item="$item" />
						@endforeach

					</ul>
				@endif
			</label>
		</li>
	@endforeach

</ul>

<span class="navigation-divisor border-b"></span>
