<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Route;

/**
 * Admin section class
 */
class AdminSection extends Model {

	/**
	 * The attributes that are mass assignable
	 */
	protected $fillable = ['category_id', 'name', 'resource', 'action', 'order', 'hidden', 'for_supervisors'];

	/**
	 * Get the category
	 */
	public function category(): BelongsTo {
		return $this->belongsTo(AdminCategory::class);
	}

	/**
	 * Get the url
	 */
	public function routeName(): string {
		return implode('.', ['admin', $this->resource, $this->action]);
	}

	/**
	 * Get the url
	 */
	public function url(): string {
		$routeName = $this->routeName();

		if (Route::has($routeName)) {
			return route($routeName);
		}

		return '/' . str_replace('.', '/', $routeName);
	}

	/**
	 * Get the section by slug
	 */
	public static function findBySlug(string $routeName): ?self {
		$routeName = explode('.', $routeName);
		$resource = $routeName[1];
		$action = $routeName[2];

		return self::where('resource', $resource)
			->where('action', $action)
			->first();
	}
}
