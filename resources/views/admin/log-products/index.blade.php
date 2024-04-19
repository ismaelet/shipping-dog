@pushOnce('styles')
    <style>
        .log {
            font-family: Courier New;

            h3 {
                font-family: inherit;
            }
        }
    </style>
@endpushOnce

@pushOnce('scripts')
    <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.js-copy-orders').addEventListener('click', async function() {
                // Get the HTML content of the entire document
                const designText = '------ DESIGN ------' + document.querySelector('#designs')
                    .outerHTML + '<br><br><br>'
                const productsText = '------ PRODOTTI ------' + document.querySelector('#products')
                    .outerHTML

                copyRichTextToClipboard(designText + productsText)
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
        <x-dashboard.typography.main-heading>Log dei prodotti</x-dashboard.typography.main-heading>

        {{-- Recap form --}}
        <x-dashboard.form.panel :title="($validCarts->count() ? $filteredOrders->count() : 0) . ' Articoli da fare'">
            <x-inputs.label>Parti dall'ordine:</x-inputs.label>

            <div class="flex gap-4 items-center">
                <form action="" method="POST" class="w-full">
                    <x-inputs.search-input search="carts" stored-name="start_cart_id"
                        stored-value="{{ $startFromCart ? $startFromCart->id : null }}"
                        shown-value="{{ $startFromCart ? $startFromCart->id . ' - ' . $startFromCart->address->full_name : '' }}"
                        placeholder="Ordine da cui partire" required />
                </form>

                <form action="" method="POST">
                    <x-inputs.button type="submit" name="submit" value="clean_log" variant="secondary" size="auto">
                        <?= embed_icon('clean') ?>
                        Pulisci log
                    </x-inputs.button>
                </form>
            </div>

            <x-inputs.button class="js-copy-orders">
                <?= embed_icon('copy') ?>Copia tutto
            </x-inputs.button>
        </x-dashboard.form.panel>

        {{-- Logs --}}
        <div class="grid lg:grid-cols-2 gap-8">
            {{-- Designs --}}
            <x-dashboard.form.panel title="Design" id="designs" class="log gap-8">

                @if ($validCarts->count())
                    @php
                        $sortedBySellers = $filteredOrders->sortBy('seller_id')->unique('seller_id');
                    @endphp

                    @foreach ($sortedBySellers as $sellerOrder)
                        {{-- Sellers --}}
                        <div class="seller">
                            <h3 class="text-primary">[ {{ strtoupper($sellerOrder->storeName) }} ]</h3>

                            @php
                                // Get the orders
                                $sortedByProducts = $filteredOrders
                                    ->where('seller_id', '=', $sellerOrder->seller_id)
                                    ->sortBy('design_id')
                                    ->unique('design_id');
                            @endphp

                            <div class="designs grid gap-4">
                                @foreach ($sortedByProducts as $product)
                                    {{-- Designs --}}
                                    <div class="designs">
                                        <p class="text-neutral-700">
                                            {{ ($product->design_id ? $product->design_id . ' - ' : '') . $product->productName }}
                                        </p>

                                        @php
                                            $mergedOrders = [];
                                            // Get the orders
                                            $orders = $filteredOrders
                                                ->where('design_id', '=', $product->design_id)
                                                ->sortBy('size_id')
                                                ->sortBy('color_id')
                                                ->sortBy('type_id');
                                        @endphp

                                        @foreach ($orders as $order)
                                            @php
                                                $orderDescription = $order->description();
                                                $orderDescription = $order->inventory
                                                    ? $orderDescription . ' <strong>(INVENTARIO)</strong>'
                                                    : $orderDescription;

                                                if (isset($mergedOrders[$orderDescription])) {
                                                    $mergedOrders[$orderDescription] += $order->quantity;
                                                } else {
                                                    $mergedOrders[$orderDescription] = 1;
                                                }
                                            @endphp
                                        @endforeach

                                        @foreach ($mergedOrders as $orderDescription => $quantity)
                                            <p>{!! $quantity . ' x ' . $orderDescription !!}</p>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="py-12">Nessun design da stampare</p>
                @endif
            </x-dashboard.form.panel>

            {{-- Products --}}
            <x-dashboard.form.panel title="Prodotti" id="products" class="log gap-8">

                @if ($validCarts->count())
                    @php
                        $sortedByTypes = $filteredOrders->sortBy('type_id')->unique('type_id');
                    @endphp

                    @foreach ($sortedByTypes as $typeOrder)
                        {{-- Product type --}}
                        <div class="product-type">
                            <h3 class="text-primary">[ {{ strtoupper($typeOrder->productTypeName) }} ]</h3>

                            @php
                                $mergedOrders = [];

                                // Get the orders
                                $sortedByData = $filteredOrders
                                    ->where('inventory', 0)
                                    ->where('type_id', '=', $typeOrder->type_id)
                                    ->sortBy('color_id')
                                    ->sortBy('size_id');
                            @endphp

                            @foreach ($sortedByData as $order)
                                {{-- Order --}}
                                @php
                                    $orderDescription = [];
                                    $orderDescription[] =
                                        $order->size && strpos($order->product->type->sizes, ',')
                                            ? $order->size->name
                                            : null;
                                    $orderDescription[] =
                                        $order->color && strpos($order->product->type->colors, ',')
                                            ? $order->color->name
                                            : null;
                                    $orderDescription =
                                        implode(' ', array_filter($orderDescription)) ?: 'Prodotto standard';

                                    if (isset($mergedOrders[$orderDescription])) {
                                        $mergedOrders[$orderDescription] += $order->quantity;
                                    } else {
                                        $mergedOrders[$orderDescription] = 1;
                                    }
                                @endphp
                            @endforeach

                            @foreach ($mergedOrders as $orderDescription => $quantity)
                                <p>{{ $quantity . ' x ' . $orderDescription }}</p>
                            @endforeach
                        </div>
                    @endforeach
                @else
                    <p class="py-12">Nessun prodotto da stampare</p>
                @endif

            </x-dashboard.form.panel>
        </div>
    </div>
</x-layouts.admin-dashboard>
