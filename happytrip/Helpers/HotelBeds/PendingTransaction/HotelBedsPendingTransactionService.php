<?php

namespace Helpers\HotelBeds\PendingTransaction;

use Helpers\HotelBeds\Api\Responses\CheckRateResponse;
use Helpers\HotelBeds\HotelBedsOrderInput;
use Helpers\Invoice\InvoiceAmount;
use Helpers\Order\PendingTransaction\CashTransactionEntity;
use Helpers\Order\PendingTransaction\CashTransactionStatus;
use Helpers\Item\PendingTransaction\PendingTransactionItemType;
use App\Services\Auth\UserContext;

class HotelBedsPendingTransactionService
{
    /**
     * @param UserContext $context
     * @param InvoiceAmount $invoice
     * @param CheckRateResponse $hotelOffer
     * @param HotelBedsOrderInput $orderInput
     * @return CashTransactionEntity
     */
    public function create(UserContext $context, InvoiceAmount $invoice,   CheckRateResponse $hotelOffer, HotelBedsOrderInput $orderInput)
    {
        //we should create here with ::create method and write event
        $transaction = new CashTransactionEntity;

        $transaction->id = null;
        $transaction->user_id = $context->userId;
        $transaction->type = PendingTransactionItemType::HOTEL;
        $transaction->status = CashTransactionStatus::PENDING;

        $transaction->serialized_data = json_encode(
            new PendingTransactionHotelBedsType(
                $context,
                $invoice,
                $hotelOffer,
                $orderInput
            )
        );

        $transaction->save();

        return $transaction;
    }
}
