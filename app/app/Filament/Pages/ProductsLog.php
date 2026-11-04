<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ProductsLog extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.products-log';

    protected static ?string $title = 'Log dei prodotti';

    protected static string|\UnitEnum|null $navigationGroup = 'Vendite';

    /**
     * The sort order of the resource's navigation item.
     */
    protected static ?int $navigationSort = 2;
}
