<?php

namespace Helpers\HotelBeds;

class HotelBedsHotelListItem
{
    public $checkOut;
    public $checkIn;
    public $code;
    public $name;
    public $categoryCode;
    public $categoryName;
    public $destinationCode;
    public $destinationName;
    public $zoneCode;
    public $zoneName;
    public $latitude;
    public $longitude;
    public $minRateInPoints;
    public $maxRateInPoints;
    public $currency;
    public $rooms;
    public $priceInPoints;
    public $searchCode;
    public $address;
    public $accommodationTypeCode;
    public $image;
    public $mainImage;
    public $facilities;
    public $ratingStars;
    public $description;
    public $phones;
   
    /**
     * HotelBedsHotelListItem constructor.
     * @param $code
     * @param $name
     * @param $categoryCode
     * @param $categoryName
     * @param $destinationCode
     * @param $destinationName
     * @param $zoneCode
     * @param $zoneName
     * @param $latitude
     * @param $longitude
     * @param $minRateInPoints
     * @param $maxRateInPoints
     * @param $currency
     * @param $rooms
     * @param $checkOut
     * @param $checkIn
     * @param $searchCode
     * @param $address
     * @param $accommodationTypeCode
     * @param $image
     * @param $mainImage
     * @param $facilities
     * @param $ratingStars
     * @param $description
     
     */
    public function __construct($code,
                                $name,
                                $categoryCode,
                                $categoryName,
                                $destinationCode,
                                $destinationName,
                                $zoneCode,
                                $zoneName,
                                $latitude,
                                $longitude,
                                $minRateInPoints,
                                $maxRateInPoints,
                                $currency,
                                $rooms,
                                $checkOut,
                                $checkIn,
                                $searchCode,
                                $address,
                                $accommodationTypeCode,
                                $image,
                                $mainImage,
                                $ratingStars,
                                $facilities,
                                $description,
                                $phones

                                
    )
    {
        

        $this->code = $code;
        $this->name = $name;
        $this->categoryCode = $categoryCode;
        $this->categoryName = $categoryName;
        $this->destinationCode = $destinationCode;
        $this->destinationName = $destinationName;
        $this->zoneCode = $zoneCode;
        $this->zoneName = $zoneName;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->minRateInPoints = $minRateInPoints;
        $this->maxRateInPoints = $maxRateInPoints;
        $this->currency = $currency;
        $this->rooms = $rooms;
        $this->checkOut = $checkOut;
        $this->checkIn = $checkIn;
        $this->searchCode = $searchCode;
        $this->address = $address;
        $this->accommodationTypeCode = $accommodationTypeCode;
        $this->image = $image;
        $this->mainImage = $mainImage;
        $this->ratingStars = $ratingStars;
        $this->facilities = $facilities;
        $this->description = $description;
        $this->phones = $phones;
    }
}
