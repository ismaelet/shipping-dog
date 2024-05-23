<?php

namespace App\Models;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\Seller;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User class
 */
class User extends Authenticatable implements FilamentUser, HasAvatar {

	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable
	 */
	protected $fillable = ['username', 'first_name', 'last_name', 'email', 'password', 'is_admin', 'admin'];

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
		return $this->hasOne(Order::class, 'user_id', 'id')->where('status', EcommerceHelper::statusFromTag('open'));
	}

	/**
	 * Get the user's orders
	 */
	public function orders(): HasMany {
		return $this->hasMany(Order::class);
	}

	/**
	 * Get the user's shop
	 */
	public function seller(): HasOne {
		return $this->hasOne(Seller::class);
	}

	/**
	 * Check if the user is a seller
	 */
	public function isSeller(): bool {
		return (bool) $this->seller;
	}

	public function canAccessPanel(Panel $panel): bool {
		return $this->isAdmin();
	}

	public function getFilamentAvatarUrl(): ?string {
		return public_asset($this->icon());
	}

	//! Legacy support
	public function cover(?int $size = 1024) {
		$sizeConversion = [
			1024 => '',
			400 => '_m',
		];

		$sizeSlug = $sizeConversion[$size] ?? '';

		// Get the HD cover image
		$cover = 'shops/covers/' . $this->id . $sizeSlug;

		if (file_exists(public_storage($cover . '.webp'))) {
			return public_asset($cover . '.webp');
		}

		if (file_exists(public_storage($cover . '.jpg'))) {
			return public_asset($cover . '.jpg');
		}

		return public_asset('shops/covers/0.jpg');
	}

	public function icon(): string {
		$icon = 'users/icons/' . $this->id;

		if (file_exists(public_storage($icon . '.webp'))) {
			return $icon . '.webp';
		}

		if (file_exists(public_storage($icon . '.jpg'))) {
			return $icon . '.jpg';
		}

		return 'users/icons/0.jpg';
	}
}
