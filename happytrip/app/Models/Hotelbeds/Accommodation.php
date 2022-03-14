<?php

namespace App\Models\Hotelbeds;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $connection = 'hotelbeds';

    protected $casts = [
        'created_at' => 'datetime'
    ];
}
