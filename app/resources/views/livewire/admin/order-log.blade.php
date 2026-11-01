@script
    <script>
        $wire.on('copyOrders', (data) => {
            const firstOrderId = data[0];
            const firstOrder =
                document.querySelector(`.js-order[data-id='${firstOrderId}']`) || document.querySelector(`.js-order`);

            copyFrom(firstOrder);
        });
    </script>
@endscript

@pushOnce('scripts')
    <script defer>
        function copyFrom(firstOrder) {
            if (!firstOrder) {
                return;
            }

            const orders = [];
            let currentOrder = firstOrder;

            while (currentOrder) {
                if (currentOrder.classList.contains('js-order')) {
                    orders.push(currentOrder);
                }

                currentOrder = currentOrder.nextElementSibling;
            }

            const texts = [];

            orders.forEach((order) => {
                const orderTexts = [];
                const toCopyElements = order.querySelectorAll('.js-to-copy');

                toCopyElements.forEach((el) => {
                    orderTexts.push(el.innerText);
                });

                texts.push(orderTexts.join('\n').replace(/(\n\s*){2,}/g, '\n'));
            });

            // Remove multiple \n to have at most one \n in a row
            const mergedTexts = texts.join('\n\n---\n\n');

            navigator.clipboard
                .writeText(mergedTexts)
                .then(() => {})
                .catch((err) => {
                    console.error('Errore nella copia: ', err);
                });
        }
    </script>
@endpushOnce

@pushOnce('styles')
    <style>
        .dashboard-panel {
            display: grid;
            align-content: start;
            gap: 1rem;
            border-radius: var(--radius-xl);
            background-color: white;
            padding: 1rem;
            box-shadow:
                var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow),
                var(--tw-ring-shadow), var(--tw-shadow);
            border: 1px solid;
            border-color: color-mix(in oklab, var(--color-white) 10%, transparent);

            .dark & {
                background-color: var(--gray-900);
            }
        }

        .dashboard-panel-title {
            padding-bottom: 0.5rem;
            padding-left: var(--radius-xl);
            font-size: 1.25rem;
            font-weight: 600;
        }

        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .gap-8 {
            gap: 2rem;
        }

        .opacity-50 {
            opacity: 0.5;
        }

        .opacity-20 {
            opacity: 0.2;
        }

        .pointer-events-none {
            pointer-events: none;
        }
    </style>
@endpushOnce

<div class="grid gap-8">
    {{-- Filter --}}
    <livewire:admin.order-log.filter :$orders :$firstOrder />

    {{-- Orders --}}
    <div class="mt-8 grid grid-cols-1 gap-8" wire:loading.class="opacity-20 pointer-events-none" x-data>
        {{-- If there are orders --}}
        @if ($orders?->count())
            @foreach ($orders as $order)
                @php
                    $title = $order->idCode().' '.($order->address->full_name ?? '');
                    $title =
                        $order->user && $order->user->isSeller() ? $title.' - <span class="text-error">Venditore</span>' : $title;
                @endphp

                <div class="js-order" data-id="{{ $order->id }}" wire:key="order-{{ $order->id }}">
                    <h3 class="dashboard-panel-title">{!! $title !!}</h3>
                    <div class="dashboard-panel">
                        {{-- Order header --}}
                        <p class="js-to-copy">
                            [{{ $order->id }} - {{ $order->createdAtString() }} - {{ $order->payment_method->name }}]
                        </p>

                        {{-- Address --}}
                        @if ($order->address)
                            {{-- If found --}}
                            <div>
                                <p class="js-to-copy">{!! implode('<br>', $order->address->full_address) !!}</p>
                                <p>---</p>
                                <p>{{ $order->address->email }}</p>
                                <p>{{ $order->address->phone }}</p>
                            </div>
                        @else
                            {{-- If error --}}
                            @php
                                $order->has_errors = 1;
                                $order->save();
                            @endphp

                            <p class="text-error">!!! Errore nell'indirizzo !!!</p>
                        @endif

                        {{-- Coupon --}}
                        @if ($order->coupon)
                            <p>Sconto: <span class="font-bold">{{ $order->code }}</span></p>
                        @endif

                        {{-- Order Items --}}
                        <div class="js-to-copy">
                            @foreach ($order->order_items as $orderItem)
                                <p>
                                    @if ($orderItem->inventory == 1)
                                        <span class="text-green font-bold">! IN INVENTARIO !</span>
                                    @endif

                                    @if ($orderItem->inventory == 2)
                                        <span class="text-dark-green font-bold">! SIMILE IN INVENTARIO !</span>
                                    @endif

                                    <a
                                        href="{{ $orderItem->product_with_trashed->fullUrl($orderItem->color_id, $orderItem->position_id) }}"
                                        target="_blank"
                                    >
                                        {{ $orderItem->full_name }}
                                    </a>
                                </p>
                            @endforeach
                        </div>

                        {{-- Shipping --}}
                        <p class="js-to-copy">
                            {!! $order->shipping_method->name !!}, {!! $order->shipping_method->deliveryDateString($order->created_at, $order->hasLongDelivery()) !!}
                        </p>

                        {{-- Price --}}
                        @if (! $order->change_request)
                            <p class="js-to-copy">Totale: <strong>{!! price($order->price) !!}</strong></p>
                        @else
                            <p class="text-blue js-to-copy font-bold">CAMBIO TAGLIA</p>
                        @endif

                        <x-button
                            class="js-copy-from-cart"
                            data-id="{{ $order->id }}"
                            variant="secondary"
                            @click="copyFrom($event.target.closest('.js-order'))"
                        >
                            <x-heroicon-o-clipboard class="text-white" />
                            Copia a partire da qui
                        </x-button>
                    </div>
                </div>
            @endforeach
        @else
            <p class="py-8">
                Nessun ordine registrato dopo
                <span class="text-heading-400 font-bold">{{ $previousOrder?->address->full_name }}</span> del
                <span class="text-heading-400 font-bold">{{ $previousOrder?->created_at->format('d/m/Y') }}</span>
            </p>
        @endif
    </div>
</div>
