<?php

namespace App\Http\Controllers\Ecommerce\Order;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderFindController extends Controller {

    /**
     * Display the page to find an order by email and ID.
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View {
        $title = 'Stato ordine';
        $locationTag = 'support';

        $inputs = [
            [
                ['text' => 'Indirizzo email', 'name' => 'email', 'required' => true],
            ],
            [
                ['text' => 'Numero ordine', 'name' => 'id', 'required' => true],
                ['type' => 'error', 'text' => 'Nessun ordine trovato corrispondente ai dati inseriti', 'error' => 'no_match'],
            ],
        ];

        return view('ecommerce.orders.find', compact(
            'title',
            'inputs',
            'locationTag'
        ));
    }


    /**
     * Display the page to find an order by email and ID.
     * 
     * @param Request $request
     * @return View
     */
    public function show(Request $request): RedirectResponse {
        $id = (int) trim($request->id, '#');
        $email = strtolower(trim($request->email));

        $order = Order::join('addresses', 'addresses.order_id', 'orders.id')
            ->where('orders.id', $id)
            ->where('email', $email)
            ->where('status', '!=', EcommerceHelper::statusFromTag('open'))
            ->select('orders.*', 'addresses.email')
            ->first();

        if ($order) {
            return redirect($order->recapUrlWithToken());
        }

        return redirect()->back()
            ->withInput()
            ->with(['error' => 'Nessun ordine trovato corrispondente ai dati inseriti']);
    }
}
