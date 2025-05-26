<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class OrdersLog extends Page {

	protected static ?string $navigationIcon = 'heroicon-o-document-text';

	protected static string $view = 'filament.admin.pages.orders-log';

	protected static ?string $title = 'Log degli Ordini';

	protected static ?string $navigationGroup = 'Gestione Vendite';
}
