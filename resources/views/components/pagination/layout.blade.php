@props(['recordCount', 'pageSize' => 50])

@php
    $currentPage = request()->page ?? 1;
    $pageCount = ceil($recordCount / $pageSize);
    $continuousStart = $currentPage < 5;
    $countinuousEnd = $currentPage > $pageCount - 4;
    $dynamicStart = $continuousStart ? 2 : $currentPage - 1;
    $dynamicEnd = $countinuousEnd ? $pageCount - 1 : max($currentPage + 1, 5);
@endphp

@if ($pageCount > 1)
    <nav class="flex justify-center items-center gap-1">

        @if ($currentPage > 1)
            {{-- Skit to the first page --}}
            <x-pagination.first page="1" />

            {{-- Go back one --}}
            <x-pagination.previous :page="$currentPage - 1" />
        @endif

        {{-- First page --}}
        <x-pagination.page-number :page="1" :current-page="$currentPage" />

        {{-- Ellipsis --}}
        @if (!$continuousStart)
            <x-pagination.ellipsis />
        @endif

        {{-- Dynamic pages --}}
        @for ($i = $dynamicStart; $i <= $dynamicEnd; $i++)
            <x-pagination.page-number :page="$i" :current-page="$currentPage" />
        @endfor

        {{-- Ellipsis --}}
        @if (!$countinuousEnd)
            <x-pagination.ellipsis />
        @endif

        {{-- Last page --}}
        <x-pagination.page-number :page="$pageCount" :current-page="$currentPage" />

        @if ($currentPage < $pageCount)
            {{-- Go forward one --}}
            <x-pagination.next :page="$currentPage + 1" />

            {{-- Skit to the last page --}}
            <x-pagination.last :page="$pageCount" />
        @endif

    </nav>
@endif
