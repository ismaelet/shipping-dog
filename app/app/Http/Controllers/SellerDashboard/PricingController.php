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

        $res_cloth = ProductType::where('category', 'c')->where('hidden', false)->with('gender', 'price')->orderBy('id')->get();
        $res_acc = ProductType::where('category', 'm')->where('hidden', false)->with('price')->orderBy('id')->get();

        return view('seller-dashboard.pricing', [
            'seller' => $seller,
            'res_cloth' => $res_cloth,
            'res_acc' => $res_acc,
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
