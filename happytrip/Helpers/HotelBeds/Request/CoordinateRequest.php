<?php


namespace Helpers\HotelBeds\Request;


class CoordinateRequest
{
    private float $lat;

    private float $lng;

    /**
     * CoordinateRequest constructor.
     * @param float $lat
     * @param float $lng
     */
    public function __construct(
        float $lat,
        float $lng
    )
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

}
