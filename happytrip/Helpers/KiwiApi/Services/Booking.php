<?php

namespace Helpers\KiwiApi\Services;

use GuzzleHttp\Exception\GuzzleException;
use Helpers\KiwiApi\Client;
use Helpers\KiwiApi\Request\BookRequest;
use Helpers\KiwiApi\Request\CheckRequest;

/**
 * Class Flights
 * Doc: https://docs.kiwi.com/locations/
 *
 * @package Helpers\KiwiApi\Model
 */
class Booking implements IService
{
    private $client;

    /**
     * Service constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $data
     * @return mixed
     * @throws GuzzleException
     */
    public function searchByQuery(CheckRequest $request)
    {
        return $this->client->get('/v2/booking/check_flights', $request->__toArray());
    }

    /**
     * @param $data
     * @return mixed
     */
    public function book(BookRequest $request)
    {
        return $this->client->post('/v2/booking/save_booking', $request->__toArray());
    }
}
