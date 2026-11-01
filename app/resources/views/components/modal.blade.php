@props([
    'name',
    'title',
    'size' => 'md',
    'fixedHeight' => false,
    'open' => false,
    'closeOnBackdrop' => true,
])

@php
    $maxWidth = match ($size) {
        'sm' => 'max-w-md',
        'lg' => 'max-w-4xl',
        'xl' => 'max-w-6xl',
        default => 'max-w-xl',
    };
@endphp

@pushOnce('styles')
    <style>
        .modal-body {
            top: calc(var(--header-height, 0px) / 2);
            max-height: calc(100dvh - var(--header-height, 0px) - 4rem);
        }

        .modal-body[data-fixed-height='true'] {
            height: 80svh;
        }
    </style>
@endpushOnce

{{-- Open with: $dispatch('open-modal', { name: '{{ $name }}' }). Pass :open="true" to show it on load (e.g. validation errors). --}}
<div
    x-data="{ open: @js((bool) $open), name: @js($name) }"
    x-on:open-modal.window="if ($event.detail.name === name) open = true;"
    x-on:close-modal.window="if (! $event.detail?.name || $event.detail.name === name) open = false;"
    x-on:keydown.escape.window="open = false"
    {{ $attributes }}
>
    <template x-teleport="body">
        <div
            x-show="open"
            x-trap.noscroll.inert="open"
            style="display: none"
            class="fixed inset-0 z-50 flex items-end justify-center p-0 sm:items-center sm:p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-title-{{ $name }}"
        >
            <div
                x-show="open"
                x-transition.opacity.duration.150ms
                @if ($closeOnBackdrop) x-on:click="open = false" @endif
                class="absolute inset-0 bg-black/60"
                aria-hidden="true"
            ></div>

            <div
                x-show="open"
                x-transition:enter="transition duration-150 motion-reduce:transition-none"
                x-transition:enter-start="translate-y-4 opacity-0 motion-reduce:translate-y-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                class="modal-body {{ $maxWidth }} bg-background-400 relative flex w-full flex-col overflow-hidden rounded-t-xl sm:rounded-xl"
                data-fixed-height="{{ $fixedHeight ? 'true' : 'false' }}"
            >
                <div class="border-border-400 flex items-center justify-between gap-4 border-b px-5 py-4">
                    <x-heading variant="article" id="modal-title-{{ $name }}">{{ $title }}</x-heading>

                    <button
                        type="button"
                        x-on:click="open = false"
                        aria-label="Chiudi"
                        class="text-text-400 hover:text-heading-400 focus-visible:outline-primary-400 grid size-9 shrink-0 cursor-pointer place-items-center rounded-full focus-visible:outline-2"
                    >
                        <x-icon-close class="size-4" />
                    </button>
                </div>

                <div class="min-h-0 flex-1 overflow-y-auto px-5 py-4">{{ $slot }}</div>

                @isset($footer)
                    <div class="border-border-400 flex flex-wrap justify-end gap-2 border-t px-5 py-4">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </template>
</div>
