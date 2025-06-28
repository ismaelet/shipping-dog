@pushOnce('scripts')
	<script defer>
		function redirectToPage(page) {
			const url = new URL(window.location.href);
			url.searchParams.set('page', page);
			url.hash = 'product-list';
			window.location.href = url;
		}
	</script>
@endpushOnce

<div class="contents">
	@if ($pageCount > 1)
		<div
			id="page-box"
			class="mar-t-3 text-center"
		>
			@if ($bottomPage > 1)
				<p class="page_txt disabled">...</p>
			@endif

			@for ($i = $bottomPage; $i <= $topPage; $i++)
				@if ($i == $page)
					<p class="page_txt clickable selected disabled">{{ $i }}</p>
				@else
					<p
						class="page_txt clickable"
						@click="redirectToPage({{ $i }})"
					>{{ $i }}</p>
				@endif
			@endfor

			@if ($pageCount > $topPage)
				<p class="page_txt disabled">...</p>
			@endif

		</div>
	@endif
</div>
