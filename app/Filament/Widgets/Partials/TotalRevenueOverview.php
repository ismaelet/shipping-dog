<?php

namespace App\Filament\Widgets\Partials;

use App\Models\Ecommerce\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TotalRevenueOverview {

	/**
	 * Invoke the widget.
	 *
	 * @return Stat The generated statistic.
	 */
	public static function make(): Stat {
		$value = self::getCurrentMonthRevenue();
		$difference = round($value / self::getLastMonthRevenue() * 100) - 100;
		$positiveTrend = $difference > 0;

		return Stat::make('Fatturato totale', format_price($value))
			->description($difference . '% rispetto al mese scorso')
			->descriptionIcon($positiveTrend ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
			->chart(self::getData())
			->color($positiveTrend ? 'success' : 'danger');
	}

	/**
	 * Get the data for the chart.
	 *
	 * @return array The chart data.
	 */
	private static function getData(): array {
		$data = Trend::query(
			Order::hasBeenPaid()
		)
			->between(
				start: now()->subMonth(),
				end: now(),
			)
			->perDay()
			->sum('price');

		return $data->map(fn (TrendValue $value) => $value->aggregate)->toArray();
	}

	private static function getCurrentMonthRevenue(): float {
		return Order::hasBeenPaid()->whereBetween('created_at', [now()->subMonth(), now()])->sum('price');
	}

	private static function getLastMonthRevenue(): float {
		return Order::hasBeenPaid()->whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->sum('price');
	}
}
