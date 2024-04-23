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
            // Copy from cart set in the header
            document.querySelector('.js-copy-orders').addEventListener('click', async function() {
                cartId = document.querySelector('.js-first-cart').value

                copyOrdersContent(cartId)
            })

            // Copy from specific cart
            document.querySelectorAll('.js-copy-from-cart').forEach((button) => {
                button.addEventListener('click', async function() {
                    cartId = this.getAttribute('data-id')

                    copyOrdersContent(cartId)
                })
            })

            // Copy the orders content
            function copyOrdersContent(startingCartId) {
                let orders = []
                let order = document.querySelector(`.js-order[data-id="${startingCartId}"]`);

                orders.push(order);

                while (nextOrder = selectNextOrder(order)) {
                    order = nextOrder;

                    if (order) {
                        orders.push(order);
                    }
                }

                // Get the HTML content of the entire document
                let text = '';

                orders.forEach((order) => {
                    order.querySelectorAll('.js-to-copy').forEach((element) => {
                        text += element.outerHTML;
                    })

                    text += '--------<br><br>';
                })

                copyRichTextToClipboard(text)
            }

            function selectNextOrder(order) {
                const nextSibling = order.closest('article').nextElementSibling

                if (nextSibling) {
                    return nextSibling.querySelector('.js-order');
                }

                return null;
            }

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
            <div class="grid gap-4">
                <x-form.group>
                    <x-inputs.label>Copia a partire da:</x-inputs.label>
                    <x-inputs.input :value="$carts->first()->id" class="js-first-cart" placeholder="A partire da" data-size="3" />
                    <x-inputs.button class="grow js-copy-orders">
                        {!! embed_icon('copy') !!}
                        Copia il log
                    </x-inputs.button>
                </x-form.group>

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

                <x-dashboard.form.panel :title="$title" id="carts" class="order log js-order"
                    data-id="{{ $cart->id }}">

                    {{-- Order header --}}
                    <p class="js-to-copy">[{{ $cart->id }} - {{ date('Y-m-d H:i:s', strtotime($cart->time)) }}]</p>

                    {{-- Address --}}
                    @if ($cart->address)
                        {{-- If found --}}
                        <div class="js-to-copy">
                            <p>{!! $cart->address->fullAddress() !!}</p>
                            ---
                        </div>
                        <div>
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
                    <div class="js-to-copy">
                        @foreach ($cart->orders as $order)
                            <p>
                                @if ($order->inventory == 1)
                                    <span class="font-bold text-green">! IN INVENTARIO !</span>
                                @endif

                                @if ($order->inventory == 2)
                                    <span class="font-bold text-dark-green">! SIMILE IN INVENTARIO !</span>
                                @endif

                                <a href="{{ $order->product->fullUrl($order->color_id, $order->position_id) }}"
                                    target="_blank">
                                    {{ $order->fullName() }}
                                </a>
                            </p>
                        @endforeach
                    </div>

                    {{-- Shipping --}}
                    <p class="js-to-copy">{!! $cart->shipping_method->name !!}, {!! $cart->shipping_method->deliveryDateString($cart->created_at, $cart->hasLongDelivery()) !!}</p>

                    {{-- Price --}}
                    @if (!$cart->change_request)
                        <p class="js-to-copy">Totale: <strong>{!! $cart->priceString() !!}</strong></p>
                    @else
                        <p class="font-bold text-blue js-to-copy">CAMBIO TAGLIA</p>
                    @endif

                    <x-inputs.button class="js-copy-from-cart" data-id="{{ $cart->id }}" size="small"
                        variant="secondary">
                        {!! embed_icon('copy') !!}
                        Copia a partire da qui
                    </x-inputs.button>
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
