@props([
    'message' => $slot,
    'type' => 'error',
])

@php
    $icons = [
        'success' => 'icon-check',
        'error' => 'icon-close',
    ];
@endphp

<span {{
    $attributes->class([
        'alert flex justify-center items-center gap-2',
        'text-success-400' => $type == 'success',
        'text-danger-400' => $type == 'error',
    ])->merge()
}}>
    <x-dynamic-component :component="$icons[$type]" class="size-4" />
    {{ $message }}
</span>
