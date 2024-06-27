<?php

namespace App\Filament\Widgets\Partials;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Number;

class MonthlyUsersOverview {

	/**
	 * Invoke the widget.
	 *
	 * @return Stat The generated statistic.
	 */
	public static function make(): Stat {
		$value = self::getCurrentInvervalUserCount();
		$difference = round($value / self::getLastInvervalUserCount() * 100) - 100;
		$positiveTrend = $difference > 0;

		return Stat::make('Nuovi utenti', Number::abbreviate($value))
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
		$data = Trend::model(User::class)
			->between(
				start: now()->subMonth(),
				end: now(),
			)
			->perDay()
			->count();

		return $data->map(fn (TrendValue $value) => $value->aggregate)->toArray();
	}

	/**
	 * Get the count of users created in the current Inverval.
	 *
	 * @return int The count of users.
	 */
	private static function getCurrentInvervalUserCount(): int {
		return User::whereBetween('created_at', [now()->subMonth(), now()])->count();
	}

	/**
	 * Get the count of users created in the last Inverval.
	 *
	 * @return int The count of users.
	 */
	private static function getLastInvervalUserCount(): int {
		return User::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();
	}
}
