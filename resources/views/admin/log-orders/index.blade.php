@pushOnce('styles')
    <style>
        .log {
            font-family: Courier New;

            h3 {
                font-family: inherit;
            }

            strong {
                color: var(--color-neutral-700);
            }
        }
    </style>
@endpushOnce

@pushOnce('scripts')
    <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.js-copy-orders').addEventListener('click', async function() {
                // Get the HTML content of the entire document
                let text = '';

                document.querySelectorAll('.js-order').forEach((order) => {
                    text += order.outerHTML + '--------<br><br><br>';
                })

                copyRichTextToClipboard(text)
            })

            async function copyRichTextToClipboard(html) {
                try {
                    await navigator.clipboard.write([
                        new ClipboardItem({
                            'text/html': new Blob([html], {
                                type: 'text/html'
                            }),
                        }),
                    ])
                    console.log('Rich text copied to clipboard')
                    return true
                } catch (err) {
                    console.error('Failed to copy rich text: ', err)
                    return false
                }
            }
        })
    </script>
@endpushOnce

<x-layouts.admin-dashboard>

    <div class="grid gap-12">

        {{-- Title --}}
        <x-dashboard.typography.main-heading>Log degli ordini</x-dashboard.typography.main-heading>

        {{-- Recap form --}}
        <x-dashboard.form.panel title="Azioni">
            <div class="flex gap-4">
                <x-inputs.button class="grow js-copy-orders">
                    {!! embed_icon('copy') !!}
                    Copia il log
                </x-inputs.button>

                @if ($carts->count())
                    <x-inputs.button type="submit" name="submit" value="clean_log" variant="secondary" class="grow">
                        {!! embed_icon('delete') !!}
                        Pulisci il log
                    </x-inputs.button>
                @else
                @endif
            </div>
        </x-dashboard.form.panel>

        {{-- Carts --}}
        @if ($carts->count())
            @foreach ($carts as $cart)
                @php
                    $title = $cart->idCode() . ' ' . ($cart->address->full_name ?? '');
                    $title =
                        $cart->user && $cart->user->isSeller()
                            ? $title . ' - <span class="text-error">Venditore</span>'
                            : $title;
                @endphp

                <x-dashboard.form.panel :title="$title" id="carts" class="order log js-order">

                    {{-- Order header --}}
                    <p>[{{ $cart->id }} - {{ date('Y-m-d H:i:s', strtotime($cart->time)) }}]</p>

                    {{-- Address --}}
                    @if ($cart->address)
                        {{-- If found --}}
                        <div>
                            <p>{!! $cart->address->fullAddress() !!}</p>
                            ---
                            <p>{{ $cart->address->email }}</p>
                            <p>{{ $cart->address->phone }}</p>
                        </div>
                    @else
                        {{-- If error --}}
                        @php
                            $cart->has_errors = 1;
                            $cart->save();

                            // Get the PayPal data
                            /*$paypalData = paypal_find_order($cart->id);

							if ($paypalData) {
							// Create the address
							$addressLine1 = explode(' ', $paypalData->shipping_info->address->line1);
							$addressNumber = array_pop($addressLine1);
							$addressLine1 = implode(' ', $addressLine1);

							Address::create([
							'cart_id' => $cart->id,
							'user_id' => $reg ? $cart->user_id : null,
							'full_name' => $paypalData->shipping_info->name,
							'address' => $addressLine1,
							'number' => $addressNumber,
							'city' => $paypalData->shipping_info->address->city,
							'zip' => $paypalData->shipping_info->address->postal_code,
							'country' => $paypalData->shipping_info->address->country_code,
							'email' => $paypalData->payer_info->email_address,
							'phone' => $paypalData->payer_info->phone_number->national_number,
							]);*/

                        @endphp

                        <p class="text-error">!!! Errore nell'indirizzo !!!</p>
                    @endif

                    {{-- Coupon --}}
                    @if ($cart->coupon)
                        <p>Sconto: <span class="font-bold">{{ $cart->code }}</span></p>
                    @endif

                    {{-- Orders --}}
                    <div>
                        @foreach ($cart->orders as $order)
                            <p>
                                @if ($order->inventory == 1)
                                    <span class="font-bold text-green">! IN INVENTARIO !</span>
                                @endif

                                @if ($order->inventory == 2)
                                    <span class="font-bold text-dark-green">! SIMILE IN INVENTARIO !</span>
                                @endif

                                <a href="{{ $order->product->url() }}" target="_blank">
                                    {{ $order->fullName() }}
                                </a>
                            </p>
                        @endforeach
                    </div>

                    {{-- Shipping --}}
                    <p>{!! $cart->shipping_method->name !!}, {!! $cart->shipping_method->deliveryDateString($cart->created_at, $cart->hasLongDelivery()) !!}</p>

                    {{-- Price --}}
                    @if (!$cart->change_request)
                        <p>Totale: <strong>{!! $cart->priceString() !!}</strong></p>
                    @else
                        <p class="font-bold text-blue">CAMBIO TAGLIA</p>
                    @endif
                </x-dashboard.form.panel>
            @endforeach
        @else
            <x-dashboard.form.panel>
                <p class="py-8">
                    Nessun ordine registrato dopo il <?= (new DateTime($date))->format('d/m/Y') ?>
                </p>
            </x-dashboard.form.panel>
        @endif
    </div>
</x-layouts.admin-dashboard>
