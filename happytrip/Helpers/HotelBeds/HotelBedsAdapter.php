<?php

namespace Helpers\HotelBeds;

use App\Models\Hotelbeds\Hotel;
use Helpers\HotelBeds\Api\Exceptions\HotelBedsApiSystemException;
use Helpers\HotelBeds\Api\HotelBedsApi;
use Helpers\HotelBeds\Config\HotelBedsConfigManager;
use Helpers\HotelBeds\ImportStatic\HotelBedsDictionaryResponse;
use Helpers\HotelBeds\VO\HotelBedsSearchCode;
use App\Repositories\HotelBeds\HotelBedsRepository;
use App\Services\Auth\UserContext;
use Carbon\Carbon;
use App\Models\Rating;

class HotelBedsAdapter
{
    private $api;
    private $exceptionHandler;
    private $configManager;
    private $hotelFactory;
    private $repository;

    /**
     * HotelBedsService constructor.
     * @param HotelBedsExceptionHandler $hotelBedsExceptionHandler
     * @param HotelBedsConfigManager $configManager
     * @param HotelBedsHotelFactory $hotelFactory
     */
    public function __construct(HotelBedsExceptionHandler $hotelBedsExceptionHandler,
                                HotelBedsConfigManager $configManager,
                                HotelBedsHotelFactory $hotelFactory
    )
    {
        $this->configManager = $configManager;
        $this->api = $this->api();
        $this->exceptionHandler = $hotelBedsExceptionHandler;
        $this->hotelFactory = $hotelFactory;
//        $this->repository = $repository;
    }
    /**
     * @return client Ip
    */
    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }

    /**
     * @return available hotels today
    */
    public function currentAvailableHotels($hotelsCodes, $destinationCode)
    {
        $req = new HotelBedsHotelsAvailabilityRequest(
            Carbon::now(),
            Carbon::tomorrow(),
            1,
            2,
            0,
            null,
            null,
            $hotelsCodes,
            $destinationCode,
            null,
        );

        return $this->getAvailabilityByHotels($req);
    }
    /**
     * @return get hotel reviews
    */
    public function getHotelReviews($hotelCode,$hotelReview=0)
    {
        $filters = ['hotel' => $hotelCode];
        $reviews = $this->getRatecomments($filters);
        $reviews = collect($reviews['rateComments']);
        $reviews = $reviews->where('hotel', $hotelCode);

        $rating = Rating::where('code', $hotelCode)->get();
        $ratingData = [];
        foreach ($rating as $key => $rate) {
            $ratingData[$key]['id'] = $rate->id;
            $ratingData[$key]['code'] = $rate->code;
            $ratingData[$key]['customer_name'] = $rate->customer->name;
            $ratingData[$key]['rate'] = $rate->rate;
            $ratingData[$key]['comment'] = $rate->comment;
            $ratingData[$key]['date'] = $rate->created_at;
        }
        $allReviews = array_merge($reviews->toArray(), $ratingData);
        $reviews_count = count($allReviews);
        if($hotelReview == 1){
            return array_merge($reviews->toArray(), $ratingData);
        }
        else{
            return $reviews_count;
        }

    }
    /**
     * @return HotelBedsApi|mixed
     */
    protected function api()
    {
        return $this->api ?: $this->api = new HotelBedsApi($this->configManager->getConfig());
    }

    /**
     * @param $filters
     * @return mixed
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getCountries($filters)
    {
        $apiData = $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getCountries($filters);
        });
        $result = [];

        if (!empty($apiData['countries'])) {
            foreach ($apiData['countries'] as $data) {
                $result[] = $this->hotelFactory->createHotelBedsStaticsCountry($data);
            }
        }
        return new HotelBedsDictionaryResponse(
            collect($result),
            $apiData['total']
        );
    }

    /**
     * @param $filters
     * @return HotelBedsDictionaryResponse
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getCategories($filters)
    {
        $apiData = $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getCategories($filters);
        });
        $result = [];
        if (!empty($apiData['categories'])) {
            foreach ($apiData['categories'] as $data) {
                $result[] = $this->hotelFactory->createHotelBedsStaticsCategory($data);
            }
        }

        return new HotelBedsDictionaryResponse(
            collect($result),
            $apiData['total']
        );
    }

    /**
     * @param $filters
     * @return mixed
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getDestinations($filters)
    {
        $apiData = $this->api->getDestinations($filters);
        $result = [];

        if (!empty($apiData['destinations'])) {
            foreach ($apiData['destinations'] as $data) {
                if (!isset($data['name'])) {
                    continue;
                }
                $result[] = $this->hotelFactory->createHotelBedsStaticsDestination($data);
            }
        }
        return new HotelBedsDictionaryResponse(
            collect($result),
            $apiData['total']
        );
    }

    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getCurrencies($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getCurrencies($filters);
        });
    }

    /**
     * @param $filters
     * @return mixed
     */
    public function getHotels($filters): HotelBedsDictionaryResponse
    {

        $apiData = $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getHotels($filters);
        });
        $result = [];
        if (!empty($apiData['hotels'])) {
            foreach ($apiData['hotels'] as $data) {
                $result[] = $this->hotelFactory->createHotelBedsStaticsHotel($data);
            }
        }

        return new HotelBedsDictionaryResponse(
            collect($result),
            $apiData['total']
        );
    }

    /**
     * @param $hotelCode
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getHotelDetails($hotelCode, $filters)
    {
        return $this->exceptionHandler->handle(function () use ($hotelCode, $filters) {
            return $this->api->getHotelDetails($hotelCode, $filters);
        });
    }

    public function getAccommodations($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getAccommodations($filters);
        });
    }

    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getBoards($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getBoards($filters);
        });
    }


    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getChains($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getChains($filters);
        });
    }

    /**
     * @param $filters
     * @return HotelBedsDictionaryResponse
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getFacilities($filters)
    {
        $apiData = $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getFacilities($filters);
        });
        $result = [];
        if (!empty($apiData['facilities'])) {
            foreach ($apiData['facilities'] as $data) {
                $result[] = $this->hotelFactory->createHotelBedsStaticsFacility($data);
            }
        }

        return new HotelBedsDictionaryResponse(
            collect($result),
            $apiData['total']
        );
    }

    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getFacilitygroups($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getFacilitygroups($filters);
        });
    }

    public function getIssues($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getIssues($filters);
        });
    }

    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getPromotions($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getPromotions($filters);
        });
    }

    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getRooms($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getRooms($filters);
        });
    }

    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getSegments($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getSegments($filters);
        });
    }

    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getTerminals($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getTerminals($filters);
        });
    }

    public function getImagetypes($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getImagetypes($filters);
        });
    }

    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getGroupcategories($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getGroupcategories($filters);
        });
    }

    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getRatecomments($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getRatecomments($filters);
        });
    }

    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getRatecommentdetails($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getRatecommentdetails($filters);
        });
    }

    /**
     * @param $filters
     * @return mixed
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getLanguages($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getLanguages($filters);
        });
    }


    public function getAvailabilityByHotels(HotelBedsHotelsAvailabilityRequest $request)
    {
        return $this->api->getAvailabilityByHotels($request);
       // dd($apiData);
        $hotelsList = collect();

        if (!empty($apiData['hotels']['hotels'])) {
            $hotelCodes = collect($apiData['hotels']['hotels'])->pluck('code')->toArray();
            $hotelStaticInfoList = Hotel::whereIn('code', $hotelCodes)->limit(count($hotelCodes))->get();
            foreach ($apiData['hotels']['hotels'] as $data) {
                $hotelStaticInfo = $hotelStaticInfoList->first(function($item) use ($data) {
                    return $item->code == $data['code'];
                });
                $filters = [];
                $getHotel = $this->getHotelDetails($data['code'], $filters);
                $facilities = [];
                $facilityCodes = [];
                $phones = [];

                //dd($getHotel);
                if (!empty($getHotel['hotel']['facilities'])) {

                    $facilityCodes = collect($getHotel['hotel']['facilities'])->pluck('facilityCode')->toArray();
                    $facilities = $getHotel['hotel']['facilities'];


                }
                if (!empty($getHotel['hotel']['phones'])) {

                    $phones = $getHotel['hotel']['phones'];

                }
                $description='';
                if (!empty($getHotel['hotel']['description']['content'])) {
                    $description = $getHotel['hotel']['description']['content'];
                }
               // dd($facilities,$description);
                //$data['facilitiesCodes'] = $facilityCodes;
                $data['facilities'] = $facilities;
                $data['phones'] = $phones;
                $data['description'] = $description;
                $data['checkOut'] = $apiData['hotels']['checkOut'];
                $data['checkIn'] = $apiData['hotels']['checkIn'];
               // $data['rooms'] = $apiData['hotels']['hotels']['rooms'];
                $hotelsList->push($this->hotelFactory->createHotelListItem($data, $hotelStaticInfo, $request->networkId));

            }
        }
      // dd($hotelsList);
        return $hotelsList;
    }

    /**
     * @param $requestBody
     * @param $networkId
     * @return HotelBedsHotelDetail
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function checkBookingRates($requestBody, $networkId)
    {
        $apiData = $this->exceptionHandler->handle(function () use ($requestBody, $networkId) {
            return $this->api->checkBookingRates($requestBody);
        });
        $hotelStaticInfo = $this->repository->getHotelStaticInfo($apiData['hotel']['code']);

        return $this->hotelFactory->createHotelDetailItem($apiData['hotel'], $hotelStaticInfo, $networkId);
    }

    public function checkRatesBySearchCode(HotelBedsSearchCode $searchCode, $networkId)
    {
        $apiData = $this->exceptionHandler->handle(function () use ($searchCode, $networkId) {
            return $this->api->checkRates($searchCode);
        });
        if (!empty($apiData['hotel'])) {
            $hotelStaticInfo = $this->repository->getHotelStaticInfo($apiData['hotel']['code']);
            return $this->hotelFactory->createHotelDetailItem($apiData['hotel'], $hotelStaticInfo, $networkId);
        }

        return null;
    }

    public function checkRatesForRoomOffer(UserContext $context, $offerCode)
    {
        $apiData = $this->exceptionHandler->handle(function () use ($offerCode, $context) {
            return $this->api->checkRatesForRoomOffer($offerCode);
        });

        if (!empty($apiData['hotel'])) {
            $hotelStaticInfo = $this->repository->getHotelStaticInfo($apiData['hotel']['code']);
            return $this->hotelFactory->createHotelRoomOfferItem($context, $hotelStaticInfo, $apiData['hotel']);
        }

        return null;
    }

    /**
     * @param $requestBody
     * @return mixed
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function bookings($requestBody)
    {
        $apiData = $this->exceptionHandler->handle(function () use ($requestBody) {
            return $this->api->bookings($requestBody);
        });

        if (!is_array($apiData)) {
            throw new HotelBedsApiSystemException('response empty');
        }

        return $this->hotelFactory->createHotelBookingItem($apiData['booking'], 1);
    }

    /**
     * @param $booking_reference
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getBookingsDetail($booking_reference)
    {
        return $this->exceptionHandler->handle(function () use ($booking_reference) {
            return $this->api->getBookingsDetail($booking_reference);
        });
    }

    /**
     * @param $filters
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function getBookings($filters)
    {
        return $this->exceptionHandler->handle(function () use ($filters) {
            return $this->api->getBookings($filters);
        });
    }

    /**
     * @param $params
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function deleteBookings($params)
    {
        return $this->exceptionHandler->handle(function () use ($params) {
            return $this->api->deleteBookings($params);
        });
    }

    /**
     * @param $body
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function putBookings($body)
    {
        return $this->exceptionHandler->handle(function () use ($body) {
            return $this->api->putBookings($body);
        });
    }

    /**
     * @param $body
     * @return array
     * @throws \App\Exceptions\HotelBeadsException
     */
    public function availability($body)
    {
        return $this->exceptionHandler->handle(function () use ($body) {
            return $this->api->availability($body);
        });
    }
}
