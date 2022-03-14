<?php

namespace Helpers\HotelBeds;

//use App\Infrastructure\Versioned\Versioned;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Integer;

class HotelBedsOrderInput
{
    protected static $version = 1;
    public $rateKey;
    public $holderFirstName;
    public $holderLastName;
    /**
     * @var HotelBedsRoomInput[]|Collection
     */
    public $rooms;
    public $remark;

    public $clientReference;

    /**
     * HotelBedsOrderInput constructor.
     * @param $rateKey
     * @param $holderFirstName
     * @param $holderLastName
     * @param $rooms
     * @param $remark
     */
    public function __construct($rateKey,$holderFirstName, $holderLastName,  $rooms, $remark, $clientReference)
    {
        $this->rateKey = $rateKey;
        $this->holderFirstName = $holderFirstName;
        $this->holderLastName = $holderLastName;
        $this->rooms = $rooms;
        $this->remark = $remark;
        $this->clientReference = $clientReference;
    }


    /**
     * @param $data
     * @return HotelBedsOrderInput|null
     */
    public static function fromArray($data)
    {
        return new self(
            $data['rate_key'],
            $data['holder_first_name'],
            $data['holder_last_name'],
            collect($data['rooms'])->map(function ($room) {
                return HotelBedsRoomInput::fromArray($room);
            }),
            $data['remark'],
            $data['client_reference']
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'rate_key' => $this->rateKey,
            'holder_first_name' => $this->holderFirstName,
            'holder_last_name' => $this->holderLastName,
            'rooms' => $this->rooms,
            'remark' => $this->remark,
        ];
    }


}
