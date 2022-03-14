<?php

namespace Helpers\HotelBeds\ImportStatic;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class HotelBedsHotelDictionaryItem
{

    public $code;
    public $name;
    public $countryCode;
    public $destinationCode;
    public $categoryName;
    public $categoryCode;
    public $description;
    public $stateCode;
    public $zoneCode;
    public $categoryGroupCode;
    public $chainCode;
    public $accommodationTypeCode;
    public $address;
    public $longitude;
    public $latitude;
    public $postalCode;
    public $city;
    public $email;
    /** @var Collection */
    public $generalImages;
    public $facilityCodes;
    public $rating;
    public $phones;
    public $rooms;
    public $facilities;
    public $terminals;
    public $issues;
    public $wildcards;
    public $web;
    public $lastUpdate;
    public $ranking;

    /**
     * HotelBedsHotelDictionaryItem constructor.
     * @param $code
     * @param $name
     * @param $countryCode
     * @param $destinationCode
     * @param $categoryCode
     * @param $description
     * @param $stateCode
     * @param $zoneCode
     * @param $categoryGroupCode
     * @param $chainCode
     * @param $accommodationTypeCode
     * @param $address
     * @param $longitude
     * @param $latitude
     * @param $postalCode
     * @param $city
     * @param $email
     * @param $generalImages
     * @param $rating
     */
    public function __construct($code,
                                $name,
                                $countryCode,
                                $destinationCode,
                                $categoryCode,
                                $description,
                                $stateCode,
                                $zoneCode,
                                $categoryGroupCode,
                                $chainCode,
                                $accommodationTypeCode,
                                $address,
                                $longitude,
                                $latitude,
                                $postalCode,
                                $city,
                                $email,
                                $generalImages,
                                $rating,
                                $phones,
                                $rooms,
                                $facilities,
                                $terminals,
                                $issues,
                                $wildcards,
                                $web,
                                $lastUpdate,
                                $ranking
    )
    {

        $this->code = $code;
        $this->name = $name;
        $this->countryCode = $countryCode;
        $this->destinationCode = $destinationCode;
        $this->categoryCode = $categoryCode;
        $this->description = $description;
        $this->stateCode = $stateCode;
        $this->zoneCode = $zoneCode;
        $this->categoryGroupCode = $categoryGroupCode;
        $this->chainCode = $chainCode;
        $this->accommodationTypeCode = $accommodationTypeCode;
        $this->address = $address;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->email = $email;
        $this->generalImages = collect($generalImages);
        $this->rating = $rating;
        $this->phones = collect($phones);
        $this->rooms = collect($rooms);
        $this->facilities = collect($facilities);
        $this->terminals = collect($terminals);
        $this->issues = collect($issues);
        $this->wildcards = collect($wildcards);
        $this->web = $web;
        $this->lastUpdate = Carbon::parse($lastUpdate);
        $this->ranking = $ranking;
    }

}
