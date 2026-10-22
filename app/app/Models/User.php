<?php

namespace App\Models;

use App\Helpers\EcommerceHelper;
use App\Helpers\FileHelper;
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
use Illuminate\Support\Facades\Vite;

/**
 * User class
 */
class User extends Authenticatable implements FilamentUser, HasAvatar {

    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'google_id',
        'avatar',
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
     * Attribute: Get whether the user is an admin
     */
    public function isAdmin(): Attribute {
        return Attribute::make(
            get: fn(): bool => $this->admin_level,
        );
    }

    /**
     * Attribute: Get whether the user is a superadmin
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
     * Get the user's contact information
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
     */
    public function wishlist_items(): HasMany {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the user's full name
     */
    public function fullName(): string {
        $fullName = [];

        if ($this->first_name) {
            $fullName[] = $this->first_name;
        }

        if ($this->last_name) {
            $fullName[] = $this->last_name;
        }

        return implode(' ', $fullName);
    }

    /**
     * Check if the user is a seller
     */
    public function isSeller(): bool {
        return (bool) $this->seller;
    }

    /**
     * Whether the user can access the Filament admin panel
     */
    public function canAccessPanel(Panel $panel): bool {
        return $this->is_admin;
    }

    public function getFilamentAvatarUrl(): ?string {
        return $this->icon();
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

        if (FileHelper::exists($cover . '.webp')) {
            return FileHelper::storageAsset($cover . '.webp');
        }

        if (FileHelper::exists($cover . '.jpg')) {
            return FileHelper::storageAsset($cover . '.jpg');
        }

        return Vite::image('user/cover.webp');
    }

    public function icon(): string {
        $icon = 'users/icons/' . $this->id;

        if (FileHelper::exists($icon . '.webp')) {
            return FileHelper::storageAsset($icon . '.webp');
        }

        if (FileHelper::exists($icon . '.jpg')) {
            return FileHelper::storageAsset($icon . '.jpg');
        }

        if ($this->avatar) {
            return $this->avatar;
        }

        return Vite::image('user/icon.webp');
    }
}
