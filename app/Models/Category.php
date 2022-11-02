<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function food()
    {
        return $this->belongsToMany(Food::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('ship')
              ->width(150)
              ->sharpen(10);
    }

    public function getImage()
    {
        $mediaItems = $this->getMedia();
        $mediaUrl = null;
        if (count($mediaItems)) {
            $mediaUrl = [
                'original' => $mediaItems[0]->getUrl(),
                'ship' => $mediaItems[0]->getUrl('ship')
            ];
        }
        return $mediaUrl;
    }
}
