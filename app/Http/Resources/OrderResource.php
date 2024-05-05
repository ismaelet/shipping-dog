<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {
		return [
			'id' => $this->id,
			'name' => $this->id . ' - ' . $this->address?->full_name,
			'price_string' => $this->priceString(),
			'order_items_price' => $this->orderItemsPrice(),
			'gross_order_items_price_string' => $this->grossOrderItemsPriceString(),
			'shipping_fee_string' => $this->shippingFeeString(),
			'quantity' => $this->quantity,
			'created_at' => $this->created_at,
		];
	}
}
