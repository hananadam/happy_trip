<?php

namespace Helpers\HotelBeds\RedemptionTransaction;

use Helpers\Hotel\Responses\HotelApiServiceCancelReservationResponse;
use Helpers\Hotel\Responses\HotelApiServiceReserveResponse;
use Helpers\HotelBeds\HotelBedsOrderInput;
use Helpers\HotelBeds\HotelBedsOrderItem;
use Helpers\Invoice\InvoiceAmount;
use Helpers\RedemptionTransaction\RedemptionTransactionType;

class HotelBedsRedemptionTransactionType extends RedemptionTransactionType
{
    /**
     * @var \Helpers\Invoice\InvoiceAmount
     */
    public $invoice;
    /**
     * @var \Helpers\Hotel\HotelInfo
     */
    public $hotelInfo;
    /**
     * @var \Helpers\Hotel\HotelOrderInput
     */
    public $orderInput;
    /**
     * @var HotelApiServiceReserveResponse
     */
    public $reserveResponse;
    /**
     * @var HotelApiServiceCancelReservationResponse
     */
    public $cancellationResponse;

    /**
     * HotelBedsRedemptionTransactionType constructor.
     * @param InvoiceAmount $invoice
     * @param HotelBedsOrderInput $orderInput
     * @param null $reserveResponse
     * @param null $cancellationResponse
     */
    public function __construct(InvoiceAmount $invoice, HotelBedsOrderInput $orderInput, $reserveResponse = null, $cancellationResponse = null)
    {
        $this->invoice = $invoice;
        $this->orderInput = $orderInput;
        $this->reserveResponse = $reserveResponse;
        $this->cancellationResponse = $cancellationResponse;
    }

    /**
     * @param $data
     * @return HotelBedsRedemptionTransactionType
     */
    public static function fromArray($data)
    {
        return   new self(
                InvoiceAmount::fromVersionedArray($data['invoice']),
                HotelBedsOrderInput::fromVersionedArray($data['order_input']),
                HotelBedsOrderItem::fromVersionedArray($data['reserve_response'])
            );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'invoice' => $this->invoice,
            'order_input' => $this->orderInput,
            'reserve_response' => $this->reserveResponse
        ];
    }

    /**
     * @param HotelApiServiceCancelReservationResponse $response
     */
    public function cancel(HotelApiServiceCancelReservationResponse $response)
    {
        $this->cancellationResponse = $response;
    }

    /**
     * @param HotelApiServiceReserveResponse $response
     */
    public function reserve(HotelApiServiceReserveResponse $response)
    {
        $this->reserveResponse = $response;
    }
}
