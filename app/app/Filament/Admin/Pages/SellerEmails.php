<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class SellerEmails extends Page {

	protected static ?string $navigationIcon = 'heroicon-o-envelope';

	protected static string $view = 'filament.admin.pages.seller-emails';

	protected static ?string $title = 'Lista Email';

	protected static ?string $navigationGroup = 'Utenti e Negozi';

	/**
	 * The sort order of the resource's navigation item.
	 * 
	 * @var int
	 */
	protected static ?int $navigationSort = 4;
}
