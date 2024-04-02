@php
    use App\Models\Ecommerce\Cart;
    use App\Models\Ecommerce\Shop;
    use App\Models\Ecommerce\Shop\Payment;

    // accept seller entry
    if (isset($_POST['accept-seller'])) {
        (new Shop($_POST['accept-seller']))->accept();
    }

    // delete seller entry
    if (isset($_POST['delete-seller'])) {
        $seller = Shop::find($_POST['delete-seller']);
        $seller->deleteAndClean();
    }

    // select pending lables
    $lblN = Cart::unlabelled()->count();

    // select shops awaiting for approval
    $sellers = Shop::awaitingApproval();

    // select balace
    $balance['gained'] = Shop::sum('total');
    $balance['payed'] = Payment::where('other', 0)->sum('value');
    $balance['extra'] = Payment::where('other', 1)->sum('value');

    $balance['tot'] = $balance['gained'] + $balance['extra'];
    $balance['topay'] = $balance['tot'] - $balance['payed'];
@endphp

@pushOnce('styles')
    <style>
        .panel:has(.panel__counter) {
            text-align: center;
        }

        .panel__body:has(.panel__counter) {
            display: grid;
            align-items: center;
        }

        .panel__counter {
            color: var(--color-primary-400);
            font-size: var(--fs-850);
            font-weight: var(--fw-bold);
            text-align: center;
        }

        .seller-item {
            padding: 0.75rem;
            border: var(--border);
            border-radius: var(--border-radius-400);
            border-style: dashed;
        }

        .seller-item__title {
            color: var(--fc-700);
            font-family: var(--ff-titles);
        }

        .seller-item__button {
            background: none;
            border: none;
            color: var(--color-primary-400);
            font-family: var(--ff-titles);
            cursor: pointer;
        }

        .seller-item_button--green {
            color: var(--clr-good-400);
        }

        .seller-item_button--red {
            color: var(--clr-bad-400);
        }
    </style>
@endpushOnce

<x-templates.admin>

    <div class="grid-flow" data-align='top' data-columns="30rem" data-spacing="700">

        <!-- Labels count -->
        <section class="panel">
            <div class="panel__header">
                <h3 class="panel__title">Etichette da preparare</h3>
            </div>

            <div class="panel__body">
                <p class="panel__counter"><?= $lblN ?></p>
            </div>

            <div class="panel__footer">
                <a href="<?= route('admin.shipping.courier') ?>" class="button">Genera le etichette</a>
            </div>
        </section>

        <!-- Pending sellers -->
        <form class="panel" method="POST">
            <div class="panel__header">
                <h3 class="panel__title">Negozi da approvare</h3>
            </div>

            <div class="panel__body grid-flow" data-spacing="200">

                @if (count($sellers))
                    @foreach ($sellers as $seller)
                        <div class="seller-item grid-flow" data-spacing="200">
                            <p class="seller-item__title"><?= $seller->name ?></p>

                            <div class="flex-group" data-column-spacing="400">
                                <a href="<?= $seller->clientUrl ?>" class="seller-item__button">Visualizza</a>
                                <button type="submit" class="seller-item__button seller-item_button--green"
                                    name="accept-seller" value="<?= $seller->id ?>">Accetta</button>
                                <button type="submit" class="seller-item__button seller-item_button--red"
                                    name="delete-seller" value="<?= $seller->id ?>"
                                    onclick="return confirm('Sei sicuro di voler eliminare il negozio?')">Elimina</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty">
                        <p class="empty">Nessun negozio da approvare</p>
                    </div>

                @endif

            </div>
        </form>
    </div>

</x-templates.admin>
