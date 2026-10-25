@pushOnce('styles')
    <style>
        .quantity {
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type='number'] {
                border: none;
                outline: none;
                padding: 0;
                margin: 0;
                text-align: center;
                -moz-appearance: textfield;
            }
        }
    </style>
@endpushOnce

<form
    class="mt-8 flex"
    wire:submit.prevent="addToCart"
    x-data="{
    quantity: @entangle('quantity'),
    maxQuantity: @js($maxQuantity),

    decrease() {
        this.quantity = Math.max(1, Number(this.quantity || 1) - 1)
    },

    increase() {
        this.quantity = Math.min(this.maxQuantity, Number(this.quantity || 1) + 1)
    },
}"
>
    <label class="quantity !border-border-400 !text-heading-400 flex !flex-none grow !items-center rounded-l-full border border-r-0 border-neutral-300 sm:grow-0">
        <button
            type="button"
            @click="decrease()"
            :disabled="quantity <= 1"
            class="!w-10 cursor-pointer disabled:cursor-default disabled:opacity-50"
        >
            -
        </button>

        <input
            type="number"
            name="quantity"
            x-model.number="quantity"
            min="1"
            :max="maxQuantity"
            step="1"
            class="!text-heading-400 !w-[2.125rem] !bg-transparent"
        />

        <button
            type="button"
            @click="increase()"
            :disabled="quantity >= maxQuantity"
            class="!w-10 cursor-pointer disabled:cursor-default disabled:opacity-50"
        >
            +
        </button>
    </label>

    <x-button type="submit" class="relative grow rounded-l-none">
        <span class="contents" wire:loading.class="invisible">
            Aggiungi al carrello
            <x-icon-bag class="size-[1.25em]!" />
        </span>

        <x-icon-loading class="absolute h-4 w-12" wire:loading />
    </x-button>
</form>
