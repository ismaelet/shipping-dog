<label class="text-text-400 flex items-center gap-2 text-sm">
    <span class="text-trim">Ordina:</span>

    <x-select
        class="bg-surface-400 hover:bg-surface-500 min-w-40 rounded-full px-4 py-2 text-sm font-semibold"
        wire:change="changeSort($event.target.value)"
    >
        <option value="popular" @selected($sort === 'popular')>Popolari</option>
        <option value="new" @selected($sort === 'new')>Nuovi</option>

        @if ($seller)
            <option value="custom" @selected($sort === 'custom')>Personalizzati</option>
        @endif
    </x-select>
</label>
