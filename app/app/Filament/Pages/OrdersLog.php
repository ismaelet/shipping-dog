<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class OrdersLog extends Page {

	protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

	protected string $view = 'filament.pages.orders-log';

	protected static ?string $title = 'Log degli ordini';

	protected static string | \UnitEnum | null $navigationGroup = 'Vendite';

	/**
	 * The sort order of the resource's navigation item.
	 * 
	 * @var int
	 */
	protected static ?int $navigationSort = 2;
}
