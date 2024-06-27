<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Partials\MonthlyRevenueOverview;
use App\Filament\Widgets\Partials\TotalSalesOverview;
use App\Filament\Widgets\Partials\YearlyRevenueOverview;
use Filament\Widgets\StatsOverviewWidget;

class SalesOverviewGroup extends StatsOverviewWidget {

	protected static ?string $pollingInterval = null;

	protected static bool $isLazy = false;

	protected int | string | array $columnSpan = [
		'lg' => 5,
	];

	public function getColumns(): int {
		return 1;
	}

	protected function getStats(): array {
		return [
			MonthlyRevenueOverview::make(),
			YearlyRevenueOverview::make(),
			TotalSalesOverview::make(),
		];
	}
}

/*

Revenue by Product Category
Best-Selling Products
*/