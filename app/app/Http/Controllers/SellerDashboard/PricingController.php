<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Seller\SellerPrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PricingController extends Controller {

    /**
     * Display the pricing page for the seller.
     */
    public function index(): View {
        $seller = Auth::user()->seller;

        $clothing = ProductType::where('category', 'c')->where('hidden', false)->with('gender', 'price')->orderBy('id')->get();
        $accessories = ProductType::where('category', 'm')->where('hidden', false)->with('price')->orderBy('id')->get();
        $sellerPrices = $seller->prices->keyBy('type_id');

        $formatItems = function ($types, bool $includeGender = false) use ($sellerPrices) {
            return $types->map(function (ProductType $type) use ($sellerPrices, $includeGender) {
                $price = $sellerPrices->get($type->id) ?? $type->price;
                $gainWhite = (float) $price->gain_w;
                $gainColor = (float) $price->gain_c;
                $baseWhite = (float) $price->price - $gainWhite / 0.75;
                $baseColor = (float) $price->price - $gainColor / 0.75;

                return [
                    'name' => trim($type->name . ' ' . ($includeGender ? ($type->gender?->name ?? '') : '')),
                    'tag' => $type->tag,
                    'price' => (float) $price->price,
                    'gainWhite' => $gainWhite,
                    'gainColor' => $gainColor,
                    'minimum' => round((float) $price->price - max($gainWhite, $gainColor) / 0.75, 2),
                    'baseWhite' => $baseWhite,
                    'baseColor' => $baseColor,
                ];
            });
        };

        return view('seller-dashboard.pricing', [
            'seller' => $seller,
            'pricingGroups' => [
                [
                    'title' => 'Abbigliamento',
                    'items' => $formatItems($clothing, true),
                    'hasColorGains' => true,
                    'accent' => 'primary',
                ],
                [
                    'title' => 'Accessori',
                    'items' => $formatItems($accessories),
                    'hasColorGains' => false,
                    'accent' => 'detail-1',
                ],
            ],
        ]);
    }

    public function update(): RedirectResponse {
        $seller = Auth::user()->seller;

        foreach (ProductType::where('hidden', false)->with('price')->get() as $type) {
            $price = request()->input('price_' . $type->tag);
            if ($price === null) continue;

            $row_price = $seller->prices()->where('type_id', $type->id)->first() ?? $type->price;
            if (! $row_price) continue;

            $price_old = $row_price->price;
            $gain_w_old = $row_price->gain_w;
            $gain_c_old = $row_price->gain_c;

            $gain_w = round(($price - $price_old) * .75 + $gain_w_old, 2);
            $gain_c = round(($price - $price_old) * .75 + $gain_c_old, 2);

            // add prices
            SellerPrice::updateOrCreate(
                [
                    'seller_id' => $seller->id,
                    'type_id' => $type->id,
                ],
                [
                    'price' => $price,
                    'gain_w' => $gain_w,
                    'gain_c' => $gain_c,
                ]
            );
        }

        return redirect()->back()->with([
            'success' => 'I prezzi sono stati aggiornati con successo!'
        ]);
    }
}
