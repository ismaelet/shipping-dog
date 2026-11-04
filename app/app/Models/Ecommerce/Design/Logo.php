<?php

namespace App\Models\Ecommerce\Design;

use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    protected $fillable = [];

    /**
     * Get the image path for the logo
     */
    public function image(): string
    {
        if (FileHelper::exists($url = 'shops/logos/'.$this->id.'.webp')) {
            return FileHelper::storageAsset($url);
        }

        return FileHelper::storageAsset('shops/logos/'.$this->id.'.png');
    }

    /**
     * Get the custom preview image path for the logo
     */
    public function customPreview(int $typeId): ?string
    {
        if (FileHelper::exists($url = 'shops/logos/'.$typeId.'-'.$this->id.'.jpg')) {
            return FileHelper::storageAsset($url);
        }

        return null;
    }
}
