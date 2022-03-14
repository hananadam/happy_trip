<?php

namespace App\Models\Hotelbeds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Destination extends Model
{
    use Searchable;

    protected $connection = 'hotelbeds';
    protected $table = 'destinations';
    protected $primaryKey = 'id';

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'countryCode', 'code');
    }
}
