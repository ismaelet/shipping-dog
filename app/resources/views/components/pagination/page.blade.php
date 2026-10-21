@props(['page', 'currentPage' => null])

<button
	type="submit"
	name="page"
	value="{{ $page }}"
	@class([
		'pagination-page',
		'flex size-8 shrink-0 items-center justify-center',
		'rounded-full text-sm font-semibold',
		'transition-colors duration-200',
		'cursor-pointer',
		' bg-primary-400 text-white hover:bg-primary-500' => $page == $currentPage,
		'hover:text-primary-500' => $page != $currentPage,
	])
	@if ($page == $currentPage) aria-current="page" @endif
>
	{{ $slot }}
</button>
