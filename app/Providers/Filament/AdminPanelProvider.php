<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Http\Middleware\Admin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider {

	public function panel(Panel $panel): Panel {
		return $panel
			// General settings
			->default()
			->id('admin')
			->path('admin')

			// Style
			->colors([
				'primary' => [
					300 => '179, 153, 243',
					400 => '255, 149, 0',
					500 => '230, 103, 0',
					600 => '230, 103, 0',
				],
				'danger' => [
					400 => '255, 0, 0',
					500 => '204, 0, 0',
					600 => '204, 0, 0',
				],
			])
			->font('Poppins')
			->viteTheme('resources/css/filament/admin/theme.css')
			->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
			->collapsibleNavigationGroups(false)
			->brandLogo(fn () => view('filament.admin.logo'))

			// Pages & Components
			->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
			->pages([
				Dashboard::class,
			])
			->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
			->widgets([
				Widgets\AccountWidget::class,
				Widgets\FilamentInfoWidget::class,
			])
			->navigationItems([
				NavigationItem::make('Sito Pubblico')
					->url('/', shouldOpenInNewTab: true)
					->icon('icon-link'),
				NavigationItem::make('Dashboard Vecchia')
					->url('/legacy-admin/dashboard', shouldOpenInNewTab: false)
					->icon('icon-link')
			])

			// Middlewares
			->middleware([
				EncryptCookies::class,
				AddQueuedCookiesToResponse::class,
				StartSession::class,
				AuthenticateSession::class,
				ShareErrorsFromSession::class,
				VerifyCsrfToken::class,
				SubstituteBindings::class,
				DisableBladeIconComponents::class,
				DispatchServingFilamentEvent::class,
			])
			->authMiddleware([
				Authenticate::class,
				Admin::class,
			]);
	}
}
