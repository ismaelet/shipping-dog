<?php

namespace App\Http\Controllers\Ecommerce;

use App\Actions\Designs\EditDesign;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product;
use App\Models\Warning;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Resource action: Display the specified product
     */
    public function show(Product $product, Request $request): View|RedirectResponse
    {
        if (
            ! $product?->id ||
            ! $product?->seller ||
            $product->trashed() ||
            ($product->status == 0 && ! $product->userIsOwner() && ! is_admin())
        ) {
            return redirect()->route('shop');
        }

        if ($request->color_id) {
            $product->color_id = $request->color_id;
        }

        if ($request->position_id) {
            $product->position_id = $request->position_id;
        }

        $title = $product->name;
        $thumbnail = $product->image(1024);

        $sameDesignProducts = Product::whereHas('type', function ($query) {
            $query->doesntHave('main_type');
        })
            ->where('design_id', $product->design_id)
            ->where('type_id', '<>', $product->type_id)
            ->where('products.status', 1)
            ->where('products.deleted', 0)
            ->with(
                'design',
                'design_with_trashed',
                'color',
                'position',
                'type',
                'seller.prices',
                'specific_price',
                'base_price',
            )
            ->get();

        $relatedGarments = $sameDesignProducts->where('type.category', 'c')->sortBy('type.sort');
        $relatedAccessories = $sameDesignProducts->where('type.category', 'm')->sortBy('type.sort');

        $productColors = $product->colors;

        $defaultColor = $productColors->firstWhere('id', $product->color_id) ?? $productColors->first();
        $defaultSize = $product->type->sizes->first();
        $defaultPosition = $product->type->positions->first();

        $warning = Warning::currentWarning();

        $breadcrumbs = [
            ['label' => 'Prodotti', 'url' => route('shop')],
            ['label' => $product->type->name, 'url' => route('shop', ['type' => $product->type->id])],
            ['label' => $product->name, 'url' => null],
        ];

        return view('ecommerce.product', [
            'product' => $product,
            'location' => 'shop',
            'title' => $title,
            'thumbnail' => $thumbnail,
            'productColors' => $productColors,
            'defaultColor' => $defaultColor,
            'defaultSize' => $defaultSize,
            'defaultPosition' => $defaultPosition,
            'relatedGarments' => $relatedGarments,
            'relatedAccessories' => $relatedAccessories,
            'warning' => $warning,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Custom action: Change the status
     */
    public function changeStatus(Product $product, Request $request): RedirectResponse
    {
        EditDesign::product($product->id);
        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        EditDesign::status($product, (bool) $request->status);

        return redirect()->back();
    }
}
