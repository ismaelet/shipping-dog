<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Models\Framework\Configuration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrdersLogController extends Controller {

	/**
     * Clean the order log by resetting the start from date
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function clean(Request $request): RedirectResponse {
		$startFromConfig = $this->getStartFrom();

		$startFromConfig->update([
			'value' => date('Y-m-d H:i:s'),
		]);

		return redirect()->back();
	}

	/**
     * Update the start from date for the order log
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateStartFrom(Request $request): RedirectResponse {
		$startFromConfig = $this->getStartFrom();

		$startFromId = (int) $request->start_order_id;
		$startFromCart = Order::find($startFromId);

		if ($startFromCart) {
			$startFromConfig->update([
				'value' => $startFromCart->created_at,
			]);
		}

		return redirect()->back();
	}

	/**
     * Get the start from configuration
     *
     * @return Configuration|null
     */
    private function getStartFrom(): ?Configuration {
		return Configuration::where('name', 'order_log_start_from')->first();
	}
}
