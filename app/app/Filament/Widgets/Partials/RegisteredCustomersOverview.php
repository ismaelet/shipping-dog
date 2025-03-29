<?php

namespace App\Filament\Widgets\Partials;

use App\Models\Ecommerce\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegisteredCustomersOverview {

	/**
	 * Invoke the widget.
	 *
	 * @return Stat The generated statistic.
	 */
	public static function make(): Stat {
		$value = self::getIntervalPercentage(0);
		$difference = round($value / self::getIntervalPercentage(1) * 100) - 100;
		$positiveTrend = $difference > 0;

		return Stat::make('Clienti registrati', $value . '%')
			->description(($positiveTrend ? '+' : '') . $difference . '% rispetto all\'anno scorso')
			->descriptionIcon($positiveTrend ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
			->color($positiveTrend ? 'success' : 'danger');
	}

	private static function getIntervalPercentage(int $intervalJump): int {
		$registeredCustomers = Order::hasBeenPaid()
			->whereNotNull('user_id')
			->whereBetween('created_at', [now()->subYears($intervalJump + 1), now()->subYears($intervalJump)])
			->count();
		$notRegisteredCustomers = Order::hasBeenPaid()
			->whereNull('user_id')
			->whereBetween('created_at', [now()->subYears($intervalJump + 1), now()->subYears($intervalJump)])
			->count();

		return round($registeredCustomers / ($registeredCustomers + $notRegisteredCustomers) * 100);
	}
}
