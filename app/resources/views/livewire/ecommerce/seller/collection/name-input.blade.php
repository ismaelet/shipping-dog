<div
    class="grid gap-3"
    x-data="{ showSaved: false, savedTimeout: null }"
    x-on:collection-name-saved.window="
        clearTimeout(savedTimeout);
        showSaved = true;
        savedTimeout = setTimeout(() => (showSaved = false), 2000);
    "
>
    <div class="inline-flex items-center">
        <input
            type="text"
            class="text-750 font-heading text-neutral-heading"
            name="name"
            placeholder="Nome collezione"
            wire:model.live.debounce.500ms="name"
            autocomplete="off"
        />

        <div
            class="-ml-8 inline-flex h-5 w-5 items-center justify-center text-sm leading-none transition-opacity"
            aria-live="polite"
        >
            <span wire:loading wire:target="name"> … </span>

            <span
                wire:loading.remove
                wire:target="name"
                x-show="@js($errors->has('name')) || showSaved"
                x-transition.opacity
                @class([
                    'text-2xl',
                    'text-[#067e00]' => $saved && ! $errors->has('name'),
                    'text-[#c71619]' => $errors->has('name'),
                ])
            >
                @if ($errors->has('name'))
                    !
                @elseif ($saved)
                    ✓
                @endif
            </span>
        </div>
    </div>

    @error('name')
        <p class="text-sm text-[#c71619]">{{ $message }}</p>
    @enderror
</div>
