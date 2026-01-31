<?php

namespace App\Filament\Admin\Resources\Sellers\Pages;

use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Admin\Resources\Sellers\SellerResource;
use App\Models\Ecommerce\Seller;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSellers extends ListRecords {
    protected static string $resource = SellerResource::class;

    protected function getHeaderActions(): array {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array {
        return [
            // All sellers
            'all' => Tab::make('Tutti'),

            // Sellers awaiting approval
            'awaiting-approval' => Tab::make('Da Valutare')
                ->modifyQueryUsing(function ($query) {
                    $query->where('status', 0)
                        ->where('activation_request_sent', true);
                })
                ->badge(fn() => Seller::where('status', 0)->where('activation_request_sent', true)->count())
                ->badgeColor(fn() => Seller::where('status', 0)->where('activation_request_sent', true)->count() ? 'primary' : 'gray'),

            // Sellers awaiting payment
            'awaiting-payment' => Tab::make('Da Pagare')
                ->modifyQueryUsing(function ($query) {
                    $query->where('request', true);
                })
                ->badge(Seller::where('request', true)->count())
                ->badgeColor(fn() => Seller::where('request', true)->count() > 0 ? 'primary' : 'gray'),
        ];
    }
}
