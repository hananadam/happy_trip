<?php

namespace App\Models\Hotelbeds;

use App\Models\HotelSearch;
use Cache;
use Carbon\Carbon;
use Helpers\HotelBeds\Config\HotelBedsConfigManager;
use Helpers\HotelBeds\HotelBedsAdapter;
use Helpers\HotelBeds\HotelBedsExceptionHandler;
use Helpers\HotelBeds\HotelBedsHotelFactory;
use Helpers\HotelBeds\HotelBedsHotelsAvailabilityRequest;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $connection = 'hotelbeds';

    protected $table = 'hotels';

    protected $casts = [
        'created_at' => 'datetime',
        'images' => 'json',
        'phones' => 'json',
        'rooms' => 'json',
        'facilities' => 'json',
        'terminals' => 'json',
        'issues' => 'json',
        'wildcards' => 'json',
    ];

    public function getMainImage()
    {
        $mainImage = '';
        if (!empty($this->images)) {
            $mainImage = 'http://photos.hotelbeds.com/giata/original/' . $this->images[0]['path'];
        }
        return $mainImage;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'countryCode', 'code');
    }

    public function getPrice()
    {
        $search = HotelSearch::where(['uuid' => Cache::get('search_uuid')])->first();
        $roomData = unserialize($search->content);

        $adults = $roomData['adults'];
        $children = $roomData['children'];
        $roomCount = $roomData['rooms'];

        $req = new HotelBedsHotelsAvailabilityRequest(
            Carbon::make($search->check_in),
            Carbon::make($search->check_out),
            $roomCount,
            $adults,
            $children ?? 0,
            null,
            null,
            [$this->code],
            $search->destination,
            null,
        );

        $this->adapter = new HotelBedsAdapter(
            new HotelBedsExceptionHandler(),
            new HotelBedsConfigManager(),
            new HotelBedsHotelFactory(),
        );

        $availability = $this->adapter->getAvailabilityByHotels($req);

        $price = $availability->pluck('minRateInPoints')->first();

        return $price;
    }
}
