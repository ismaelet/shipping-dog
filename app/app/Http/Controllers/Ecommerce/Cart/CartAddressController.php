<?php

namespace App\Http\Controllers\Ecommerce\Cart;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\ShippingMethod;
use App\Models\Shipping\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartAddressController extends Controller {

	/**
	 * View the cart address page
	 * 
	 * @return \Illuminate\View\View
	 */
	public function index(): View {

		$error = $_GET['error'] ?? false;
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

		$provinces = Province::orderBy('name')->get();

		$address = Address::where('order_id', $cart->id)->first();

		if (!$address && Auth::check()) {
			$address = Address::where('user_id', Auth::id())->latest()->first();
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
				'error' => $error,
				'shippingMethods' => $shippingMethods,
				'provinces' => $provinces,
				'address' => $address,
			]);
	}

	/**
	 * Update or create the address
	 * 
	 * @return \Illuminate\Http\RedirectResponse
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

	/**
	 * 
	 */
	public function addressMethods() {

		// add code
		if (isset($_POST['add_code']) || !empty($_POST['code'])) {
			$coupon = Coupon::generateFromCode($_POST['code']);
			$error = $legacyCart->addCoupon($coupon->id);

			if (!$error) {
				redirect_old('#code-anch');
			} else {
				redirect_old('?step-id=2&error=' . $error . '#code-anch');
			}
		}

		// remove code
		if (isset($_POST['del_code'])) {
			$legacyCart->removeCoupon();

			redirect_old('#code-anch');
		}

		if (isset($_POST['item_credit'])) {
			\Legacy\System\Classes\LegacyDB::update('order_items SET credit=1 WHERE id = ?', [$_POST['item_credit']]);

			$legacyCart->update();

			redirect_old('#items');
		}

		if (isset($_POST['item_no_credit'])) {
			\Legacy\System\Classes\LegacyDB::update('order_items SET credit=0 WHERE id = ?', [$_POST['item_no_credit']]);

			$legacyCart->update();

			redirect_old('#items');
		}

		if (isset($_POST['ship_credit'])) {
			$legacyCart->updateShippingMethodId(1);
			$legacyCart->updateShippingWithCredit(true);

			redirect_old('#shipping');
		}

		if (isset($_POST['ship_no_credit'])) {
			$legacyCart->updateShippingMethodId(2);
			$legacyCart->updateShippingWithCredit(false);

			redirect_old('#shipping');
		}
	}
}
