<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SellerEmails extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected string $view = 'filament.pages.seller-emails';

    protected static ?string $title = 'Lista Email';

    protected static string|\UnitEnum|null $navigationGroup = 'Utenti e Negozi';

    /**
     * The sort order of the resource's navigation item.
     */
    protected static ?int $navigationSort = 4;
}
