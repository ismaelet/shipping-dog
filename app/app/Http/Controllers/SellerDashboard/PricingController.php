<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Http\Controllers\Controller;
use App\Legacy\Ecommerce\Product\Type;
use App\Legacy\System\DB;
use App\Models\Ecommerce\Seller\SellerPrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PricingController extends Controller {

    /**
     * Display the pricing page for the seller.
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View {
        $seller = Auth::user()->seller;

        $res_cloth = DB::select('id FROM product_types WHERE category=? AND hidden = 0 ORDER BY id ASC', ['c']);
        $res_acc = DB::select('id FROM product_types WHERE category=? AND hidden = 0 ORDER BY id ASC', ['m']);

        return view('seller-dashboard.pricing', [
            'seller' => $seller,
            'res_cloth' => $res_cloth,
            'res_acc' => $res_acc,
        ]);
    }

    public function update(): RedirectResponse {
        $seller = Auth::user()->seller;

        $res = DB::select('id FROM product_types WHERE hidden = 0 ORDER BY id ASC');

        while ($row = $res->fetch_assoc()) {

            $type = new Type($row['id']);

            $price = $_POST['price_' . $type->slug];

            $row_price = DB::row('* FROM prices_sellers WHERE seller_id = ? AND type_id = ?', [$seller->id, $type->id]) ?: DB::row('* FROM prices_default WHERE type_id = ?', [$type->id]);

            $price_old = $row_price['price'];
            $gain_w_old = $row_price['gain_w'];
            $gain_c_old = $row_price['gain_c'];

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
