<?php

namespace App\Filament\Widgets;

use App\Models\Ecommerce\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class YearSalesChart extends ChartWidget {

	protected static ?string $heading = 'Panoramica annuale';

	protected static ?string $description = 'Panoramica delle vendite dell\'anno corrente e del precedente.';

	protected static ?string $pollingInterval = null;

	protected static bool $isLazy = false;

	protected int | string | array $columnSpan = [
		'lg' => 7,
	];

	protected function getType(): string {
		return 'bar';
	}

	protected function getData(): array {
		$currentYearData = Trend::query(
			Order::hasBeenPaid()
		)
			->between(
				start: now()->startOfYear(),
				end: now(),
			)
			->perMonth()
			->sum('price');

		$lastYearData = Trend::query(
			Order::hasBeenPaid()
		)
			->between(
				start: now()->subYear()->startOfYear(),
				end: now()->subYear()->endOfYear(),
			)
			->perMonth()
			->sum('price');

		return [
			'datasets' => [
				[
					'label' => now()->year,
					'data' => $currentYearData->map(fn (TrendValue $value) => $value->aggregate),
				],
				[
					'label' => now()->subYear()->year,
					'data' => $lastYearData->map(fn (TrendValue $value) => $value->aggregate),
					'borderColor' => 'rgba(85, 100, 120, 1)',
					'backgroundColor' => 'rgba(85, 100, 120, 0.1)',
				],
			],
			'labels' => ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
		];
	}
}
