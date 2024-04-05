@props(['title' => '', 'icon' => null])

@pushOnce('styles')
    <style>
        .user-dashboard-panel {
            padding-inline: var(--content-padding);
            padding-block: var(--content-padding);
            border-radius: var(--border-radius-400);
            box-shadow: var(--shadow-big);
        }

        .user-dashboard-panel__title {
            padding-bottom: var(--content-padding);
            margin-bottom: var(--content-padding);
            border-bottom: var(--border);
        }
    </style>
@endpushOnce

<section class="user-dashboard-panel">

    {{-- Panel header --}}
    <div class="user-dashboard-panel__title flex gap-4 items-center">
        <span class="w-6 aspect-square text-secondary-400">
            {{ import_svg('icons/' . $icon) }}
        </span>

        <x-typography.article-heading>{{ $title }}</x-typography.article-heading>
    </div>

    {{-- Panel body --}}
    <div class="">
        {{ $slot }}
    </div>
</section>
