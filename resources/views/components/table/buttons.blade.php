@aware(['buttons'])

<div class="flex items-center gap-4 flex-wrap">

    @foreach ($buttons as $button)
        <x-table.button :$button />
    @endforeach
</div>
