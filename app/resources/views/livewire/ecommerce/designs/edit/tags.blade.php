<form wire:submit="save" class="card grid min-w-0 gap-4 p-6">
    <x-heading variant="article">Tags</x-heading>
    <label for="design-tags" class="sr-only">Tags</label
    ><x-textarea
        id="design-tags"
        class="w-full min-w-0"
        wire:model="value"
        placeholder="(Opzionale) Puoi inserire fino a 10 tag separati da virgola. I tag rendono il tuo design più facile da trovare."
    />
    @error('value')
        <x-alert type="error">{{ $message }}</x-alert>
    @enderror
    <x-ecommerce.designs.edit.save-feedback :status="$saveStatus" />
</form>
