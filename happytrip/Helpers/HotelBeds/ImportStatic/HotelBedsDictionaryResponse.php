<?php

namespace Helpers\HotelBeds\ImportStatic;

class HotelBedsDictionaryResponse
{
    public $items;
    public $total;

    public function __construct($items, $total)
    {

        $this->items = $items;
        $this->total = $total;
    }
}
