<?php

namespace Helpers\HotelBeds;

use App\Infrastructure\Versioned\Versioned;
use Illuminate\Support\Collection;


class HotelBedsRoomInput
{
    protected static $version = 1;

    /**
     * @var HotelBedsPaxInput[]|Collection
     */
    public $paxes;

    /**
     * HotelBedsRoomInput constructor.
     * @param HotelBedsPaxInput[]|Collection $paxes
     */
    public function __construct($paxes)
    {
        $this->paxes =  $paxes;
    }

    /**
     * @param $data
     * @return HotelBedsRoomInput|null
     */
    public static function fromArray($data)
    {
        if (!$data) {
            return null;
        }

        return new self(
            collect($data['paxes'])->map(function ($pax) {
                return HotelBedsPaxInput::fromArray($pax);
            })
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ['paxes' => $this->paxes];
    }

}
