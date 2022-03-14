<?php

namespace Helpers\HotelBeds;

use Helpers\HotelBeds\ImportStatic\HotelBedsCategoryDictionaryItem;
use Helpers\HotelBeds\ImportStatic\HotelBedsCountryDictionaryItem;
use Helpers\HotelBeds\ImportStatic\HotelBedsDestinationDictionaryItem;
use Helpers\HotelBeds\ImportStatic\HotelBedsFacilityDictionaryItem;
use Helpers\HotelBeds\ImportStatic\HotelBedsHotelDictionaryItem;
use Helpers\HotelBeds\VO\HotelBedsSearchCode;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HotelBedsHotelFactory
{
    /**
     * @var HotelPriceCalculator
     */
    private $priceCalculator;
    /**
     * @var PointConverter
     */
    private $pointConverter;

    private $balanceRepository;

    private $invoiceAmountService;

    /**
     * HotelBedsHotelFactory constructor.
     * @param HotelPriceCalculator $priceCalculator
     * @param PointConverter $pointConverter
     * @param UserAccountRepository $balanceRepository
     * @param InvoiceAmountService $invoiceAmountService
     */
//    public function __construct(HotelPriceCalculator $priceCalculator, PointConverter $pointConverter, UserAccountRepository $balanceRepository, InvoiceAmountService $invoiceAmountService)
//    {
//        $this->priceCalculator = $priceCalculator;
//        $this->pointConverter = $pointConverter;
//        $this->balanceRepository = $balanceRepository;
//        $this->invoiceAmountService =   $invoiceAmountService;
//    }

    /**
     * @param $apiHotelItem
     * @param $hotelStaticInfo
     * @param $networkId
     * @return HotelBedsHotelListItem
     */
    public function createHotelListItem($apiHotelItem, $hotelStaticInfo, $networkId): HotelBedsHotelListItem
    {

        $rooms = $apiHotelItem['rooms'] ?? null;
        $rooms = $this->createHotelRoomsCollection($rooms, $networkId, $apiHotelItem['currency']);
        $searchCode = '';

        if ($rooms->count()) {
            $searchCode = HotelBedsSearchCode::create($rooms);
        }

        $generalImages = [];
        if (!empty($hotelStaticInfo->images)) {
            $generalImages = collect($hotelStaticInfo->images)->where('imageTypeCode', 'GEN')->values();
        }

        $mainImage = '';
        if (!empty($generalImages)) {
            $mainImage = 'http://photos.hotelbeds.com/giata/original/' . $generalImages[0]['path'];
        }
        return new HotelBedsHotelListItem(
            $apiHotelItem['code'],
            $apiHotelItem['name'],
            $apiHotelItem['categoryCode'],
            $apiHotelItem['categoryName'],
            $apiHotelItem['destinationCode'],
            $apiHotelItem['destinationName'],
            $apiHotelItem['zoneCode'],
            $apiHotelItem['zoneName'],
            $apiHotelItem['latitude'],
            $apiHotelItem['longitude'],
            $apiHotelItem['minRate'],
            $apiHotelItem['maxRate'],
            $apiHotelItem['currency'],
            $apiHotelItem['rooms'] ?? null,
            $apiHotelItem['checkOut'] ?? null,
            $apiHotelItem['checkIn'] ?? null,
            $searchCode,
            (!empty($hotelStaticInfo)) ? $hotelStaticInfo->address : null,
            (!empty($hotelStaticInfo)) ? $hotelStaticInfo->accommodation_type_code : null,
            (!empty($hotelStaticInfo)) ? $hotelStaticInfo->images : null,
            $mainImage,
            (!empty($hotelStaticInfo)) ? $hotelStaticInfo->ratingStars : null,
            (!empty($apiHotelItem['facilities'])) ? $apiHotelItem['facilities'] : null,
            $apiHotelItem['description'],
            $apiHotelItem['phones'] ?? null,




        );
        //dd($rooms);


    }

    /**
     * @param $apiHotelItem
     * @param HotelBedsHotelStaticInfo|null $hotelStaticInfo
     * @param $networkId
     * @return HotelBedsHotelDetail
     */
    public function createHotelDetailItem($apiHotelItem, ?HotelBedsHotelStaticInfo $hotelStaticInfo, $networkId): HotelBedsHotelDetail
    {
        return new HotelBedsHotelDetail(
            $apiHotelItem['code'],
            $apiHotelItem['name'],
            $apiHotelItem['categoryCode'],
            $apiHotelItem['categoryName'],
            $apiHotelItem['destinationCode'],
            $apiHotelItem['destinationName'],
            $apiHotelItem['zoneCode'],
            $apiHotelItem['zoneName'],
            $apiHotelItem['latitude'],
            $apiHotelItem['longitude'],
            $rooms = $this->createHotelRoomsCollection($apiHotelItem['rooms'] ?? null, $networkId, $apiHotelItem['currency']),
            $apiHotelItem['checkOut'] ? Carbon::createFromFormat('!Y-m-d', $apiHotelItem['checkOut']) : null,
            $apiHotelItem['checkIn'] ? Carbon::createFromFormat('!Y-m-d', $apiHotelItem['checkIn']) : null,
            (!empty($hotelStaticInfo)) ? $hotelStaticInfo->facilities : null,
            (!empty($hotelStaticInfo)) ? $hotelStaticInfo->address : null,
            (!empty($hotelStaticInfo)) ? $hotelStaticInfo->accommodationType : null,
            (!empty($hotelStaticInfo)) ? $hotelStaticInfo->imagesArray : null,
            (!empty($hotelStaticInfo)) ? $hotelStaticInfo->stars : null
        );
    }

    /**
     * @param UserContext $context
     * @param HotelBedsHotelStaticInfo|null $hotelStaticInfo
     * @param $apiHotelItem
     * @return HotelBedsRoomOffer
     */
    public function createHotelRoomOfferItem(UserContext $context, ?HotelBedsHotelStaticInfo $hotelStaticInfo, $apiHotelItem): HotelBedsRoomOffer
    {
        $room = collect();
        if (!empty($apiHotelItem['rooms'])) {
            $room = collect($apiHotelItem['rooms'])->first();
        }
        $currency = $apiHotelItem['currency'];
        $rate = collect($room['rates'])->first();
        $cancellationPolicies = collect($rate['cancellationPolicies'] ?? null)->first();
        $sellingRate = $rate['sellingRate'] ?? null;

        $roomOfferItem = new HotelBedsRoomOffer(
            new HotelBedsHotelInfo(
                $apiHotelItem['code'],
                $apiHotelItem['name'],
                $apiHotelItem['categoryCode'],
                $apiHotelItem['categoryName'],
                $apiHotelItem['destinationCode'],
                $apiHotelItem['destinationName'],
                $apiHotelItem['zoneCode'],
                $apiHotelItem['zoneName'],
                $apiHotelItem['latitude'],
                $apiHotelItem['longitude'],
                (!empty($hotelStaticInfo)) ? $hotelStaticInfo->facilities : null,
                (!empty($hotelStaticInfo)) ? $hotelStaticInfo->address : null,
                (!empty($hotelStaticInfo)) ? $hotelStaticInfo->accommodationType : null,
                (!empty($hotelStaticInfo)) ? $hotelStaticInfo->imagesArray : null,
                (!empty($hotelStaticInfo)) ? $hotelStaticInfo->stars : null
            ),
            new HotelBedsRoomItem(
                $room['code'],
                $room['name'],
                new HotelBedsRate(
                    $rate['rateKey'] ?? null,
                    $rate['rateClass'],
                    new Points($this->pointConverter->toPoints($rate['net'], $currency, $context->networkId)),
                    ($sellingRate) ? new Points($this->pointConverter->toPoints($rate['sellingRate'], $currency, $context->networkId)) : null,
                    $rate['hotelMandatory'] ?? null,
                    $rate['allotment'] ?? null,
                    $rate['paymentType'],
                    $rate['packaging'],
                    $rate['boardCode'],
                    $rate['boardName'],
                    $rate['rooms'],
                    $rate['adults'],
                    $rate['children'],
                    $apiHotelItem['checkIn'],
                    $apiHotelItem['checkOut'],
                    (!empty($cancellationPolicies)) ? new HotelBedsHotelRateCancellationPolicy(
                        new Points($this->pointConverter->toPoints($cancellationPolicies['amount'], $currency, $context->networkId)),
                        $cancellationPolicies['from']
                    ) : null
                )
            )
        );
        $roomOfferItem->hotelInfo->currency = $currency;

        return $roomOfferItem;
    }

    /**
     * @param $apiHotelItem
     * @return HotelBedsHotelDictionaryItem
     */
    public function createHotelBedsStaticsHotel($apiHotelItem): HotelBedsHotelDictionaryItem
    {
        $generalImages = [];
        if (!empty($apiHotelItem['images'])) {
            $generalImages = collect($apiHotelItem['images'])->where('imageTypeCode', 'GEN')->values();
        }
        $facilityCodes = [];
        if (!empty($apiHotelItem['facilities'])) {
            $facilityCodes = collect($apiHotelItem['facilities'])->pluck('facilityCode');
        }
        $dictionaryItem = new HotelBedsHotelDictionaryItem(
            $apiHotelItem['code'],
            $apiHotelItem['name']['content'],
            $apiHotelItem['countryCode'],
            $apiHotelItem['destinationCode'],
            !empty($apiHotelItem['categoryCode']) ? $apiHotelItem['categoryCode'] : null,
            (!empty($apiHotelItem['description'])) ? $apiHotelItem['description']['content'] : null,
            $apiHotelItem['stateCode'],
            $apiHotelItem['zoneCode'],
            $apiHotelItem['categoryGroupCode'] ?? null,
            $apiHotelItem['chainCode'] ?? null,
            !empty($apiHotelItem['accommodationTypeCode']) ? $apiHotelItem['accommodationTypeCode'] : null,
            $apiHotelItem['address']['content'] ?? null,
            $apiHotelItem['coordinates']['longitude'] ?? null,
            $apiHotelItem['coordinates']['latitude'] ?? null,
            $apiHotelItem['postalCode'] ?? null,
            $apiHotelItem['city']['content'],
            $apiHotelItem['email'] ?? null,
            $generalImages,
            $apiHotelItem['S2C'] ?? null,
            $apiHotelItem['phones'] ?? null,
            $apiHotelItem['rooms'] ?? null,
            $apiHotelItem['facilities'] ?? null,
            $apiHotelItem['terminals'] ?? null,
            $apiHotelItem['issues'] ?? null,
            $apiHotelItem['wildcards'] ?? null,
            $apiHotelItem['web'] ?? null,
            $apiHotelItem['lastUpdate'],
            $apiHotelItem['ranking'],
        );
        $dictionaryItem->facilityCodes = $facilityCodes;
        return $dictionaryItem;
    }

    /**
     * @param $apiHotelItem
     * @return HotelBedsCountryDictionaryItem
     */
    public function createHotelBedsStaticsCountry($apiHotelItem): HotelBedsCountryDictionaryItem
    {
        return new HotelBedsCountryDictionaryItem(
            $apiHotelItem['code'],
            $apiHotelItem['description']['content']
        );

    }

    /**
     * @param $apiHotelItem
     * @return HotelBedsFacilityDictionaryItem
     */
    public function createHotelBedsStaticsFacility($apiHotelItem): HotelBedsFacilityDictionaryItem
    {
        return new HotelBedsFacilityDictionaryItem(
            $apiHotelItem['code'],
            !empty($apiHotelItem['description']) ? $apiHotelItem['description']['content'] : null
        );

    }

    /**
     * @param $apiHotelItem
     * @return HotelBedsCategoryDictionaryItem
     */
    public function createHotelBedsStaticsCategory($apiHotelItem): HotelBedsCategoryDictionaryItem
    {
        return new HotelBedsCategoryDictionaryItem(
            $apiHotelItem['code'],
            $apiHotelItem['simpleCode']
        );

    }

    /**
     * @param $apiHotelItem
     * @return HotelBedsDestinationDictionaryItem
     */
    public function createHotelBedsStaticsDestination($apiHotelItem): HotelBedsDestinationDictionaryItem
    {

        return new HotelBedsDestinationDictionaryItem(
            $apiHotelItem['code'],
            $apiHotelItem['name']['content'],
            $apiHotelItem['countryCode'],
            $apiHotelItem['isoCode']
        );

    }

    /**
     * @param $apiBookingItem
     * @param $networkId
     * @return HotelBedsOrderItem
     */
    public function createHotelBookingItem($apiBookingItem, $networkId): HotelBedsOrderItem
    {
        $apiHotelItem = $apiBookingItem['hotel'];
        $rooms = $apiHotelItem['rooms'] ?? null;
        $rooms = $this->createHotelRoomsCollection($rooms, $networkId, $apiHotelItem['currency']);

        return new HotelBedsOrderItem(
            new HotelBedsHotelInfo(
                $apiHotelItem['code'],
                $apiHotelItem['name'],
                $apiHotelItem['categoryCode'],
                $apiHotelItem['categoryName'],
                $apiHotelItem['destinationCode'],
                $apiHotelItem['destinationName'],
                $apiHotelItem['zoneCode'],
                $apiHotelItem['zoneName'],
                $apiHotelItem['latitude'],
                $apiHotelItem['longitude'],
                null,
                null,
                null,
                null,
                null,
                $apiHotelItem['checkIn'],
                $apiHotelItem['checkOut']
            ),
            new HotelBedsOfferItem(
                $apiBookingItem['reference'],
                $apiBookingItem['clientReference'],
                $apiBookingItem['creationDate'],
                $apiBookingItem['status'],
                $apiBookingItem['modificationPolicies'] ?? null,
                $apiBookingItem['remark'] ?? null,
                $apiBookingItem['invoiceCompany'] ?? null,
                0,
                $apiBookingItem['currency'],
                $this->createHotelOfferRateItem($rooms->first()->rates->first())
            )
        );

    }

    /**
     * @param $roomsData
     * @param $networkId
     * @param $currency
     * @return \Illuminate\Support\Collection|HotelBedsRoomDetail[]
     */
    public function createHotelRoomsCollection($roomsData, $networkId, $currency)
    {
        $result = [];

        if ($roomsData) {
            foreach ($roomsData as $room) {
                $result[] = $this->createHotelRoomItem($room, $networkId, $currency);
            }
        }

        return collect($result);
    }

    /**
     * @param $roomData
     * @param $networkId
     * @param $currency
     * @return HotelBedsRoomDetail
     */
    public function createHotelRoomItem($roomData, $networkId, $currency): HotelBedsRoomDetail
    {
        $rates = $roomData['rates'] ?? null;

        return new HotelBedsRoomDetail(
            $roomData['code'],
            $roomData['name'],
            $this->createRoomRatesCollection($rates, $networkId, $currency)
        );
    }

    /**
     * @param $ratesData
     * @param $networkId
     * @param $currency
     * @return \Illuminate\Support\Collection
     */
    public function createRoomRatesCollection($ratesData, $networkId, $currency): Collection
    {
        $result = [];

        if ($ratesData) {
            foreach ($ratesData as $rate) {
                $result[] = $this->createHotelRateItem($rate, $networkId, $currency);
            }
        }

        return collect($result);
    }

    /**
     * @param $rate
     * @param $networkId
     * @param $currency
     * @return HotelBedsRate
     */
    public function createHotelRateItem($rate, $networkId, $currency): HotelBedsRate
    {
        $cancellationPolicies = $rate['cancellationPolicies'] ?? null;
        $sellingRate = $rate['sellingRate'] ?? null;
        return new HotelBedsRate(
            $rate['rateKey'] ?? null,
            $rate['rateClass'],
            $rate['net'],
            $sellingRate,
            $rate['hotelMandatory'] ?? null,
            $rate['allotment'] ?? null,
            $rate['paymentType'],
            $rate['packaging'],
            $rate['boardCode'],
            $rate['boardName'],
            $rate['rooms'],
            $rate['adults'],
            $rate['children'],
            null,
            null,
            $this->createRoomRatesCancellationPoliciesCollection($cancellationPolicies, $currency, $networkId)
        );
    }

    public function createHotelOfferRateItem(HotelBedsRate $rate): HotelBedsHotelOfferRateItem
    {

        return new HotelBedsHotelOfferRateItem (
            $rate->rateClass,
            $rate->net,
            $rate->sellingRate,
            $rate->paymentType,
            $rate->packaging,
            $rate->boardCode,
            $rate->boardName,
            $rate->rooms,
            $rate->adults,
            $rate->children,
            $rate->cancellationPolicies
        );
    }

    /**
     * @param $cancellationPolicies
     * @param $currency
     * @param $networkId
     * @return Collection
     */
    public function createRoomRatesCancellationPoliciesCollection($cancellationPolicies, $currency, $networkId): Collection
    {
        $result = [];

        if ($cancellationPolicies) {
            foreach ($cancellationPolicies as $cancellationPolicy) {
                $result[] = $this->createHotelRateCancellationPolicyItem($cancellationPolicy, $currency, $networkId);
            }
        }

        return collect($result);
    }

    /**
     * @param $cancellationPolicy
     * @param $currency
     * @param $networkId
     * @return HotelBedsHotelRateCancellationPolicy
     */
    public function createHotelRateCancellationPolicyItem($cancellationPolicy, $currency, $networkId): HotelBedsHotelRateCancellationPolicy
    {
        return new HotelBedsHotelRateCancellationPolicy(
            $cancellationPolicy['amount'],
            $cancellationPolicy['from']
        );
    }
}
