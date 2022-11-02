<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Promotion extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'promotion_price',
        'start_date',
        'end_date'
    ];

    public function food()
    {
        return $this->belongsToMany(Food::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('cover')
              ->width(1920)
              ->sharpen(10);
    }

    public function getImage()
    {
        $mediaItems = $this->getMedia();
        $mediaUrl = null;
        if (count($mediaItems)) {
            $mediaUrl = [
                'original' => $mediaItems[0]->getUrl(),
                'cover'   => $mediaItems[0]->getUrl('cover')
            ];
        }
        return $mediaUrl;
    }
}
