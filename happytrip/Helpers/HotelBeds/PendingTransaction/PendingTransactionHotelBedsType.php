<?php

namespace Helpers\HotelBeds\PendingTransaction;

use Helpers\Hotel\HotelReservationService;
use Helpers\HotelBeds\Api\Responses\CheckRateResponse;
use Helpers\HotelBeds\HotelBedsOrderInput;
use Helpers\Invoice\InvoiceAmount;
use Helpers\Order\PendingTransaction\Types\PendingTransactionType;
use App\Services\Auth\UserContext;
use App\Services\TransClass;

class PendingTransactionHotelBedsType extends PendingTransactionType
{
    /**
     * @var \App\Services\Auth\UserContext
     */
    private $context;
    /**
     * @var \Helpers\Invoice\InvoiceAmount
     */
    private $invoice;
    /**
     * @var \Helpers\Hotel\HotelOfferItem
     */
    private $hotelOffer;
    /**
     * @var \Helpers\Hotel\HotelOrderInput
     */
    private $orderInput;

    /**
     * PendingTransactionHotelBedsType constructor.
     * @param UserContext $context
     * @param InvoiceAmount $invoice
     * @param CheckRateResponse $hotelOffer
     * @param HotelBedsOrderInput $orderInput
     */
    public function __construct(UserContext $context, InvoiceAmount $invoice, CheckRateResponse $hotelOffer,   HotelBedsOrderInput $orderInput)
    {

        $this->context = $context;
        $this->invoice = $invoice;
        $this->hotelOffer = $hotelOffer;
        $this->orderInput = $orderInput;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'context' => $this->context,
            'invoice' => $this->invoice,
            'hotel_offer' => $this->hotelOffer,
            'order_input' => $this->orderInput,
        ];
    }

    /**
     * @param $id
     * @return object|null
     * @throws \App\Exceptions\RedemptionException
     */
    public function redeem($id)
    {
        /**
         * @var $service HotelReservationService
         */
        $service = app(HotelReservationService::class);
        return $service->reserve($this->context, $this->hotelOffer,$this->invoice(), $this->orderInput);
    }

    /**
     * @return int
     */
    public function redemptionType()
    {
        return TransClass::HOTEL;
    }

    /**
     * @return string
     */
    public function description()
    {
        return 'Hotel Reservation';
    }

    /**
     * @param $data
     * @return PendingTransactionHotelBedsType
     */
    public static function fromArray($data)
    {
        return new self(
            UserContext::fromArray($data['context']),
            InvoiceAmount::fromVersionedArray($data['invoice']),
            HotelBedsOrderInput::fromArray($data['hotel_info']['response']),
            HotelBedsOrderInput::fromArray($data['order_input'])
        );
    }

    /**
     * @return InvoiceAmount
     */
    public function invoice()
    {
        return $this->invoice;
    }

    /**
     * @return UserContext
     */
    public function context(): UserContext
    {
        return $this->context;
    }
}
