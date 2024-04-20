<?php

namespace App\Helpers;

use User as OldUser;
use App\Models\User;
use Ecommerce\Cart;
use Illuminate\Support\Facades\Auth;

class App {
	public static function isSeller(): bool {
		if (!Auth::check()) {
			return false;
		}

		$user = User::find(Auth::id());

		return $user->isSeller();
	}

	public static function userOld(): ?OldUser {
		return new OldUser(Auth::id() ?? 0);
	}

	public static function cartOld(): ?Cart {
		return new Cart(cart()->id ?? 0);
	}
}
