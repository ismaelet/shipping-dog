<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'full_name',
        'address',
        'number',
        'city',
        'zip',
        'region',
        'country',
        'email',
        'phone',
        'txnID',
    ];

    /**
     * Attribute: get the full address
     */
    public function fullAddress(): Attribute
    {
        return Attribute::make(
            get: function (): ?array {
                $nameLine = $this->full_name;
                $addressLine = $this->address;
                $addressLine = $this->number ? $addressLine.', '.$this->number : $addressLine;
                $cityLine = $this->zip.' '.$this->city;
                $cityLine = $this->region ? $cityLine.' ('.$this->region.')' : $cityLine;
                $lines = array_filter([$nameLine, $addressLine, $cityLine]);

                return empty($lines) ? null : $lines;
            }
        );
    }

    /**
     * Relationship: get the address' cart
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * Get the customer's contacts
     */
    public function contacts(): string
    {
        return implode('<br>', array_filter([$this->phone, $this->email]));
    }
}
