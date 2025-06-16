<?php

namespace App\Models;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\Seller;
use App\Models\User\Like;
use App\Models\User\UserContact;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User class
 */
class User extends Authenticatable implements FilamentUser, HasAvatar {

	use HasFactory, Notifiable, SoftDeletes;

	/**
	 * The attributes that are mass assignable
	 */
	protected $fillable = [
		'username',
		'first_name',
		'last_name',
		'email',
		'password',
		'is_admin',
		'admin'
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
	 * Delete the user's related data
	 */
	public function delete() {
		$this->seller?->delete();

		return parent::delete();
	}

	/**
	 * Get the username
	 * 
	 * @return \Illuminate\Database\Eloquent\Casts\Attribute
	 */
	public function name(): Attribute {
		return Attribute::make(
			get: fn(?string $value): string => $value ?: $this->email,
		);
	}

	/**
	 * Get whether the user is an admin
	 * 
	 * @return \Illuminate\Database\Eloquent\Casts\Attribute
	 */
	public function isAdmin(): Attribute {
		return Attribute::make(
			get: fn(): bool => $this->admin_level,
		);
	}

	/**
	 * Get whether the user is a superadmin
	 * 
	 * @return \Illuminate\Database\Eloquent\Casts\Attribute
	 */
	public function isSuperadmin(): Attribute {
		return Attribute::make(
			get: fn(): bool => $this->admin_level == 2,
		);
	}

	/**
	 * Get the user's active cart
	 */
	public function cart(): HasOne {
		return $this->hasOne(Order::class, 'user_id', 'id')->where('status', EcommerceHelper::statusFromTag('open'));
	}

	/**
	 * Get the user's shop
	 */
	public function seller(): HasOne {
		return $this->hasOne(Seller::class);
	}

	/**
	 * Get the user's contact information.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function contacts(): HasOne {
		return $this->hasOne(UserContact::class);
	}

	/**
	 * Get the user's orders
	 */
	public function orders(): HasMany {
		return $this->hasMany(Order::class);
	}

	/**
	 * Get the user's wishlist items
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function wishlist_items(): HasMany {
		return $this->hasMany(Like::class);
	}

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
	 * Check if the user is a seller
	 */
	public function isSeller(): bool {
		return (bool) $this->seller;
	}

	public function canAccessPanel(Panel $panel): bool {
		return $this->is_admin;
	}

	public function getFilamentAvatarUrl(): ?string {
		return public_asset($this->icon());
	}

	/**
	 * Get the user's website URL.
	 * 
	 * @return string|null
	 */
	public function website(): ?string {
		return $this->contacts?->website;
	}

	/**
	 * Get the user's Facebook URL.
	 * 
	 * @return array
	 */
	public function facebook(): ?string {
		return $this->contacts?->facebook;
	}

	/**
	 * Get the user's Instagram URL.
	 * 
	 * @return string|null
	 */
	public function instagram(): ?string {
		return $this->contacts?->instagram;
	}

	/**
	 * Get the user's Twitter URL.
	 * 
	 * @return string|null
	 */
	public function twitter(): ?string {
		return $this->contacts?->twitter;
	}

	/**
	 * Get the user's YouTube URL.
	 * 
	 * @return string|null
	 */
	public function youtube(): ?string {
		return $this->contacts?->youtube;
	}

	/**
	 * Get the user's TikTok URL.
	 * 
	 * @return string|null
	 */
	public function tiktok(): ?string {
		return $this->contacts?->tiktok;
	}

	/**
	 * Get the user's Bluesky URL.
	 * 
	 * @return string|null
	 */
	public function bluesky(): ?string {
		return $this->contacts?->bluesky;
	}

	public function cover(?int $size = 1920) {
		$sizeConversion = [
			1920 => '',
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
