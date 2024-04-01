@php

    global $user;

    // elements
    $header = [];

    $header['logo'] = false;
    $header['name-bar'] = false;
    $header['srch-bar'] = true;
    $header['lang-bar'] = true;
    $header['login'] = true;
    $header['force-mob'] = false;
    $header['privacy'] = true;
    $header['wishlist'] = true;
    $header['light-mode-switcher'] = true;

    // theme
    $header['floating'] = false;

    // name
    $header['name'] = 'Shi<span class="txt-c1">pp</span>ing Dog';
    $header['name-mob'] = 'Shi<span class="txt-c1">pp</span>ing Dog';

    // links
    $header['links'] = [];

    $header['links'][] = ['title' => 'Home', 'loc' => 'home', 'url' => route('home')];
    $header['links'][] = [
        'title' => 'Carrello (' . cart_count() . ')',
        'loc' => 'cart',
        'url' => route('cart'),
        'cart' => true,
        'mob' => true,
    ];
    $header['links'][] = [
        'title' => 'Login',
        'loc' => 'login',
        'url' => route('login'),
        'logged' => false,
        'mob' => true,
    ];
    $header['links'][] = [
        'title' => 'Mio negozio',
        'loc' => 'artist',
        'url' => route('artist', ['id' => auth()->id()]),
        'drop' => 'artist',
        'seller' => true,
    ];
    $header['links'][] = [
        'title' => 'Profilo',
        'loc' => 'profile',
        'url' => route('profile'),
        'logged' => true,
        'mob' => true,
    ];
    $header['links'][] = ['title' => 'Prodotti', 'loc' => 'shop', 'url' => route('shop'), 'drop' => 'shop'];
    // $header['links'][] = ['title' => 'Promozioni', 'loc' => 'promotions', 'url' => route('promotions'), 'new' => true];
    $header['links'][] = [
        'title' => 'Preferiti',
        'loc' => 'wishlist',
        'url' => route('wishlist'),
        'logged' => true,
        'mob' => true,
    ];
    $header['links'][] = ['title' => 'Vendi', 'loc' => 'sell', 'url' => route('sell'), 'seller' => false];
    $header['links'][] = ['title' => 'Supporto', 'loc' => 'info', 'url' => route('infos.faq'), 'drop' => 'info'];
    $header['links'][] = ['title' => 'Gestione', 'loc' => 'admin', 'url' => route('admin'), 'admin' => true];

    // dropdowns
    $header['dropdowns']['shop'] = [
        ['Abbigliamento Donna', route('shop', ['gender' => 'f'])],
        ['Abbigliamento Uomo', route('shop', ['gender' => 'm'])],
        ['Accessori', route('shop', ['group' => 'm'])],
        ['Ufficiali', route('artist', ['id' => '1'])],
    ];

    $header['dropdowns']['info'] = [
        ['Contatti', route('contacts')],
        ['Cambio taglia o reso', route('refund')],
        ['Stato ordine', route('order/status')],
        ['Domande frequenti', route('infos.faq')],
    ];

    $header['dropdowns']['artist'] = [
        ['Il mio negozio', route('artist', ['id' => auth()->id()])],
        ['Le mie grafiche', route('manage/portfolio')],
        ['Carica una grafica', route('manage/portfolio/add')],
        ['Vendite realizzate', route('manage/sales/list')],
        ['Modifica prezzi', route('manage/sales/prices')],
        ['Gestisci', route('manage')],
    ];

    // login bar
    $header['login-link'] = ['Login', route('login')];
    $header['logged-link'] = [auth()->user() ? auth()->user()->username : '', route('profile')];

@endphp

@push('styles')
    <style>
        /* Structure */
        header {
            --h-bgr-color: var(--bgr);
            --h-text-color: var(--text-color-strong);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            width: 100%;
            height: 80px;
            position: relative;
            top: 0;
            left: 0;
            z-index: 100;
        }

        [data-color-scheme='dark'] header {
            --h-bgr-color: var(--surface);
        }

        header.floating {
            position: absolute;
        }

        #h-pan {
            width: 100%;
            padding-inline: 2rem;
            top: 0;
            left: 0;
        }

        #h-pan.fixed,
        #h-pan.h-open {
            position: fixed;
        }

        #h-bgr {
            opacity: 1;
            background-color: var(--h-bgr-color);
            transition: opacity 0.4s;
        }

        .floating #h-bgr {
            background-color: rgba(255, 255, 255, 0.6);
            -webkit-backdrop-filter: blur(7px);
            backdrop-filter: blur(7px);
        }

        [data-color-scheme='dark'] .floating #h-bgr {
            background-color: rgba(0, 0, 0, 0.3);
        }

        #h-pan.h-open #h-bgr,
        #h-pan.fixed #h-bgr {
            opacity: 1;
        }

        #h-bars {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 0 max-content;
            align-items: center;
            position: relative;
        }

        #h-nav-rig {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        /* Logo */
        #h-logo {
            display: inline-block;
            background-image: url(/app/assets/logo/logo_wht.png);
            background-size: contain;
            background-position: center;
            width: 5rem;
            height: 5rem;
        }

        /* Name and searchbar */
        #h-srch .h-name {
            color: var(--h-text-color);
            font-size: 22px;
            letter-spacing: 0.1em;
            white-space: nowrap;
            transition: color 0.4s;

            @media (max-width: 480px) {
                display: none;
            }
        }

        #h-srch {
            display: flex;
            width: max-content;
            flex-shrink: 0;
            margin-left: -1rem;
            position: relative;
            align-items: center;
            font-size: 0;
            overflow: hidden;
        }

        #h-srch .h-name-box {
            max-width: 100%;
            overflow: hidden;
            margin-left: 0.5rem;
            position: relative;
            top: 1px;
            transition: max-width 0.1s 0.3s;
        }

        #h-srch-ico-box {
            border: 1px solid transparent;
            width: 45px;
            height: 45px;
            transition: border 0.2s;
            cursor: pointer;
        }

        #h-srch-ico-box .background {
            opacity: 0;
            transition: opacity 0.4s;
        }

        #h-srch .icon-box {
            width: 50%;
            height: 50%;
            z-index: 1;
        }

        #h-srch .icon-box svg {
            fill: var(--h-text-color);
        }

        #h-srch-inp-box {
            width: 0;
            overflow: hidden;
            transition: width 0.4s;
            vertical-align: middle;
        }

        #h-srch-inp {
            width: 100%;
            border: 1px solid var(--surface);
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            padding: 0 1rem;
            height: 45px;
            line-height: 45px;
        }

        [data-color-scheme='dark'] #h-srch-inp {
            border: none;
        }

        #h-srch-cbx:checked~#h-srch-ico-box {
            border-color: var(--clr-1);
        }

        #h-srch-cbx:checked~#h-srch-ico-box .background {
            opacity: 1;
        }

        #h-srch-cbx:checked~#h-srch-ico-box svg {
            fill: #fff;
        }

        #h-srch-cbx:checked~#h-srch-inp-box {
            width: 300px;
        }

        #h-srch-cbx:checked~.h-name-box {
            max-width: 0;
            transition: max-width 0.1s;
        }

        /* Desktop links */
        .h-link {
            position: relative;
        }

        .h-link-txt {
            font-weight: 600;
            line-height: 80px;
            position: relative;
        }

        .h-link__new-icon {
            --icon-size: 1.2rem;
            color: red;
            position: absolute;
            top: 50%;
            right: 0;
            translate: 70% -100%;
        }

        [data-color-scheme='dark'] .h-link-txt {
            font-weight: 500;
        }

        .h-link-txt a {
            display: block;
            color: var(--h-text-color);
            transition: color 0.1s;
        }

        .h-link-txt:hover a,
        .h-link-txt.active a {
            color: var(--clr-1);
        }

        .h-drop-ico {
            font-size: 0.8em;
            vertical-align: top;
        }

        /* Desktop dropdown menu */
        .h-drop {
            background-color: var(--clr-1);
            width: 230px;
            max-height: 0;
            position: absolute;
            top: 100%;
            left: calc(50% - 115px);
            overflow: hidden;
            transition: max-height 0.1s ease-out;
            z-index: 1;
        }

        .h-link:hover .h-drop,
        #nav_prof:hover .h-drop {
            max-height: 300px;
            transition: max-height 0.2s ease-in;
        }

        .h-drop-link {
            display: block;
            color: #fff;
            font-size: var(--font-size-300);
            line-height: 1;
            text-align: left;
            transition: background 0.2s;
        }

        .h-drop-link a {
            display: block;
            padding: 1em;
            transition: color 0.2s;
        }

        /* Wishlist & Cart */
        header:has(#h-srch-cbx:checked) .h-icon {
            visibility: hidden;
            pointer-events: none;
        }

        #h-cart {
            display: block;
            background-position: center;
            background-size: contain;
            width: 20px;
            height: 20px;
            padding: 0;
            position: relative;
        }

        #h-cart svg,
        #h-wish svg {
            fill: var(--h-text-color);
        }

        #h-cart:hover svg,
        #h-wish:hover svg {
            fill: var(--clr-1);
        }

        #h-cart-num {
            background: #e00003;
            border-radius: 100%;
            width: 14px;
            position: absolute;
            bottom: 0.9rem;
            left: 0.9rem;
            color: #fff;
            font-size: 0.8rem;
            line-height: 14px;
            text-align: center;
        }

        #h-wish {
            background-position: center;
            background-size: contain;
            width: 20px;
            height: 20px;
            padding: 0;
        }

        /* Theme switch */
        .theme-switch .icon-box {
            display: flex;
            width: 1.5rem;
            aspect-ratio: 1;
            cursor: pointer;
            justify-content: center;
        }

        .theme-switch svg {
            fill: var(--h-text-color);
        }

        .theme-switch .icon-box:hover svg {
            fill: var(--clr-1);
        }

        .theme-switch .dark-icon svg {
            width: 75%;
        }

        html[data-color-scheme='light'] .theme-switch .light-icon {
            display: none;
        }

        html[data-color-scheme='dark'] .theme-switch .dark-icon {
            display: none;
        }

        /* Login & profile */
        #h-user-ico {
            display: block;
            background-color: #ddd;
            background-position: center;
            background-size: cover;
            border: 2px solid #8b8b8b;
            width: 40px;
            height: 40px;
            transition: border 0.1s;
        }

        .h-log-txt {
            display: inline-block;
            color: var(--h-text-color);
            transition: color 0.4s, background 0.4s, border 0.4s;
        }

        .h-log-txt.main {
            padding: 0 1.7rem;
            border-width: 2px;
            border-style: solid;
            border-radius: 10em;
            font-weight: 600;
            text-transform: lowercase;
            line-height: calc(3rem - 2px);
            transition: color 0.2s, background 0.2s, border 0.2s;
        }

        .h-log-txt.main:hover {
            background-color: var(--text-color-strong);
            border-color: var(--text-color-strong);
            color: var(--bgr);
        }

        [data-color-scheme='dark'] .h-log-txt.main {
            font-weight: 500;
        }

        /* Mobile menu icon */
        #h-mob-pan {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        #h-icoM-box {
            display: inline-block;
            width: 1.5rem;
            height: 65px;
            position: relative;
            vertical-align: middle;
            cursor: pointer;
        }

        .h-icoM-line {
            width: 100%;
            height: 2px;
            position: absolute;
            top: calc(50% - 2px);
            right: 0;
            transition: transform 0.4s;
        }

        .h-icoM-rot {
            background-color: var(--h-text-color);
            width: 100%;
            height: 100%;
            transform: rotate(0deg);
            transition: transform 0.4s;
        }

        #h-icoM-line1 {
            transform: translateY(-10px);
            transition: transform 0.4s 0.4s;
        }

        #h-icoM-line3 {
            transform: translateY(10px);
            transition: transform 0.4s 0.4s;
        }

        #h-cbxM:checked+#h-icoM-box #h-icoM-line1,
        #h-cbxM:checked+#h-icoM-box #h-icoM-line3 {
            transform: translateY(0);
            transition: transform 0.4s;
        }

        #h-cbxM:checked+#h-icoM-box #h-icoM-line1 .h-icoM-rot {
            transform: rotate(45deg);
            transition: transform 0.4s 0.4s;
        }

        #h-cbxM:checked+#h-icoM-box #h-icoM-line3 .h-icoM-rot {
            transform: rotate(-45deg);
            transition: transform 0.4s 0.4s;
        }

        #h-icoM-line2 {
            opacity: 1;
            transition: opacity 0.1s 0.4s, transform 0.2s 0.4s;
        }

        #h-cbxM:checked+#h-icoM-box #h-icoM-line2 {
            opacity: 0;
            transform: translateX(-4rem);
            transition: opacity 0.1s 0.1s, transform 0.2s;
        }

        /* Mobile menu */
        #h-navM {
            background-color: var(--h-bgr-color);
            width: 100%;
            height: 100%;
            overflow: hidden;
            overflow-y: auto;
            position: fixed;
            top: 65px;
            left: -100%;
            z-index: 1;
            transition: left 0.4s;
        }

        #h-cbxM:checked~#h-navM {
            left: 0;
        }

        #h-contM {
            padding: 2rem 2rem 0 2rem;
            position: relative;
        }

        .h-linkM-txt {
            opacity: 0;
            padding-left: 20px;
            transform: translateX(-4rem);
            font-size: 0.9rem;
            letter-spacing: 0.1em;
            line-height: 5.5;
            cursor: pointer;
            transition: opacity 0.4s 0.5s, transform 0.4s 0.4s, color 0.5s;
        }

        #h-cbxM:checked~#h-navM .h-linkM-txt {
            opacity: 1;
            transform: translateX(0);
        }

        .h-linkM-bgr {
            opacity: 0;
            transition: opacity 0.4s;
            z-index: -1;
        }

        .h-lineM {
            height: 1px;
            margin: 1rem 0;
        }

        /* Mobile dropdown */
        .h-dropM-cbx:checked+a .h-linkM-txt {
            color: #fff !important;
        }

        .h-dropM-cbx:checked+a .h-linkM-bgr {
            opacity: 1;
        }

        .h-dropM-link {
            opacity: 0;
            display: block;
            border: 0px solid var(--surface-secondary);
            height: 0;
            padding-left: 20px;
            font-size: 0.9rem;
            letter-spacing: 0.1em;
            line-height: 4rem;
            overflow: hidden;
            transition: opacity 0.4s, border 0.4s, height 0.4s, background 0.4s, color 0.2s;
        }

        .h-dropM-cbx:checked~.h-dropM .h-dropM-link {
            opacity: 1;
            border-width: 0 0 1px 0;
            height: 4rem;
        }

        .h-dropM-link a {
            display: block;
        }

        /* Responsive */
        @media (max-width: 1280px) {
            header {
                height: 65px;
            }

            #h-pan {
                padding: 0 3rem;
            }

            #h-logo {
                width: 4rem;
                height: 4rem;
            }
        }

        @media (max-width: 576px) {
            #h-pan {
                padding-inline: 1rem;
            }

            #h-contM {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            #h-srch-cbx:checked~#h-srch-inp-box {
                width: calc(100vw - 96px);
            }

            .h-name {
                font-size: 1.4rem;
                letter-spacing: 0;
            }
        }
    </style>
@endpush

<header class="<?= $header['floating'] ? 'floating' : '' ?>">

    <div id="h-pan">

        <div id="h-bgr" class="bgr"></div>

        <div id="h-bars">

            <!-- Left bar -->
            <div id="h-nav-lef" class="h-bar inlb txt-lef al-mid">

                <!-- Logo -->
                @if ($header['logo'])
                    <a href="<?= route('home') ?>" id="h-logo" <?= background_image(LOGO_DIR . 'logo.png') ?>></a>
                @endif

                <!-- Search bar -->
                @if ($header['srch-bar'])
                    <form action="<?= route('search') ?>" method="GET" id="h-srch" class="inlb"
                        onClick="$('#h-srch-inp').focus();">

                        <input type="checkbox" id="h-srch-cbx" class="hidden">

                        <label for="h-srch-cbx" id="h-srch-ico-box" class="stack-items">

                            <div class="background fill-all bgr-c1"></div>

                            <div class="icon-box">
                                <?= icon_embed('search') ?>
                            </div>

                        </label>

                        <div id="h-srch-inp-box">
                            <input type="text" id="h-srch-inp" class="input" name="search"
                                placeholder="Cerca un prodotto o tag">
                        </div>

                        <div class="h-name-box inlb al-mid">

                            <h3 class="h-name txt-upp desk1280">
                                <a href="<?= route('home') ?>"><?= $header['name'] ?></a>
                            </h3>

                            <h3 class="h-name txt-upp mob1280">
                                <a href="<?= route('home') ?>"><?= $header['name-mob'] ?></a>
                            </h3>

                        </div>

                    </form>
                @endif

                <!-- Name -->
                @if ($header['name-bar'])
                    <div clas="h-name-box" class="inlb">

                        <h3 class="h-name txt-upp desk1280">
                            <a href="<?= route('home') ?>"><?= $header['name'] ?></a>
                        </h3>

                        <h3 class="h-name txt-upp mob1280">
                            <a href="<?= route('home') ?>"><?= $header['name-mob'] ?></a>
                        </h3>

                    </div>
                @endif

            </div>

            <!-- Central bar -->
            <div id="h-nav-cen" class="h-bar inlb txt-cen al-mid"></div>

            <!-- Right bar -->
            <div id="h-nav-rig" class="h-bar flex gap-6 lg:gap-12">

                <!-- Links -->
                @if (!$header['force-mob'])

                    <div class="h-nav-box desk1280 flex gap-12">

                        @foreach ($header['links'] as $h_link)
                            @php

                                $h_valid = true;

                                // Check logged filter
                                if (isset($h_link['logged'])) {
                                    if ($h_link['logged'] && !logged()) {
                                        $h_valid = false;
                                    } elseif (!$h_link['logged'] && logged()) {
                                        $h_valid = false;
                                    }
                                }

                                // Check seller filter
                                if (isset($h_link['seller'])) {
                                    if ($h_link['seller'] && (!auth()->user() || !auth()->user()->is_seller())) {
                                        $h_valid = false;
                                    } elseif (!$h_link['seller'] && auth()->user() && auth()->user()->is_seller()) {
                                        $h_valid = false;
                                    }
                                }

                                // Check admin filter
                                if (isset($h_link['admin']) && !admin()) {
                                    $h_valid = false;
                                }

                                // Check cart filter
                                if (isset($h_link['cart']) && !cart_count()) {
                                    $h_valid = false;
                                }

                                // Check mobile filter
                                if (isset($h_link['mob'])) {
                                    $h_valid = false;
                                }

                            @endphp

                            @if ($h_valid)
                                <div class="h-link">
                                    <p
                                        class="h-link-txt <?= isset($h_link['loc']) && isset($locationTag) && $locationTag == $h_link['loc'] ? 'active' : '' ?>">
                                        <a href="<?= $h_link['url'] ?>">
                                            <?= $h_link['title'] ?>

                                            @if (isset($h_link['drop']))
                                                <span class="h-drop-ico">&#x25BC;</span>
                                            @endif

                                            @if (!empty($h_link['new']))
                                                <span class="h-link__new-icon">
                                                    <?= icon_embed('discount') ?>
                                                </span>
                                            @endif
                                        </a>
                                    </p>

                                    @if (isset($h_link['drop']))
                                        <div class="h-drop bgr-c1">

                                            @foreach ($header['dropdowns'][$h_link['drop']] as $h_drop_link)
                                                <p class="h-drop-link bgr-wht-h txt-wht txt-blk-h"><a
                                                        href="<?= $h_drop_link[1] ?>"><?= $h_drop_link[0] ?></a></p>
                                            @endforeach

                                        </div>
                                    @endif

                                </div>
                            @endif
                        @endforeach

                    </div>

                @endif

                <!-- Navigation icons -->
                <div class="h-navigation-icons flex gap-6 items-center">

                    @if ($header['light-mode-switcher'])
                        <!-- Theme switch -->
                        <div class="theme-switch h-icon">
                            <div class="light-icon icon-box js-color-scheme-switcher" data-color-scheme="light">
                                <?= icon_embed('sun') ?>
                            </div>

                            <div class="dark-icon icon-box js-color-scheme-switcher" data-color-scheme="dark">
                                <?= icon_embed('moon') ?>
                            </div>
                        </div>
                    @endif

                    @if ($header['wishlist'])
                        <!-- Favorites icon -->
                        <a id="h-wish" class="wish icon-box h-link al-mid h-icon" href="<?= route('wishlist') ?>">
                            <?= icon_embed('love') ?>
                        </a>
                    @endif

                    <!-- Cart icon -->
                    <a id="h-cart" class="bag icon-box h-link al-mid h-icon" href="<?= route('cart') ?>">

                        <?= icon_embed('bag') ?>

                        @if (cart_count())
                            <h1 id="h-cart-num"><?= cart_count() ?></h1>
                        @endif

                    </a>
                </div>

                <!-- Login bar -->
                @if ($header['login'])

                    <div id="h-log-box" class="<?= $header['force-mob'] ? 'hidden' : 'desk1280' ?>">

                        @if (auth()->id())
                            <a href="<?= $header['logged-link'][1] ?>">
                                <p class="h-log-txt br-c1-h txt-upp main"><?= $header['logged-link'][0] ?></p>
                            </a>
                        @else
                            <a href="<?= $header['login-link'][1] ?>">
                                <p class="h-log-txt br-c1-h txt-upp main"><?= $header['login-link'][0] ?></p>
                            </a>
                        @endif

                    </div>

                @endif

                <!-- Mobile menu -->
                <div id="h-mob-pan"
                    class="txt-rig @php if (!$header['force-mob']) {
                    echo 'mob1280';
                } @endphp">

                    <!-- Mobile icon -->
                    <input type="checkbox" id="h-cbxM" class="hidden">

                    <label id="h-icoM-box" for="h-cbxM" class="">
                        <div id="h-icoM-line1" class="h-icoM-line">
                            <div class="h-icoM-rot"></div>
                        </div>
                        <div id="h-icoM-line2" class="h-icoM-line">
                            <div class="h-icoM-rot"></div>
                        </div>
                        <div id="h-icoM-line3" class="h-icoM-line">
                            <div class="h-icoM-rot"></div>
                        </div>
                    </label>

                    <!-- Mobile box -->
                    <div id="h-navM"
                        class="txt-lef @php if (!$header['force-mob']) {
                        echo 'mob1280';
                    } @endphp">

                        <div id="h-navM-bgr" class="bgr"></div>

                        <div id="h-contM">

                            <!-- Links -->
                            @foreach ($header['links'] as $h_link)

                                @php

                                    $h_valid = true;

                                    // Check logged filter
                                    if (isset($h_link['logged'])) {
                                        if ($h_link['logged'] && !logged()) {
                                            $h_valid = false;
                                        } elseif (!$h_link['logged'] && logged()) {
                                            $h_valid = false;
                                        }
                                    }

                                    // Check seller filter
                                    if (isset($h_link['seller'])) {
                                        if ($h_link['seller'] && auth()->user() && auth()->user()->is_seller()) {
                                            $h_valid = false;
                                        } elseif (
                                            !$h_link['seller'] ||
                                            !auth()->user() ||
                                            !auth()->user()->is_seller()
                                        ) {
                                            $h_valid = false;
                                        }
                                    }

                                    // Check admin filter
                                    if (isset($h_link['admin']) && !admin()) {
                                        $h_valid = false;
                                    }

                                    // Check cart filter
                                    if (isset($h_link['cart']) && !cart_count()) {
                                        $h_valid = false;
                                    }

                                @endphp

                                @if ($h_valid)
                                    <label class="h-linkM">
                                        <input type="checkbox" class="h-dropM-cbx hidden">

                                        <a <?= !isset($h_link['drop']) ? 'href="' . $h_link['url'] . '"' : '' ?>>
                                            <h3 class="h-linkM-txt txt-upp txt-med">

                                                <span class="h-linkM-bgr bgr bgr-c1"></span>

                                                @php
                                                    echo $h_link['title'];

                                                    if (isset($h_link['drop'])) {
                                                        echo ' <span class="h-dropM-ico">&#x25BC;</span>';
                                                    }
                                                @endphp

                                            </h3>

                                        </a>

                                        @if (isset($h_link['drop']))
                                            <div class="h-dropM">

                                                @foreach ($header['dropdowns'][$h_link['drop']] as $h_drop_link)
                                                    <p class="h-dropM-link txt-wht-h bgr-c3-h">
                                                        <a href="<?= $h_drop_link[1] ?>">
                                                            <?= $h_drop_link[0] ?>
                                                        </a>
                                                    </p>
                                                @endforeach

                                            </div>
                                        @endif

                                    </label>
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>
