<?php

namespace App\Http\Controllers\Ecommerce\Cart;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\ShippingMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartAddressController extends Controller {

    /**
     * View the cart address page
     */
    public function index(): View {
        $cart = cart();
        $cart->load(
            'order_items.color',
            'order_items.position',
            'order_items.size',
            'order_items.product',
            'order_items.product.type',
            'order_items.product_with_trashed',
            'order_items.product_with_trashed.design_with_trashed',
            'order_items.product_with_trashed.type',
        );

        $address = $cart->address;

        if (!$address && Auth::check()) {
            // retrieve the latest address of the user and save it to the cart
            $oldAddress = Address::where('user_id', Auth::id())->latest()->first();
            $address = $oldAddress ? $oldAddress->replicate(['order_id'])->fill(['order_id' => $cart->id]) : null;

            if ($address) {
                $cart->address()->save($address);
                $cart->refresh();
            }
        }

        if (!in_array($cart->shipping_method_id, ShippingMethod::FREE_SHIPPING_METHODS)) {
            // if the shipping isn't free, fetch the shipping methods
            $shippingMethods = ShippingMethod::whereRaw('FIND_IN_SET(tag, "sta,pre,rit") > 0')
                ->orderByDesc('tier')
                ->get();
        } else {
            // if the shipping is free, fetch only the free shipping method
            $shippingMethods = ShippingMethod::where('id', ShippingMethod::FREE_SHIPPING_METHODS[0])->get();
        }

        return view('ecommerce.cart.address')
            ->with([
                'title' => 'Indirizzo',
                'shippingMethods' => $shippingMethods,
                'address' => $address,
                'cart' => $cart,
            ]);
    }

    /**
     * Update or create the address
     */
    public function update(): RedirectResponse {
        $cart = cart();

        $full_name = str_replace('-', ',', $_POST['full_name']);
        $addr = ucwords(strtolower(trim($_POST['address'])));
        $num = trim($_POST['number']);
        $zip = strtoupper(trim($_POST['zip']));
        $city = str_replace('-', ',', $_POST['city']);
        $city = ucwords(strtolower(trim($city)));
        $email = strtolower(trim($_POST['email']));
        $phone = trim($_POST['phone']);
        $security = random_string();
        $courierNotes = $_POST['courier_notes'] ?? '';
        $reg = $_POST['region'] ?? '';

        Address::updateOrCreate([
            'order_id' => $cart->id,
        ], [
            'user_id' => Auth::id() ?: null,
            'full_name' => $full_name,
            'address' => $addr,
            'number' => $num,
            'zip' => $zip,
            'city' => $city,
            'region' => $reg,
            'email' => $email,
            'phone' => $phone,
            'security' => $security
        ]);

        $cart->update([
            'courier_notes' => $courierNotes
        ]);

        $cart->shipping_method_id = request()->shipping_method_id;
        $cart->save();
        $cart->updatePrice();

        cart()->advance();

        return redirect()->route('cart.' . cart()->stepTag());
    }
}
