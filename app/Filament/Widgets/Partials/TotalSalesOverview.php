<?php

namespace App\Filament\Widgets\Partials;

use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class TotalSalesOverview {

	/**
	 * Invoke the widget.
	 *
	 * @return Stat The generated statistic.
	 */
	public static function make(): Stat {
		$value = Number::abbreviate(OrderItem::hasBeenPaid()->count());
		$firstSaleDate = Order::hasBeenPaid()->oldest()->value('created_at');
		$firstSaleDate = $firstSaleDate ? $firstSaleDate->format('d / m / Y') : 'N/A';

		return Stat::make('Vendite totali', $value)
			->description('Dal ' . $firstSaleDate)
			->descriptionColor('white');
	}
}
