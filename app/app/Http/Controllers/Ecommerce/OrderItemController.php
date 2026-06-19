<?php

namespace App\Http\Controllers\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\ShippingMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderItemController extends Controller {

    /**
     * Resource action: Store a new order item in the cart
     */
    public function store(Request $request): RedirectResponse {
        // Fetch the product
        $product = Product::find($request->product_id);

        // Set the default position ID
        if (!$request->position_id) {
            $request->merge(['position_id', $product->type->positions()->first()->id]);
        }

        // Set the default quantity
        if (!$request->quantity) {
            $request->merge(['quantity', 1]);
        }

        // Validate the request
        $request->validate([
            'product_id' => ['required', 'integer'],
            'color_id' => ['required', 'integer'],
            'size_id' => ['required', 'integer'],
            'position_id' => ['integer'],
            'logo_id' => ['integer'],
            'quantity' => ['integer'],
        ]);

        // Set the logo and size to calculate an accurate price
        $product->logo_id = $request->logo_id ?: 0;
        $product->size_id = $request->size_id;

        // Get the cart
        if (cart()) {
            $order = cart();

            if ($order->step > EcommerceHelper::stepFromTag('address')) {
                $order->step = EcommerceHelper::stepFromTag('address');
                $order->save();
            }
        } else {
            if (Auth::check()) {
                // If the user is logged in
                $order = Order::create(['user_id' => Auth::id()]);
            } else {
                // If the user is a guest
                $order = Order::create();

                session(['cart_id' => $order->id]);
            }

            $order->shipping_method_id = ShippingMethod::defaultShppingMethodId();
            $order->save();
        }

        // Calculate the amounts and discounts
        $unitAmountBeforeDiscount = $product->fullPrice();
        $totalAmountBeforeDiscount = $unitAmountBeforeDiscount * $request->quantity;

        $unitDiscount = $product->customerDiscount();
        $totalDiscount = $unitDiscount * $request->quantity;

        $unitAmountAfterDiscount = $unitAmountBeforeDiscount - $unitDiscount;
        $totalAmountAfterDiscount = $unitAmountAfterDiscount * $request->quantity;

        $unitAmountToPay = $unitAmountAfterDiscount;
        $totalAmountToPay = $unitAmountToPay * $request->quantity;

        // Calculate the seller's earnings and reductions
        $color = $product->colors()->find($request->color_id) ?? null;

        $unitSellerEarningBeforeReduction = $product->sellerEarningBeforeReduction($color);
        $totalSellerEarningBeforeReduction = $unitSellerEarningBeforeReduction * $request->quantity;

        $unitSellerReduction = $product->userIsOwner() ? $product->sellerReduction() : 0;
        $totalSellerReduction = $unitSellerReduction * $request->quantity;

        $unitSellerEarningAfterReduction = $unitSellerEarningBeforeReduction - $unitSellerReduction;
        $totalSellerEarningAfterReduction = $unitSellerEarningAfterReduction * $request->quantity;

        // Create the order
        OrderItem::create([
            ...$request->all(),
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'seller_id' => $product->seller->id,
            'design_id' => $product->design_id,
            'type_id' => $product->type->id,
            'unit_amount_before_discount' => $unitAmountBeforeDiscount,
            'total_amount_before_discount' => $totalAmountBeforeDiscount,
            'unit_amount_after_discount' => $unitAmountAfterDiscount,
            'total_amount_after_discount' => $totalAmountAfterDiscount,
            "unit_amount_to_pay" => $unitAmountToPay,
            "total_amount_to_pay" => $totalAmountToPay,
            'unit_discount' => $unitDiscount,
            'total_discount' => $totalDiscount,
            'unit_seller_earnings_before_reduction' => $unitSellerEarningBeforeReduction,
            'total_seller_earnings_before_reduction' => $totalSellerEarningBeforeReduction,
            'unit_seller_earnings_after_reduction' => $unitSellerEarningAfterReduction,
            'total_seller_earnings_after_reduction' => $totalSellerEarningAfterReduction,
            'unit_seller_reduction' => $unitSellerReduction,
            'total_seller_reduction' => $totalSellerReduction,
        ]);

        // Update the cart data
        $order->updateOrderItems();

        // Update the discount
        if ($order->coupon?->code) {
            $couponId = $order->coupon->code;
            $order->removeCoupon();
            $order->applyCoupon($couponId);
        }

        return to_route('cart');
    }
}
