<form
	action="/search"
	method="GET"
	role="search"
	class="header-search relative -ml-4 flex w-max shrink-0 items-center overflow-hidden"
>
	<input
		type="checkbox"
		id="header-search-toggle"
		class="peer sr-only"
	/>

	<label
		for="header-search-toggle"
		class="header-search-toggle peer-checked:border-primary-400 peer-checked:bg-primary-400 size-11.25 relative grid shrink-0 cursor-pointer place-items-center border border-transparent transition-[background-color,border-color] duration-200"
		aria-label="Apri ricerca"
	>
		<x-icon-search class="relative z-10 w-1/2 fill-neutral-700 transition-colors peer-checked:fill-white" />
	</label>

	<div
		class="header-search-input duration-400 peer-checked:w-75 w-0 overflow-hidden align-middle transition-[width] max-sm:peer-checked:w-[calc(100vw-96px)]"
	>
		<input
			type="search"
			name="search"
			placeholder="Cerca un prodotto o tag"
			aria-label="Cerca un prodotto o tag"
			class="h-11.25 w-full rounded-r-md border border-neutral-300 bg-transparent px-4 outline-none"
		/>
	</div>

	<div
		class="header-search-name relative top-px ml-2 max-w-full overflow-hidden transition-[max-width] delay-300 duration-100 peer-checked:max-w-0 peer-checked:delay-0"
	>
		<a
			href="{{ route('home') }}"
			class="block whitespace-nowrap text-[22px] uppercase tracking-widest text-neutral-700 max-[480px]:hidden"
		>
			Shi<span class="text-primary">pp</span>ing Dog
		</a>
	</div>
</form>

@pushOnce('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			document.querySelectorAll('.header-search').forEach(search => {
				const toggle = search.querySelector('input[type="checkbox"]');
				const input = search.querySelector('input[type="search"]');

				toggle?.addEventListener('change', () => {
					if (toggle.checked) {
						input?.focus();
					}
				});
			});
		});
	</script>
@endPushOnce
