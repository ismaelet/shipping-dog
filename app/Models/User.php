<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Seller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method mixed isAdmin()
 */
class User extends Authenticatable {

	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable
	 */
	protected $fillable = [
		'username',
		'name',
		'email',
		'password',
	];

	/**
	 * The attributes that should be hidden for serialization
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'password' => 'hashed',
	];

	/**
	 * Get the user's full name
	 */
	public function fullName(): string {
		$fullName = [];
		$fullName[] = $this->first_name;
		$fullName[] = $this->last_name;

		return implode(' ', $fullName);
	}

	/**
	 * Check if the user is an admin
	 */
	public function isAdmin(): bool {
		return (bool) $this->admin;
	}

	/**
	 * Check if the user is an admin
	 */
	public function isSupervisor(): bool {
		return (bool) $this->admin == 2;
	}

	/**
	 * Get the user's active cart
	 */
	public function cart(): HasOne {
		return $this->hasOne(Cart::class, 'user_id', 'id')->where('status', 0);
	}

	/**
	 * Get the user's shop
	 */
	public function shop(): HasOne {
		return $this->hasOne(Seller::class, 'user_id', 'id');
	}

	/**
	 * Check if the user is a seller
	 */
	public function isSeller(): bool {
		return (bool) $this->shop;
	}
}
