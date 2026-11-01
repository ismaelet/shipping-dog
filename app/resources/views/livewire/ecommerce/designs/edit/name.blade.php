@pushOnce('styles')
    <style>
        .design-name-input {
            border: none;
            border-bottom: 1px solid transparent;
            outline: none;
            color: var(--color-heading-400);
            transition: border-color 150ms;

            &:focus {
                border-color: var(--color-primary-400);
            }
        }
    </style>
@endpushOnce

@pushOnce('scripts')
    <script defer>
        document.addEventListener('alpine:init', () => {
            Alpine.data('designNameConfirmation', (designId) => ({
                visible: false,
                timer: null,

                get confirmed() {
                    return this.visible && this.$wire.saveStatus === 'saved';
                },

                get label() {
                    return this.confirmed ? 'Nome salvato' : '';
                },

                get iconStyle() {
                    return { opacity: this.confirmed ? 1 : 0 };
                },

                show(savedDesignId) {
                    if (savedDesignId !== designId) return;

                    clearTimeout(this.timer);
                    this.visible = true;
                    this.timer = setTimeout(() => (this.visible = false), 5000);
                },

                destroy() {
                    clearTimeout(this.timer);
                },
            }));
        });
    </script>
@endpushOnce

<form wire:submit="save" class="grid min-w-0 grid-cols-1 content-start gap-1">
    <label for="design-name" class="text-center">Nome della grafica:</label>
    <div class="relative">
        <input
            id="design-name"
            wire:model="value"
            wire:blur="save"
            wire:keydown.enter.prevent="save"
            maxlength="25"
            class="design-name-input focus:border-primary-400 w-full border-0 border-b border-transparent bg-transparent px-8 py-2 text-center text-3xl leading-[1.3] font-bold tracking-wider"
        />

        <span
            class="absolute top-1/2 right-2 -translate-y-1/2"
            x-data="designNameConfirmation(@js($designId))"
            @design-name-saved.window="show($event.detail.designId)"
            wire:dirty.remove
            wire:loading.remove
            role="status"
            :aria-label="label"
        >
            <x-icon-check class="text-success-400 size-6 opacity-0 transition-opacity" ::style="iconStyle" />
        </span>
    </div>
    @error('value')
        <x-alert type="error">{{ $message }}</x-alert>
    @enderror
</form>
