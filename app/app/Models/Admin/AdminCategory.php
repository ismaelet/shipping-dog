<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Admin category class
 */
class AdminCategory extends Model
{
    /**
     * The attributes that are mass assignable
     */
    protected $fillable = ['name', 'slug', 'order', 'for_supervisors'];

    /**
     * * Relationships
     */
    public function sections(): HasMany
    {
        return $this->hasMany(AdminSection::class, 'category_id');
    }
}
