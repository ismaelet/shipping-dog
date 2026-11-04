<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Coupon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'mod_us',
        'mod_them',
        'flat_us',
        'flat_them',
        'permanent',
        'shipping',
        'only_one',
        'one_per_acccount',
        'time_start',
        'time_end',
    ];

    /**
     * Get the seller on whose products the coupon can be used.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Check if the coupon is valid.
     *
     * @return string|false Returns a string with the error code if the coupon is not valid, or false if it is valid.
     */
    public function isValid(): string|false
    {
        if (strtotime($this->time_start) > strtotime('now')) {
            return 'Il codice sconto non è ancora attivo';
        }

        if (strtotime($this->time_end.' +1 day') < strtotime('now')) {
            return 'Il codice sconto è scaduto';
        }

        if ($this->one_per_account && UsedCoupon::where('coupon_id', $this->id)->exists()) {
            return 'Il codice sconto è già stato utilizzato';
        }

        if ($this->one_per_account && Auth::guest()) {
            return 'Devi essere loggato per utilizzare questo codice sconto';
        }

        return false;
    }
}
