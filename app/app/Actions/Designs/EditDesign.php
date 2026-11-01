<?php

namespace App\Actions\Designs;

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Design\DesignTag;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;

class EditDesign
{
    public static function authorize(Design $design): void
    {
        abort_unless(Auth::check() && (int) $design->user_id === (int) Auth::id() && $design->status, 403);
    }

    public static function product(int $id): Product
    {
        $product = Product::with('design', 'type.colors', 'type.positions', 'colors', 'color', 'mockup')->findOrFail($id);
        abort_unless($product->design, 404);
        static::authorize($product->design);
        abort_unless((int) $product->user_id === (int) Auth::id(), 403);

        return $product;
    }

    public static function background(Design $design, string $color): void
    {
        static::authorize($design);
        $design->update(['color' => $color]);
        static::invalidate($design);
    }

    public static function defaultColor(Design $design, int $colorId): void
    {
        static::authorize($design);
        DB::transaction(function () use ($design, $colorId) {
            $design->update(['default_color_id' => $colorId]);
            foreach (Product::where('design_id', $design->id)->with('type.colors', 'colors')->get() as $product) {
                if ($product->type->colors->contains($colorId) && $product->colors->contains($colorId)) {
                    $product->update(['color_id' => $colorId]);
                }
            }
        });
        static::invalidate($design);
    }

    public static function status(Product $product, bool $enabled): void
    {
        static::product($product->id)->update(['status' => $enabled]);
    }

    public static function preview(Product $product, ?int $color = null, ?int $position = null): string
    {
        if (! Storage::disk('public')->exists('designs/'.$product->design_id.'.png') && ! $product->design->custom) {
            return Vite::image('placeholder.webp');
        }

        return $product->image(1024, $color, $position);
    }

    public static function invalidate(Design $design): void
    {
        Storage::disk('public')->delete(['designs/previews/'.$design->id.'.webp', 'designs/previews/'.$design->id.'_m.webp']);
        Product::where('design_id', $design->id)->with('type')->get()->each->deletePreviewImages();
    }

    public static function tags(Design $design, ?string $value): void
    {
        DB::transaction(function () use ($design, $value) {
            Design::whereKey($design->id)->lockForUpdate()->firstOrFail();
            $previous = array_filter(explode(',', $design->tags()->value('tags') ?? ''));
            $tags = array_slice(array_values(array_unique(array_filter(array_map('trim', explode(',', strtolower(str_replace('#', '', $value ?? ''))))))), 0, 10);
            foreach (array_diff($previous, $tags) as $tag) {
                Tag::where('tag', $tag)->decrement('count');
                Tag::where('tag', $tag)->where('count', '<=', 0)->delete();
            }
            foreach (array_diff($tags, $previous) as $tag) {
                $record = Tag::firstOrCreate(['tag' => $tag], ['count' => 0]);
                $record->increment('count');
            }
            if ($tags === []) {
                $design->tags()->delete();
            } else {
                DesignTag::updateOrCreate(['design_id' => $design->id], ['tags' => implode(',', $tags)]);
            }
        });
    }

    public static function createProduct(Design $design, ProductType $type): Product
    {
        static::authorize($design);
        abort_if($type->hidden, 404);

        return DB::transaction(function () use ($design, $type) {
            Design::whereKey($design->id)->lockForUpdate()->firstOrFail();
            $existing = Product::where('design_id', $design->id)->where('type_id', $type->id)->first();
            if ($existing) {
                return $existing;
            }
            $type->loadMissing('colors', 'positions');
            abort_if($type->colors->isEmpty() || $type->positions->isEmpty(), 422, 'Configurazione del capo incompleta.');
            $product = Product::create([
                'name' => $design->name, 'design_id' => $design->id, 'user_id' => $design->user_id,
                'type_id' => $type->id, 'color_id' => $type->colors->first()->id,
                'positions' => $type->positions->pluck('id')->implode(','),
            ]);
            $product->colors()->sync($type->colors->modelKeys());

            return $product;
        });
    }
}
