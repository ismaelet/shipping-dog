@props(['page', 'currentPage'])

<x-pagination.page :$page :$currentPage>
    {{ $page }}
</x-pagination.page>
