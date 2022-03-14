<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelSearchResult extends Model
{
    protected $fillable = [
        'code',
        'content',
        'hotel_search_id'
    ];

    public function hotelSearch(): BelongsTo
    {
        return $this->belongsTo(HotelSearch::class);
    }

    public function getContentAttribute($content)
    {
        return json_decode($content, true);
    }
}
