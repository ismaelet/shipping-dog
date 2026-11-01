<?php

namespace App\Actions\Portfolio;

use App\Models\Ecommerce\Product;
use Illuminate\Support\Facades\Auth;

class CustomProductAccess
{
    public static function authorize(Product $product): void
    {
        abort_unless(Auth::user()?->seller && (int) $product->user_id === (int) Auth::id(), 403);
        $product->loadMissing('design_with_trashed');
        abort_unless($product->isCustom(), 404);
    }

    public static function authorizeTemporary(string $tempId): void
    {
        abort_unless(Auth::user()?->seller && preg_match('/^temp_[a-zA-Z0-9]+$/', $tempId)
            && session('custom-product-uploads.'.$tempId) === Auth::id(), 403);
    }
}
