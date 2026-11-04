<?php

namespace App\Models\Ecommerce\Seller;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'birthdate',
        'birthplace',
        'birthprovince',
        'cf',
        'national_id',
        'address',
        'city',
        'province',
        'zip',
        'seller_id',
        'paypal',
        'total',
        'status',
    ];

    /**
     * First name accessor
     */
    public function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst(strtolower(($value))),
        );
    }

    /**
     * Last name accessor
     */
    public function lastName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst(strtolower(($value))),
        );
    }

    /**
     * Brithprovince accessor
     */
    public function birthprovince(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
        );
    }

    /**
     * Province accessor
     */
    public function province(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
        );
    }

    /**
     * CF accessor
     */
    public function cf(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
        );
    }

    /**
     * National ID accessor
     */
    public function nationalId(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
        );
    }
}
