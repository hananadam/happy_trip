<?php


namespace Helpers\HotelBeds\Request;


class CodesRequest
{
    private string $codes;

    private string $destination;

    /**
     * CodesRequest constructor.
     * @param string $codes
     * @param string $destination
     */
    public function __construct(
        string $codes,
        string $destination
    )
    {
        $this->codes = $codes;
        $this->destination = $destination;
    }



}
