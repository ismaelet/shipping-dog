@aware(['buttons'])

<div class="flex items-center gap-4">

    @foreach ($buttons as $button)
        <x-table.button :$button />
    @endforeach
</div>
