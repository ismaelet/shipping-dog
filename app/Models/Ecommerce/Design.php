<?php

namespace App\Models\Ecommerce;

use App\Models\User\Like;
use Illuminate\Database\Eloquent\Model;

class Design extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'designs';
	protected $fillable = [];

	/**
	 * Check if the design is liked by the logged user
	 */
	public function isLiked(): bool {
		return Like::where([
			'userID' => userId(),
			'desID' => $this->ID,
		])->count() > 0;
	}
}
