<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProductsTable;
use App\Filament\Widgets\ProductTypesChart;
use App\Filament\Widgets\SalesOverviewGroup;
use App\Filament\Widgets\UsersOverviewGroup;
use App\Filament\Widgets\YearSalesChart;

class Dashboard extends \Filament\Pages\Dashboard {

	protected static ?string $navigationIcon = 'icon-dashboard';

	public function getColumns(): int|string|array {
		return 12;
	}

	public function getWidgets(): array {
		return [
			YearSalesChart::class,
			SalesOverviewGroup::class,
			UsersOverviewGroup::class,
			ProductsTable::class,
			ProductTypesChart::class,
		];
	}
}
