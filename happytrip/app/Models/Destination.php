<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Destination extends Model
{
    use HasFactory;

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
