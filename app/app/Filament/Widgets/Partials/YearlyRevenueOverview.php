<?php

namespace App\Filament\Widgets\Partials;

use App\Models\Ecommerce\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class YearlyRevenueOverview {

	/**
	 * Invoke the widget.
	 *
	 * @return Stat The generated statistic.
	 */
	public static function make(): Stat {
		$value = self::getCurrentIntervalRevenue();
		$difference = round($value / self::getLastIntervalRevenue() * 100) - 100;
		$positiveTrend = $difference > 0;

		return Stat::make('Fatturato annuale', format_price($value))
			->description(($positiveTrend ? '+' : '') . $difference . '% rispetto all\'anno scorso')
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
				start: now()->subYear(),
				end: now(),
			)
			->perMonth()
			->sum('price');

		return $data->map(fn (TrendValue $value) => $value->aggregate)->toArray();
	}

	private static function getCurrentIntervalRevenue(): float {
		return Order::hasBeenPaid()->whereBetween('created_at', [now()->subYear(), now()])->sum('price');
	}

	private static function getLastIntervalRevenue(): float {
		return Order::hasBeenPaid()->whereBetween('created_at', [now()->subYears(2), now()->subYear()])->sum('price');
	}
}
