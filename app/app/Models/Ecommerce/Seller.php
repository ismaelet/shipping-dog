<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Design\LogoGroup;
use App\Models\Ecommerce\Seller\Follower;
use App\Models\Ecommerce\Seller\Payment;
use App\Models\Ecommerce\Seller\PaymentRequest;
use App\Models\Ecommerce\Seller\SellerPrice;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Seller class
 */
class Seller extends Model {

    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'sort',
        'credit',
        'earned',
        'payed',
        'status',
        'activation_request_sent',
        'request',
        'custom'
    ];

    /**
     * Delete the seller's related data
     */
    public function delete() {
        $this->designs?->each(function ($design) {
            $design->delete();
        });

        return parent::delete();
    }

    /**
     * Relationship: The user account that owns the seller
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: The the seller's designs
     */
    public function designs(): HasMany {
        return $this->hasMany(Design::class, 'user_id', 'user_id');
    }

    /**
     * Relationship: The the seller's products
     */
    public function products(): HasMany {
        return $this->hasMany(Product::class, 'user_id', 'user_id');
    }

    /**
     * Relationship: The order items sold by the seller
     */
    public function order_items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relationship: The the seller's followers
     */
    public function followers(): HasMany {
        return $this->hasMany(Follower::class, 'follower_id', 'user_id');
    }

    /**
     * Relationship: The the seller's prices
     */
    public function prices(): HasMany {
        return $this->hasMany(SellerPrice::class, 'seller_id', 'id');
    }

    /**
     * Relationship: The the seller's payments
     */
    public function payments(): HasMany {
        return $this->hasMany(Payment::class)->where('other', false);
    }

    /**
     * Get the seller's payments
     */
    public function all_transfers(): HasMany {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the seller's payment request
     */
    public function payment_request(): HasOne {
        return $this->hasOne(PaymentRequest::class);
    }

    /**
     * Get the seller's logo groups
     */
    public function logoGroups(): HasMany {
        return $this->hasMany(LogoGroup::class);
    }

    /**
     * * Get methods
     */
    /**
     * Get the formatted time string
     */
    public function createdAtString(): string {
        return date('d/m/Y', strtotime($this->time));
    }

    /**
     * Get the seller's URL
     */
    public function url(): string {
        return route('seller.show', [$this]);
    }

    /**
     * Check if the seller is followed by the logged user
     */
    public function auth_follower(): HasOne {
        return $this->hasOne(Follower::class, 'user_id', 'id')
            ->where('follower_id', Auth::id());
    }

    public function isFollowed(): bool {
        return $this->auth_follower()->exists();
    }

    /**
     * Get the seller's icon
     */
    public function icon(): string {
        $icon = 'users/icons/' . $this->user_id;

        if (file_exists(public_storage($icon . '.webp'))) {
            return public_asset($icon . '.webp');
        }

        if (file_exists(public_storage($icon . '.jpg'))) {
            return public_asset($icon . '.jpg');
        }

        return public_asset('users/icons/0.jpg');
    }

    public function cover(int $size = 1920): string {
        if (!$this->user) {
            Log::error('Seller user not found', ['seller' => $this->id]);
        }

        return $this->user->cover($size);
    }

    /**
     * ~ Attributes: Seller balance
     */

    /**
     * Get the amount to be payed
     */
    public function toBePayed() {
        return $this->earned - $this->payed + $this->other_transfers;
    }

    /**
     * Get the amount of credit that can be used for the current order.
     * 
     * @return float
     */
    public function usableCredit(): float {
        return $this->toBePayed() - cart()?->usedCredit();
    }

    /**
     * Get the formatted balance
     */
    public function earnedString(): string {
        return EcommerceHelper::formatPrice($this->earned, true);
    }

    public function payedString(): string {
        return EcommerceHelper::formatPrice($this->payed, true);
    }

    public function otherTransferAmountString(): string {
        return EcommerceHelper::formatPrice($this->other_transfers, true);
    }

    public function creditString(): string {
        return EcommerceHelper::formatPrice($this->credit, true);
    }

    public function toBePayedString(): string {
        return EcommerceHelper::formatPrice($this->toBePayed(), true);
    }

    public function updateEarned(): self {
        $this->update([
            'earned' => $this->order_items->where('status', '>=', EcommerceHelper::status('confirmed'))->sum('gain')
        ]);

        return $this;
    }

    /**
     * Update the seller's paid amount
     */
    public function updatePaid(): static {
        $this->update([
            'payed' => round($this->payments()->sum('value'), 2)
        ]);

        return $this;
    }

    public function updateOtherTransfers(): float {
        return round($this->all_transfers->where('other', 1)->sum('value'), 2);
    }

    /**
     * Update the seller's credit
     */
    public function updateCredit(): self {
        $this->update(['credit' => round($this->earnedCredit() - $this->usedOrderCredit() - $this->usedShippingCredit(), 2)]);

        return $this;
    }

    private function earnedCredit(): float {
        return round($this->earned * .1, 2);
    }

    private function usedOrderCredit(): float {
        $usedOrderCredit = $this->order_items()
            ->where('user_id', '=', $this->user_id)
            ->where('credit', '=', 1)
            ->where('status', '!=', EcommerceHelper::status('cancelled'))
            ->selectRaw('SUM((fullProductPrice - price_disc) * quantity) as usedCredit')
            ->value('usedCredit');

        return $usedOrderCredit ? round($usedOrderCredit, 2) : 0;
    }

    private function usedShippingCredit(): float {
        $usedShippingCredit = Order::whereHas('order_items', function ($query) {
            $query->where('user_id', '=', $this->user_id);
        })
            ->where('credit_ship', '=', 1)
            ->where('status', '!=', EcommerceHelper::status('cancelled'))
            ->selectRaw('
			SUM(shipping) AS usedCredit
		')
            ->value('usedCredit');

        return $usedShippingCredit ? round($usedShippingCredit, 2) : 0;
    }

    /**
     * * Query builders
     */
    public static function isAwaiting(): Builder {
        return self::where('status', 0)
            ->where('user_id', '!=', 2)
            ->whereIn('user_id', function ($query) {
                $query->select('user_id')
                    ->from('designs')
                    ->where('status', 1);
            });
    }

    /**
     * * Static methods
     */
    public static function awaitingApproval(): Collection {
        return self::where('status', 0)
            ->where('user_id', '!=', 2)
            ->whereIn('user_id', function ($query) {
                $query->select('user_id')
                    ->from('designs')
                    ->where('status', 1);
            })
            ->get();
    }
}
