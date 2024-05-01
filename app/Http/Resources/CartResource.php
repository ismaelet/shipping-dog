<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {
		return [
			'id' => $this->id,
			'name' => $this->id . ' - ' . $this->address?->full_name,
			'created_at' => $this->created_at,
		];
	}
}
