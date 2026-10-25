@props(['breadcrumbs'])

<nav {{ $attributes->merge(['class' => 'line-clamp-1 flex items-center gap-3 text-sm']) }} aria-label="Breadcrumb">
    @foreach ($breadcrumbs as $breadcrumb)
        @if ($breadcrumb['url'])
            <a
                class="hover:text-primary-200 transition-colors"
                href="{{ $breadcrumb['url'] }}"
            >{{ $breadcrumb['label'] }}</a>
        @else
            <span>{{ $breadcrumb['label'] }}</span>
        @endif

        @if (! $loop->last)
            <span aria-hidden="true">›</span>
        @endif
    @endforeach
</nav>
