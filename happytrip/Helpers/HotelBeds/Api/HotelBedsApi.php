<?php

namespace Helpers\HotelBeds\Api;

use Helpers\HotelBeds\Api\Exceptions\HotelBedsApiSystemException;
use Helpers\HotelBeds\Config\HotelBedsConfig;
use Helpers\HotelBeds\HotelBedsHotelsAvailabilityRequest;
use Helpers\HotelBeds\VO\HotelBedsSearchCode;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Kevinrob\GuzzleCache\Storage\LaravelCacheStorage;
use GuzzleHttp\HandlerStack;

class HotelBedsApi
{
    private $apiBaseUrl;

    private $secureApiBaseUrl;

    private $apiHeaderAccept;

    private $apiHeaderAcceptEncoding;

    private $apiKey;

    private $apiSecret;

    /**
     * @var Client
     */
    private $client;

    private $clientReference;

    /**
     * @var HandlerStack
     */
    private $stack;

    public function __construct(HotelBedsConfig $config){

        $this->apiKey  = $config->apiKey;
        $this->apiSecret  = $config->apiSecret;
        $this->apiBaseUrl  = $config->apiBaseUrl;
        $this->secureApiBaseUrl  = $config->secureApiBaseUrl;
        $this->apiHeaderAccept  = $config->apiHeaderAccept;
        $this->apiHeaderAcceptEncoding  = $config->apiHeaderAcceptEncoding;
        $this->clientReference  = $config->clientReference;

        $stack = HandlerStack::create();
        $stack->push(
            new CacheMiddleware(
                new PrivateCacheStrategy(
                    new LaravelCacheStorage(
                        Cache::store('file')
                    )
                )
            ),
            'cache-hotelBeds'
        );

        $this->client = new Client(['handler' => $stack]);
    }
    /**
     * @return string
     */
    private function generateSignature()
    {
        return hash('sha256', $this->apiKey . $this->apiSecret . time());
    }

    /**
     * @param $headers
     * @return array
     */
    private function prepareHeaders($headers)
    {
        $headers['Api-key'] = $this->apiKey;
        $headers['Content-Type'] = $this->apiHeaderAccept;
        $headers['Accept'] = $this->apiHeaderAccept;
        $headers['Accept-Encoding'] = $this->apiHeaderAcceptEncoding;
        $headers['X-Signature'] = $this->generateSignature();
        // dd($headers);
        return $headers;
    }

    /**
     * @param $url
     * @param array $params
     * @param array $headers
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendGetRequest($url, $params = [], $headers = [])
    {
        $headers = $this->prepareHeaders($headers);

        try {
            $response = $this->client->request('GET', $url, ['query' => $params, 'headers' => $headers]);
        } catch (\GuzzleHttp\Exception\RequestException $exception) {
            throw new HotelBedsApiSystemException($exception);
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * @param $url
     * @param array $body
     * @param array $headers
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendPostRequest($url, $body = [], $headers = [])
    {
        $headers = $this->prepareHeaders($headers);

//        dd($body);
        try {
            $response = $this->client->request('POST', $url, ['json' => $body, 'headers' => $headers]);
        } catch (\GuzzleHttp\Exception\RequestException $errorResponseException) {
            $body = json_decode($errorResponseException->getResponse()->getBody()->getContents(), true);
            dd($body);
            throw new HotelBedsApiSystemException($errorResponseException);
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * @param $url
     * @param array $params
     * @param array $headers
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendDeleteRequest($url, $params = [], $headers = [])
    {
        $headers = $this->prepareHeaders($headers);

        try {
            $response = $this->client->request('DELETE', $url, ['params' => $params, 'headers' => $headers]);
        } catch (\GuzzleHttp\Exception\RequestException $errorResponseException) {
            throw new HotelBedsApiSystemException($errorResponseException);
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * @param $url
     * @param array $body
     * @param array $headers
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendPutRequest($url, $body = [], $headers = [])
    {
        $headers = $this->prepareHeaders($headers);

        try {
            $response = $this->client->request('PUT', $url, ['json' => $body, 'headers' => $headers]);
        } catch (\GuzzleHttp\Exception\RequestException $errorResponseException) {
            throw new HotelBedsApiSystemException($errorResponseException);
        }
        return json_decode($response->getBody(), true);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCountries($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . 'hotel-content-api/1.0/locations/countries?', $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDestinations($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . 'hotel-content-api/1.0/locations/destinations?', $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAccommodations($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . 'hotel-content-api/1.0/types/accommodations', $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBoards($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . 'hotel-content-api/1.0/types/boards', $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCategories($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . 'hotel-content-api/1.0/types/categories', $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getChains($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . 'hotel-content-api/1.0/types/chains', $filters);
    }

    /**
     * @param $filters
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHotels($filters)
    {
      //  dd($this->apiBaseUrl . 'hotel-content-api/1.0/hotels', $filters);
        return $this->sendGetRequest($this->apiBaseUrl . 'hotel-content-api/1.0/hotels', $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHotelDetails($hotelCode, $filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/hotels/{$hotelCode}/details?", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCurrencies($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/currencies?", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFacilities($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/facilities", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFacilitygroups($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/facilitygroups", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIssues($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/issues", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLanguages($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/languages?", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPromotions($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/promotions?", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRooms($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/rooms", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSegments($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/segments", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTerminals($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/terminals", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getImagetypes($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/imagetypes", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRatecomments($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/ratecomments", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRatecommentdetails($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/ratecommentdetails", $filters);
    }

    /**
     * @param $filters
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getGroupcategories($filters)
    {
        return $this->sendGetRequest($this->apiBaseUrl . "hotel-content-api/1.0/types/groupcategories", $filters);
    }

    /**
     * @param $request
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAvailabilityByHotels(HotelBedsHotelsAvailabilityRequest $request)
    {
        $data = [
            'stay' => [
                'checkIn' => $request->checkIn->format('Y-m-d'),
                'checkOut' => $request->checkOut->format('Y-m-d'),
            ],
            'occupancies' => [
                [
                    'rooms' => $request->roomsCount,
                    'adults' => $request->adultsCount,
                    'children' => $request->childrenCount,
                ]
            ],
        ];

        if($request->childrenCount) {
            for ($x = 0; $x < $request->childrenCount; $x++) {
                $paxes[] = [
                    "type"  =>  "CH",
					"age"   =>  $x+1
                ];
            }
            $data['occupancies'][0]['paxes'] = $paxes;
        }

        if ($request->hotelCodes) {
            $data['hotels'] = [
                'hotel' => $request->hotelCodes,
            ];
        } elseif ($request->destination) {
            $data['destination'] = [
             'code' => $request->destination
            ];
        } else {
            $data['geolocation'] = [
                'longitude' => $request->geolocation->longitude,
                'latitude' => $request->geolocation->latitude,
                'radius' => $request->geolocation->radius,
                'unit' => $request->geolocation->unit,
                'secondaryLatitude' => $request->geolocation->secondaryLatitude,
                'secondaryLongitude' => $request->geolocation->secondaryLongitude,
            ];
        }

        if ($request->minCategory || $request->maxCategory) {
            $data['filter'] = [
                'minCategory' => $request->minCategory,
                'maxCategory' => $request->maxCategory,
            ];
        }

        return $this->sendPostRequest($this->apiBaseUrl . "hotel-api/1.0/hotels", $data);
    }

    /**
     * @param HotelBedsSearchCode $searchCode
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkRates(HotelBedsSearchCode $searchCode)
    {
        $requestBody = [
            'rooms' => []
        ];

        foreach ($searchCode->rateKeys as $rateKey) {
            $requestBody['rooms'][] = [
                'rateKey' => $rateKey,
            ];
        }

        return $this->sendPostRequest($this->apiBaseUrl . "hotel-api/1.0/checkrates", $requestBody);
    }

    /**
     * @param string $rateKey
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkRatesForRoomOffer(string $rateKey)
    {
        $requestBody = [
            'rooms' => []
        ];

        $requestBody['rooms'] = [ [
                'rateKey' => $rateKey,
            ]];

        return $this->sendPostRequest($this->apiBaseUrl . "hotel-api/1.0/checkrates", $requestBody);
    }

    /**
     * @param string $rateKey
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkBookingRates(string $rateKey)
    {
        $requestBody = [
            'rooms' => [
                [
                    'rateKey' => $rateKey
                ]
            ]
        ];

        return $this->sendPostRequest($this->apiBaseUrl . "hotel-api/1.0/checkrates", $requestBody);
    }

    /**
     * @param $requestBody
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function bookings($requestBody)
    {
        $data =  [
            'holder'      => [
                'name' => $requestBody->holderFirstName,
                'surname' => $requestBody->holderLastName
            ],
            'rooms' => [[
                'rateKey' =>$requestBody->rateKey,
                'paxes' => count($requestBody->rooms) ? $requestBody->rooms : null
            ]],
            'clientReference' => $this->clientReference,
            'remark' => $requestBody->remark,
        ];
        // dd(json_encode($data));

        return  $this->sendPostRequest((isset($requestBody->paymentData) ? $this->secureApiBaseUrl :$this->apiBaseUrl) . "hotel-api/1.0/bookings", $data);
    }

    /**
     * @param $booking_reference
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBookingsDetail($booking_reference)
    {
        return $this->sendGetRequest($this->apiBaseUrl."hotel-api/1.0/bookings/{$booking_reference}");
    }

    /**
     * @param $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBookings($params)
    {
        return $this->sendGetRequest($this->apiBaseUrl."hotel-api/1.0/bookings", $params);
    }

    /**
     * @param $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteBookings($params)
    {
        return $this->sendDeleteRequest($this->apiBaseUrl."hotel-api/1.0/bookings/{$params['bookingReference']}", $params);
    }

    /**
     * @param $body
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function putBookings($body)
    {
        return $this->sendPutRequest($this->apiBaseUrl."hotel-api/1.0/bookings/{$body['bookingReference']}", $body);
    }

    /**
     * @param $body
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function availability($body)
    {
        return $this->sendGetRequest($this->apiBaseUrl.'hotel-content-api/1.0/hotels/53141/details?language=ENG&useSecondaryLanguage=False');
    }
     public function getAvailabilityByHotelCode($body)
    {
        return $this->sendGetRequest($this->apiBaseUrl.'hotel-api/1.0//hotels/53141/details?language=ENG&useSecondaryLanguage=False');
    }
}
