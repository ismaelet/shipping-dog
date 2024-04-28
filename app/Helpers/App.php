<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class App {
	public static function isSeller(): bool {
		if (!Auth::check()) {
			return false;
		}

		$user = User::find(Auth::id());

		return $user->isSeller();
	}
}
