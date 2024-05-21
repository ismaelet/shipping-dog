@props(['page'])

<x-pagination.page :$page>
	<span class="aspect-square w-1/3">
		{!! embed_icon('previous', ['fill-none']) !!}
	</span>
</x-pagination.page>
