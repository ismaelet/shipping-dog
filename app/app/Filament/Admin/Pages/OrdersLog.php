<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class OrdersLog extends Page {

	protected static ?string $navigationIcon = 'heroicon-o-document-text';

	protected static string $view = 'filament.admin.pages.orders-log';

	protected static ?string $title = 'Log degli ordini';

	protected static ?string $navigationGroup = 'Vendite';

	/**
	 * The sort order of the resource's navigation item.
	 * 
	 * @var int
	 */
	protected static ?int $navigationSort = 2;
}
