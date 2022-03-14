<?php

namespace Helpers\HotelBeds\RedemptionTransaction;

use Helpers\Hotel\Projector\HotelOrderProjector;
use Helpers\HotelBeds\Projector\HotelBedsOrderProjector;
use Helpers\HotelBeds\HotelBedsOrderInput;
use Helpers\Invoice\InvoiceAmount;
use Helpers\RedemptionReference;
use Helpers\RedemptionSource;
use Helpers\Transactions\CreateRedemptionTransactionRequest;
use Helpers\Transactions\LegacyRedemptionTransactionService;
use App\Services\Auth\UserContext;

class HotelBedsRedemptionService
{
    /**
     * @var LegacyRedemptionTransactionService
     */
    private $itemTransactionService;
    /**
     * @var HotelOrderProjector
     */
    private $projector;

    /**
     * HotelBedsRedemptionService constructor.
     * @param LegacyRedemptionTransactionService $itemTransactionService
     * @param HotelBedsOrderProjector $projector
     */
    public function __construct(LegacyRedemptionTransactionService $itemTransactionService, HotelBedsOrderProjector $projector)
    {
        $this->itemTransactionService = $itemTransactionService;
        $this->projector = $projector;
    }

    /**
     * @param UserContext $context
     * @param InvoiceAmount $invoice
     * @param HotelBedsOrderInput $orderInput
     * @param null $hotelReservationResponse
     * @return mixed|null
     */
    public function redeem(UserContext $context, InvoiceAmount $invoice, HotelBedsOrderInput $orderInput,  $hotelReservationResponse = null)
    {
        $id = $this->itemTransactionService->create(
            new CreateRedemptionTransactionRequest
            (
                $context,
                $invoice->priceInPoints,
                $items = [],
                $source = RedemptionSource::ONLINE_CATALOG,
                $reference = RedemptionReference::BLU_TRAVEL_HOTEL,
                $orderInput->countryId ?? $context->countryId,
                $areaId = null,
                $cityId = null,
                $address1 = null,
                $address2 = null,
                $postalCode = null,
                $email = null,
                $pointsInCash = $invoice->pointsInCash,
                $cashPayment = $invoice->payInCashUsd,
                $notes = json_encode(
                    new HotelBedsRedemptionTransactionType(
                        $invoice,
                        $orderInput,
                        $hotelReservationResponse,
                        null
                    )
                )
            )
            , LegacyRedemptionTransactionService::CLASS_HOTEL
        );


        return $id;
    }
}
