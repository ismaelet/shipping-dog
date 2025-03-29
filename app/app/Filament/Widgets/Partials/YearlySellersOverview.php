<?php

namespace App\Filament\Widgets\Partials;

use App\Models\Ecommerce\Seller;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Number;

class YearlySellersOverview {

	/**
	 * Invoke the widget.
	 *
	 * @return Stat The generated statistic.
	 */
	public static function make(): Stat {
		$value = self::getCurrentInvervalSellerCount();
		$difference = round($value / self::getLastInvervalSellerCount() * 100) - 100;
		$positiveTrend = $difference > 0;

		return Stat::make('Nuovi negozi', Number::abbreviate($value))
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
		$data = Trend::model(Seller::class)
			->between(
				start: now()->subYear(),
				end: now(),
			)
			->perMonth()
			->count();

		return $data->map(fn (TrendValue $value) => $value->aggregate)->toArray();
	}

	/**
	 * Get the count of Sellers created in the current Inverval.
	 *
	 * @return int The count of Sellers.
	 */
	private static function getCurrentInvervalSellerCount(): int {
		return Seller::whereBetween('created_at', [now()->subYear(), now()])->count();
	}

	/**
	 * Get the count of Sellers created in the last Inverval.
	 *
	 * @return int The count of Sellers.
	 */
	private static function getLastInvervalSellerCount(): int {
		return Seller::whereBetween('created_at', [now()->subYears(2), now()->subYear()])->count();
	}
}
