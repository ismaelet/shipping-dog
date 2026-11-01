@pushOnce('styles')
    <style>
        .cart-sidebar.active .cart-sidebar__backdrop {
            opacity: 1;
            pointer-events: all;
        }

        .cart-sidebar__body {
            position: absolute;
            right: 0;
            translate: 100% 0;
            min-width: min(25rem, 100%);
            height: 100%;
            padding: 1rem;
            background-color: var(--color-background-400);
            box-shadow: -0.5rem 0 1rem rgba(0, 0, 0, 0.1);
            pointer-events: all;
            transition: translate 0.2s;
        }

        .cart-sidebar.active .cart-sidebar__body {
            translate: none;
        }

        .cart-sidebar__content {
            display: grid;
            height: 100%;
            grid-template-rows: max-content minmax(0, 1fr) max-content;
        }

        .cart-sidebar .cart-orders {
            overflow-y: auto;
        }

        .cart-sidebar .cart-order {
            grid-template-columns: 0.66rem minmax(0, 1fr) 4.5rem;
            grid-template-areas: 'remove data image';
        }

        .cart-sidebar .cart-order_title {
            font-size: 0.9rem;
        }
    </style>
@endpushOnce

<section
    x-data="{ show: false }"
    class="cart-sidebar pointer-events-none fixed inset-0 z-100"
    :class="{ active: show }"
    @open-cart-sidebar.window="show = true"
>
    <div
        class="cart-sidebar__backdrop absolute inset-0 cursor-pointer bg-black/60 opacity-0 transition-opacity"
        @click="show = false"
    ></div>

    <div class="cart-sidebar__body">
        <div class="cart-sidebar__content">
            <!-- Header -->
            <div class="border-border-400 flex flex-wrap items-center justify-between gap-2 border-b pb-4">
                <x-heading variant="article">Carrello</x-heading>

                <button
                    type="button"
                    class="cursor-pointer opacity-70 transition-opacity hover:opacity-100"
                    @click="show = false"
                    aria-label="Chiudi il carrello"
                >
                    <x-icon-close class="size-5" />
                </button>
            </div>

            <!-- Order items -->
            <div class="overflow-auto py-4 pr-4">
                @if (! $orderItems || $orderItems->isEmpty())
                    <p class="text-center">Il carrello è vuoto</p>
                @else
                    @if ($cartId)
                        <livewire:cart.order-items-list
                            :order-id="$cartId"
                            :key="'cart-sidebar-order-items-'.$cartId.'-'.$quantity"
                        />
                    @endif
                @endif
            </div>

            {{-- Footer --}}
            <div class="">
                <div class="border-border-400 mb-4 flex justify-between border-b py-4">
                    <p>Subtotale:</p>

                    <p class="text-heading-400 font-semibold">{{ price($subtotal) }}</p>
                </div>

                <div class="grid gap-2">
                    <x-button variant="secondary" size="sm" @click="show = false">Chiudi</x-button>

                    <x-button href="{{ route('cart') }}" size="sm" :disabled="$isEmpty">
                        <x-icon-bag />
                        Acquista ora
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</section>
