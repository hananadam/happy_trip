<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{

	public $table = "media";
    public $timestamps = true;
    protected $primaryKey = 'id';
}