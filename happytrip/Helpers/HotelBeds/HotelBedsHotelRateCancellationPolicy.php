<?php

namespace Helpers\HotelBeds;


class HotelBedsHotelRateCancellationPolicy
{
    public $amount;
    public $from;

    public function __construct($amount,
                                $from
                                )
    {
        $this->amount = $amount;
        $this->from = $from;
    }
}
