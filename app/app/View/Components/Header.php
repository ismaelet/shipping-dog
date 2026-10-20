<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Header extends Component {
    public array $links;
    public array $desktopLinks;
    public array $mobileLinks;
    public bool $showWishlist;

    public function __construct(
        public bool $showThemeSwitcher = true,
        public ?string $location = null,
    ) {
        $this->links = $this->getLinks();

        $this->desktopLinks = array_values(array_filter(
            $this->links,
            fn(array $link) => $this->isVisible($link, false)
        ));

        $this->mobileLinks = array_values(array_filter(
            $this->links,
            fn(array $link) => $this->isVisible($link, true)
        ));

        $this->showWishlist = Auth::check();
    }

    private function getLinks(): array {
        $links = [
            [
                'title' => 'Home',
                'loc' => 'home',
                'url' => route('home'),
                'icon' => 'home'
            ],
            [
                'title' => 'Login',
                'loc' => 'login',
                'url' => '/login',
                'logged' => false,
                'mob' => true,
                'icon' => 'login'
            ],
        ];

        if (Auth::user()?->seller) {
            $links[] = [
                'title' => 'Mio negozio',
                'loc' => 'artist',
                'url' => route('seller.show', Auth::user()->seller),
                'dropdown' => [
                    ['Il mio negozio', route('seller.show', ['seller' => Auth::user()->seller])],
                    ['Le mie grafiche', route('seller-dashboard.portfolio')],
                    ['Carica una grafica', route('seller-dashboard.portfolio.add')],
                    ['Vendite realizzate', route('seller-dashboard.sales')],
                    ['Modifica prezzi', route('seller-dashboard.prices')],
                    ['Gestisci', route('seller-dashboard')],
                ],
                'seller' => true,
                'icon' => 'dashboard'
            ];
        }

        return array_merge($links, [
            [
                'title' => 'Profilo',
                'loc' => 'user-dashboard',
                'url' => route('user-dashboard'),
                'logged' => true,
                'mob' => true,
                'icon' => 'user',
            ],
            [
                'title' => 'Prodotti',
                'loc' => 'shop',
                'url' => route('shop'),
                'dropdown' => [
                    ['Abbigliamento Donna', route('shop', ['gender' => 'f'])],
                    ['Abbigliamento Uomo', route('shop', ['gender' => 'm'])],
                    ['Accessori', route('shop', ['group' => 'm'])],
                    ['Ufficiali', route('seller.show', ['seller' => 1])],
                ],
                'icon' => 'shop'
            ],
            [
                'title' => 'Preferiti',
                'loc' => 'wishlist',
                'url' => route('user-dashboard.wishlist'),
                'logged' => true,
                'mob' => true,
                'icon' => 'heart',
            ],
            [
                'title' => 'Vendi',
                'loc' => 'open-shop',
                'url' => route('open-shop'),
                'seller' => false,
                'icon' => 'open-shop'
            ],
            [
                'title' => 'Supporto',
                'loc' => 'info',
                'url' => route('support.faq'),
                'dropdown' => [
                    ['Contatti', route('contacts')],
                    ['Cambio taglia o reso', route('support.refund.search')],
                    ['Stato ordine', route('orders.find')],
                    ['Domande frequenti', route('support.faq')],
                ],
                'icon' => 'help',
            ],
            [
                'title' => 'Logout',
                'loc' => 'logout',
                'url' => route('logout'),
                'logged' => true,
                'mob' => true,
                'icon' => 'logout'
            ],
        ]);
    }

    private function isVisible(array $link, bool $mobile): bool {
        $authenticated = Auth::check();

        if (
            array_key_exists('logged', $link)
            && $link['logged'] !== $authenticated
        ) {
            return false;
        }

        $isSeller = is_seller();

        if (
            array_key_exists('seller', $link)
            && $link['seller'] !== $isSeller
        ) {
            return false;
        }

        if (isset($link['admin']) && !is_admin()) {
            return false;
        }

        if (isset($link['cart']) && !(cart()?->quantity ?? 0)) {
            return false;
        }

        if (!$mobile && isset($link['mob'])) {
            return false;
        }

        return true;
    }

    public function render(): View {
        return view('components.header');
    }
}
