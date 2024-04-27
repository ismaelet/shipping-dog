@pushOnce('styles')
    <style>
        .status-icon {
            --icon-size: 50%;
            border-color: currentColor;
            color: var(--_status-color);
        }

        .order-list {
            grid-template-columns: max-content 1fr max-content;
        }

        .order-row {
            display: grid;
            grid-template-columns: subgrid;
            grid-column: 1 / -1;
            align-items: center;
            padding-top: .5rem;

            &.order {
                padding-block: 1rem;
                border-top-width: var(--border-width);
            }

            &:nth-last-child(2) {
                padding-bottom: 1rem;
                border-bottom-width: var(--border-width);

            }

            .order+&:not(.order) {
                padding-top: 1rem;
                border-top-width: var(--border-width);
            }

            > :first-child {
                margin-left: 1rem;
            }
        }
    </style>
@endpushOnce

<x-dashboard.form :$title :$type>

    <x-slot name="body">
        <div class="grid lg:grid-cols-7 gap-12">

            {{-- Main content --}}
            <div class="lg:col-span-4">
                {{-- Order recap --}}
                <x-dashboard.form.panel title="Dati dell'ordine">
                    <div class="flex gap-4 items-center">
                        <div class="status-icon rounded-full aspect-square h-12 border"
                            style="--_status-color: {{ $record->statusColor() }}">
                            {!! embed_icon($record->statusIcon()) !!}
                        </div>

                        <div>
                            <h3 class="">{{ $record->statusName() }}</h3>
                            <p class="text-sm">Ordinato il {{ $record->timeString() }}</p>
                        </div>
                    </div>

                    {{-- Orders --}}
                    <div class="order-list grid gap-x-4 mt-4">
                        <div class="order-row">
                            <p class="font-medium text-neutral-300">Prodotto</p>
                            <p class="font-medium text-neutral-300">Dettagli</p>
                            <p class="font-medium text-neutral-300">Prezzo</p>
                        </div>

                        <!-- Orders list -->

                        @foreach ($record->orders as $order)
                            <div class="order-row order">
                                <img src="{{ $order->product->image() }}" alt="" class="rounded w-24">

                                <p class="">
                                    <a href="{{ $order->product->fullUrl($order->color_id, $order->position_id) }}">
                                        {{ $order->fullName() }}
                                    </a>
                                </p>

                                <p class="">{{ $order->priceString() }}</p>
                            </div>
                        @endforeach

                        @if ($record->coupon)
                            <div class="order-row">
                                <p class="">Codice</p>
                                <span></span>
                                <p class="">{{ $record->code }}</p>
                            </div>
                        @endif

                        @if ($record->discount())
                            <div class="order-row">
                                <p class="">Sconto</p>
                                <span></span>
                                <p class="">-{{ $record->discountString() }}</p>
                            </div>
                        @endif

                        <div class="order-row">
                            <p class="">Spedizione</p>
                            <span></span>
                            <p class="">{{ $record->shippingPriceString() }}</p>
                        </div>

                        <div class="order-row text-lg text-neutral-700">
                            <p class="">Totale</p>
                            <span></span>
                            <p class="">{{ $record->priceString() }}</p>
                        </div>
                    </div>

                </x-dashboard.form.panel>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-3 grid gap-12 items-start">
                {{-- Customer data --}}
                <x-dashboard.form.panel title="Dati del cliente">
                    <x-form.group>
                        <h3>Nome completo</h3>
                        <p>{{ $record->address->full_name }}</p>
                    </x-form.group>

                    <x-form.group>
                        <h3>Contatti</h3>
                        <p>Tel.:
                            <a class="text-neutral-700 transition-colors hover:text-primary"
                                href="tel:{{ $record->address->phone }}">
                                {{ $record->address->phone }}</a>
                        </p>
                        <p>E-mail:
                            <a class="text-neutral-700 transition-colors hover:text-primary"
                                href="mailto:{{ $record->address->email }}">{{ $record->address->email }}</a>
                        </p>
                    </x-form.group>
                </x-dashboard.form.panel>

                {{-- Delivery data --}}
                <x-dashboard.form.panel title="Destinatario">
                    <p class="">{!! $record->address->fullAddress() !!}</p>

                    @if ($record->status > 0)
                        <div class="flex gap-2">
                            <x-inputs.input name="tracking" value="{{ $record->tracking }}"
                                placeholder="Codice di tracking" class="grow" />

                            <span class="hidden">
                                <x-inputs.button type="submit" size="auto" name="update_tracking">
                                    {!! embed_icon('save') !!}
                                    Salva
                                </x-inputs.button>
                            </span>
                        </div>
                    @endif

                    {{-- <div>
                        <a href="edit-address?id={{ $record->address->id }}"
                            class="btn button--small btn--secondary button--auto-width">Modifica</a>
                    </div> --}}
                </x-dashboard.form.panel>
            </div>
        </div>
    </x-slot>

    {{-- Footer --}}
    <x-slot name="footer">

        <div class="flex gap-4 justify-between w-full">
            <div class="flex gap-4">
                @if ($record->status > 0)
                    <x-inputs.button type="submit" variant="secondary" color="error" name="new_status" value="-1">
                        {!! embed_icon('delete') !!}
                        Cancella l'ordine
                    </x-inputs.button>
                @endif

                @if ($record->status != 0)
                    <x-inputs.button type="submit" variant="secondary" name="new_status" value="0">
                        {!! embed_icon('back') !!}
                        Riapri il carrello
                    </x-inputs.button>
                @endif
            </div>

            @if ($record->status == -1)
                <x-inputs.button type="submit" name="new_status" value="1">
                    {!! embed_icon('correct') !!}
                    Ripristina l'ordine
                </x-inputs.button>
            @elseif (!$record->status)
                <x-inputs.button type="submit" name="new_status" value="1">
                    {!! embed_icon('correct') !!}
                    Conferma l'ordine
                </x-inputs.button>
            @elseif ($record->status == 1)
                <x-inputs.button type="submit" name="new_status" value="2">
                    {!! embed_icon('correct') !!}
                    Etichetta pronta
                </x-inputs.button>
            @elseif ($record->status == 2)
                <x-inputs.button type="submit" name="new_status" value="3">
                    {!! embed_icon('correct') !!}
                    Spedito
                </x-inputs.button>
            @elseif ($record->status == 3)
                <x-inputs.button type="submit" name="new_status" value="4">
                    {!! embed_icon('correct') !!}
                    Consegnato
                </x-inputs.button>
            @endif
        </div>

    </x-slot>
</x-dashboard.form>
