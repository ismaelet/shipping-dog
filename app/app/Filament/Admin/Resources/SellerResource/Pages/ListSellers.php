<?php

namespace App\Filament\Admin\Resources\SellerResource\Pages;

use App\Filament\Admin\Resources\SellerResource;
use App\Models\Ecommerce\Seller;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListSellers extends ListRecords {
	protected static string $resource = SellerResource::class;

	protected function getHeaderActions(): array {
		return [
			Actions\CreateAction::make(),
		];
	}

	public function getTabs(): array {
		return [
			'all' => Tab::make('Tutti'),
			'awaiting-approval' => Tab::make('Da Valutare')
				->modifyQueryUsing(function ($query) {
					$query->where('status', 0)
						->where('user_id', '!=', 2)
						->whereIn('user_id', function ($query) {
							$query->select('user_id')
								->from('designs')
								->where('status', 1);
						});
				})
				->badge(Seller::awaitingApproval()->count())
				->badgeColor(fn() => Seller::awaitingApproval()->count() > 0 ? 'primary' : 'gray'),
			'awaiting-payment' => Tab::make('Da Pagare')
				->modifyQueryUsing(function ($query) {
					$query->where('request', true);
				})
				->badge(Seller::where('request', true)->count())
				->badgeColor(fn() => Seller::where('request', true)->count() > 0 ? 'primary' : 'gray'),
		];
	}
}
