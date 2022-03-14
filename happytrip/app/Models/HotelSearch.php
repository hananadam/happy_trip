<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Helpers\HotelBeds\Config\HotelBedsConfigManager;
use Helpers\HotelBeds\HotelBedsAdapter;
use Helpers\HotelBeds\HotelBedsExceptionHandler;
use Helpers\HotelBeds\HotelBedsHotelFactory;

use Str;
use Auth;
use Cache;

class HotelSearch extends Model
{
    use HasFactory;

    /**
     * Prevent Eloquent from overriding uuid with `lastInsertId`.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'json',
    ];


    public function createSearch($data,$ages,$bed)
    {
        $search = new HotelSearch;
        $search->uuid = Str::uuid()->toString();
        $search->destination = $data['destinationCode'];
        $search->check_in = date('Y-m-d', strtotime($data['checkIn']));
        $search->check_out = date('Y-m-d', strtotime($data['checkOut']));
        $content = array(
            'rooms' => $data['room_num'],
            'adults' =>  $data['adults_num'],
            'children' => $data['children_num'] ?? 0,
            'ages' => $ages ? explode(',' ,$ages) : [],
            'bed' => $bed ? array($bed) : [],
        );
        $search->content = serialize($content);

        $search->user_ip = \Request::ip();

        if (Auth::check()) {
            $search->user_id = Auth()->user->id;
        }
        Cache::put('search_uuid', $search->uuid, config('session.lifetime'));
        $search->save();
        return $search;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(HotelSearchResult::class, 'hotel_search_id');
    }
}
