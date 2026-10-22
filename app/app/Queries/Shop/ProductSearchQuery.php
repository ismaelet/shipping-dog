<?php

namespace App\Queries\Shop;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Seller;
use Illuminate\Database\Eloquent\Builder;

class ProductSearchQuery {
    public function build(
        ?Seller $seller = null,
        string $search = '',
        ?int $typeId = null,
        ?int $genderId = null,
        string $sort = 'popular',
        bool $diversify = false,
    ): Builder {
        $products = Product::query()
            ->select('products.*')
            ->join('sellers', 'products.user_id', '=', 'sellers.user_id')
            ->leftJoin('designs', 'products.design_id', '=', 'designs.id')
            ->where('products.status', 1)
            ->where('products.deleted', 0)
            ->whereHas('type', function ($query) {
                $query->whereNull('variant_of');
            });

        // Filter by seller
        if ($seller) {
            $products->where('products.user_id', $seller->user_id);
        } else {
            $products
                ->where('sellers.status', true)
                ->where('sellers.hidden', false);
        }

        // Filter by search keyword
        if ($search !== '') {
            $products->where(function ($query) use ($search) {
                $query
                    ->where('products.name', 'like', "%{$search}%")
                    ->orWhere('sellers.name', 'like', "%{$search}%")
                    ->orWhereIn('products.design_id', function ($query) use ($search) {
                        $query
                            ->select('design_id')
                            ->from('design_tags')
                            ->where('tags', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by product type
        if ($typeId) {
            $products->where('products.type_id', $typeId);
        }

        // Filter by gender
        if ($genderId) {
            $products->whereHas('type', function ($query) use ($genderId) {
                $query->whereIn('gender_id', [$genderId, 3]);
            });
        }

        // Sort products
        switch ($sort) {
            case 'new':
                $products
                    ->orderByDesc('products.time')
                    ->orderByDesc('products.design_id')
                    ->orderBy('products.type_id');
                break;

            case 'custom':
                $products
                    ->orderBy('products.type_id')
                    ->orderBy('designs.sort');
                break;

            default:
                if ($diversify) {
                    // Rank each product within its type by popularity
                    $products->selectRaw('
                ROW_NUMBER() OVER (
                    PARTITION BY products.type_id
                    ORDER BY
                        products.recent_sales DESC,
                        products.sales DESC,
                        products.design_id DESC,
                        products.id ASC
                ) AS type_rank
            ');

                    // Alternate between product types,
                    // prioritizing more popular products in each round
                    $products
                        ->orderBy('type_rank')
                        ->orderByDesc('products.recent_sales')
                        ->orderByDesc('products.sales')
                        ->orderByDesc('products.design_id')
                        ->orderBy('products.type_id');
                } else {
                    // Original popularity sorting
                    $products
                        ->orderByDesc('products.recent_sales')
                        ->orderByDesc('products.sales')
                        ->orderByDesc('products.design_id')
                        ->orderBy('products.type_id');
                }
                break;
        }

        return $products
            ->orderBy('products.id')
            ->with([
                'type',
                'design',
                'design_with_trashed',
                'design.seller',
                'design.logo_group',
                'seller',
                'seller.prices',
                'color',
                'specific_price',
                'base_price',
                'position',
            ]);
    }
}
