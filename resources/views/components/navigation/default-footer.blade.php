@php

    // elements
    $footer['logo'] = false;
    $footer['contacts'] = true;

    // links
    $footer['columns'] = [
        ['Esplora', [['Home', 'home'], ['Prodotti', 'shop'], ['Vendi', 'sell']]],

        [
            'Supporto',
            [
                ['Contatti', 'contacts'],
                ['FAQ', 'infos.faq'],
                ['Cambio taglia', 'refund'],
                ['Stato ordine', 'order/status'],
            ],
        ],

        [
            'Legali',
            [
                ['Condizioni di vendita', 'infos.sale-terms'],
                ['Condizioni di invio', 'infos.contract'],
                ['Privacy policy', 'infos.privacy'],
                ['P. IVA ' . config('custom.ecommerce.vat')],
            ],
        ],
    ];

    // contacts
    $footer['contacts'] = ['instagram', 'facebook', 'email'];

@endphp

@push('styles')
    <style>
        /* Structure */
        footer {
            background-color: var(--surface-tertiary);
        }

        html[data-color-scheme='light'] footer {
            color: var(--text-color-strong);
        }

        /* Links */
        #footer-body {
            display: flex;
            grid-template-columns: repeat(auto-fit, minmax(9rem, 1fr));
            padding-block: 4rem 2rem;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: space-between;
        }

        .footer-title {
            padding-bottom: 1rem;
            font-weight: 600;
            font-size: 1.2rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        footer .links {
            display: grid;
            gap: 5px;
        }

        .footer-link {
            transition: color 0.2s;
        }

        html[data-color-scheme='dark'] .footer-link {
            color: var(--text-color-fade);
        }

        .footer-link.active a:hover {
            color: #fff;
        }

        .contacts ul {
            gap: 5px;
        }

        .footer-soc-icon {
            background-color: var(--surface-secondary);
            border-radius: 5px;
            width: 2.5rem;
            height: 2.5rem;
            transition: background-color 0.4s;
        }

        .footer-soc-icon .icon-box {
            width: 50%;
            aspect-ratio: 1;
        }

        .footer-soc-icon svg {
            fill: var(--text-color);
        }

        .footer-soc-icon:hover svg {
            fill: #fff;
        }

        @media (max-width: 768px) {
            #footer-body {
                display: grid;
            }
        }

        /* Bottom notes */
        #footer-notes {
            border-top: 1px solid var(--surface-secondary);
            padding-block: 2rem;
            color: var(--text-color-fade-more);
        }

        #footer-notes a {
            transition: color 0.2s;
        }
    </style>
@endpush

<footer>

    <div id="footer-wrap" class="main-wrap">

        <div id="footer-body" class="">

            <?php foreach ($footer['columns'] as $f_column) { ?>

            <div class="footer-col">

                <h3 class="footer-title"><?= $f_column[0] ?></h3>

                <ul class="links">

                    <?php foreach ($f_column[1] as $f_link) { ?>

                    <li>

                        <?php if (isset($f_link[1])) { ?>

                        <p class="footer-link active">
                            <a href="<?= $f_link[1] ?>"><?= $f_link[0] ?></a>
                        </p>

                        <?php } else { ?>

                        <p class="footer-link"><?= $f_link[0] ?></p>

                        <?php } ?>

                    </li>

                    <?php } ?>

                </ul>

            </div>

            <?php } ?>

            <?php if ($footer['contacts']) { ?>

            <div class="contacts footer-col">

                <h3 class="footer-title">Contatti</h3>

                <ul class="flex">

                    <?php if (in_array('instagram', $footer['contacts'])) { ?>
                    <a href="<?= config('custom.social_media.instagram.url') ?>"
                        class="footer-soc-icon bgr-c1-h center-items">
                        <div class="icon-box">
                            <?= embed_icon('contacts/instagram') ?>
                        </div>
                    </a>
                    <?php } ?>

                    <?php if (in_array('facebook', $footer['contacts'])) { ?>
                    <a href="<?= config('custom.social_media.facebook.url') ?>"
                        class="footer-soc-icon bgr-c1-h center-items">
                        <div class="icon-box">
                            <?= embed_icon('contacts/facebook') ?>
                        </div>
                    </a>
                    <?php } ?>

                    <?php if (in_array('email', $footer['contacts'])) { ?>
                    <a href="<?= config('custom.social_media.email.url') ?>"
                        class="footer-soc-icon bgr-c1-h center-items">
                        <div class="icon-box">
                            <?= embed_icon('contacts/email') ?>
                        </div>
                    </a>
                    <?php } ?>

                </ul>

            </div>

            <?php } ?>

        </div>

        <p id="footer-notes">
            Copyright © <?= getdate()['year'] ?> <span
                class="txt-wht"><?= config('custom.meta_data.full_name') ?></span>. Il
            copyright © di ogni design appartiene al rispettivo proprietario.
            <br>Sito web realizzato da <a class="txt-wht-h" href="emailto:ismaele.tuffanelli@gmail.com">Ismaele
                Tuffanelli</a>
        </p>
    </div>
</footer>
