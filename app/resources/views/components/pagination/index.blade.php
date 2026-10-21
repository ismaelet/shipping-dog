@props(['recordCount', 'pageSize' => 50])

@php
	$currentPage = max(1, (int) request()->input('page', 1));
	$pageCount = (int) ceil($recordCount / $pageSize);

	$currentPage = min($currentPage, max(1, $pageCount));

	$dynamicStart = max(2, $currentPage - 1);
	$dynamicEnd = min($pageCount - 1, $currentPage + 1);

	if ($currentPage <= 3) {
	    $dynamicEnd = min($pageCount - 1, 4);
	}

	if ($currentPage >= $pageCount - 2) {
	    $dynamicStart = max(2, $pageCount - 3);
	}
@endphp

@if ($pageCount > 1)
	<nav
		class="pagination flex flex-wrap items-center justify-center gap-2"
		aria-label="Pagination"
	>
		{{-- Previous --}}
		@if ($currentPage > 1)
			<x-pagination.previous :page="$currentPage - 1" />
		@endif

		{{-- First page --}}
		<x-pagination.page-number
			:page="1"
			:current-page="$currentPage"
		/>

		{{-- Start ellipsis --}}
		@if ($dynamicStart > 2)
			<x-pagination.ellipsis />
		@endif

		{{-- Dynamic pages --}}
		@for ($i = $dynamicStart; $i <= $dynamicEnd; $i++)
			<x-pagination.page-number
				:page="$i"
				:current-page="$currentPage"
			/>
		@endfor

		{{-- End ellipsis --}}
		@if ($dynamicEnd < $pageCount - 1)
			<x-pagination.ellipsis />
		@endif

		{{-- Last page --}}
		<x-pagination.page-number
			:page="$pageCount"
			:current-page="$currentPage"
		/>

		{{-- Next --}}
		@if ($currentPage < $pageCount)
			<x-pagination.next :page="$currentPage + 1" />
		@endif
	</nav>
@endif
