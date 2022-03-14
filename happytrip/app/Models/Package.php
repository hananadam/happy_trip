<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia; 
use Spatie\MediaLibrary\InteractsWithMedia;

class Package extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public $table = "packages";
    public $timestamps = true;
    protected $primaryKey = 'id';
   
    protected $casts = [
        'options' => 'json',
        'policy' => 'json'
    ];

    public function packagedetail()
    {
        return $this->hasMany(PackageDetail::class, 'package_id', 'id');

    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function images(){

        return $this->morphMany(Image::class,'imageable');
    }


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->sharpen(10);

        $this->addMediaConversion('medium-size')
              ->width(368)
              ->height(232)
              ->sharpen(10);
    }

    public function registerMediaCollections() : void
    {
        $this->addMediaCollection('main')->singleFile();
        $this->addMediaCollection('packages');
    }
}
