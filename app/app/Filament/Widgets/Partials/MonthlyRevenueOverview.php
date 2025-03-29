<?php

namespace App\Filament\Widgets\Partials;

use App\Models\Ecommerce\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class MonthlyRevenueOverview {

	/**
	 * Invoke the widget.
	 *
	 * @return Stat The generated statistic.
	 */
	public static function make(): Stat {
		$value = self::getCurrentInvervalRevenue();
		$lastIntervalValue = self::getLastInvervalRevenue();
		$difference = $lastIntervalValue ? round($value / $lastIntervalValue * 100) - 100 : 0;
		$positiveTrend = $difference > 0;

		return Stat::make('Fatturato mensile', format_price($value))
			->description(($positiveTrend ? '+' : '') . $difference . '% rispetto al mese scorso')
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

		return $data->map(fn(TrendValue $value) => $value->aggregate)->toArray();
	}

	private static function getCurrentInvervalRevenue(): float {
		return Order::hasBeenPaid()->whereBetween('created_at', [now()->subMonth(), now()])->sum('price');
	}

	private static function getLastInvervalRevenue(): float {
		return Order::hasBeenPaid()->whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->sum('price');
	}
}
