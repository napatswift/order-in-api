<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Manager extends User implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $table = 'users';

    public static function boot()
    {
        parent::boot();
 
        static::addGlobalScope(function ($query) {
            $query
                ->where('is_employee', false)
                ->where('is_manager', true);
        });
    }

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class, 'owner_id');
    }    
    
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('avatar')
              ->width(60)
              ->sharpen(10);
    }

    public function getImage()
    {
        $mediaItems = $this->getMedia();
        $mediaUrl = null;
        if (count($mediaItems)) {
            $mediaUrl = [
                'original' => $mediaItems[0]->getUrl(),
                'avatar'   => $mediaItems[0]->getUrl('avatar')
            ];
        }
        return $mediaUrl;
    }
}
