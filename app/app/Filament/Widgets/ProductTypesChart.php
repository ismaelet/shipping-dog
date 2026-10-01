<?php

namespace App\Filament\Widgets;

use App\Models\Ecommerce\OrderItem;
use Filament\Widgets\ChartWidget;

class ProductTypesChart extends ChartWidget {

    protected ?string $heading = 'Categorie più vendute';

    protected ?string $description = 'Le categorie di prodotto più vendute del mese';

    protected ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = [
        'lg' => 7,
    ];

    protected function getType(): string {
        return 'bar';
    }

    protected function getData(): array {
        $productTypes = [];

        $data = OrderItem::hasBeenPaid()
            ->selectRaw('type_id, SUM(quantity) as quantity')
            ->whereBetween('created_at', [now()->subMonth(), now()])
            ->whereHas('type')
            ->groupBy('type_id')
            ->orderBy('quantity', 'desc')
            ->with('type')
            ->limit(10)
            ->get();

        $data = $data->sortBy('quantity');

        $data->each(function ($item) use (&$productTypes) {
            $productTypes[$item->type->name] = $item->quantity;
        });

        return [
            'datasets' => [
                [
                    'label' => now()->year,
                    'data' => array_values($productTypes),
                ],
            ],
            'labels' => array_keys($productTypes),
        ];
    }
}
