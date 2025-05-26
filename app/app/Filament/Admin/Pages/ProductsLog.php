<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class ProductsLog extends Page {

	protected static ?string $navigationIcon = 'heroicon-o-document-text';

	protected static string $view = 'filament.admin.pages.products-log';

	protected static ?string $title = 'Log dei Prodotti';

	protected static ?string $navigationGroup = 'Gestione Vendite';
}
