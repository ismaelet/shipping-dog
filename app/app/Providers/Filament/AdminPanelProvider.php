<?php

namespace App\Providers\Filament;

use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use App\Filament\Pages\Dashboard;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider {
	public function panel(Panel $panel): Panel {
		return $panel
			->id('admin')
			->path('admin')
			->colors([
				'gray' => Color::Gray,
				'primary' => Color::Orange,
				'secondary' => Color::Cyan,

				// Order status colors
				'status-cancelled' => Color::Red,
				'status-open' => Color::Gray,
				'status-received' => Color::Blue,
				'status-confirmed' => Color::Green,
				'status-awaiting-shipping' => Color::Lime,
				'status-shipped' => Color::Yellow,
				'status-delivered' => Color::Orange,
			])
			->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
			->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
			->pages([])
			->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
			->widgets([
				AccountWidget::class,
				FilamentInfoWidget::class,
			])
			->userMenuItems([
				Action::make('public-website')
					->label('Sito Pubblico')
					->url('/')
					->icon('heroicon-o-globe-alt')
			])
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
			]);
	}
}
