<?php

namespace Helpers\KiwiApi\Services;

use GuzzleHttp\Exception\GuzzleException;
use Helpers\KiwiApi\Client;
use Helpers\KiwiApi\Request\SearchMultiRequest;
use Helpers\KiwiApi\Request\SearchRequest;
use Helpers\KiwiApi\Request\SearchRoundRequest;

/**
 * Class Flights
 * Doc: https://docs.kiwi.com/locations/
 *
 * @package Helpers\KiwiApi\Model
 */
class Flights implements IService
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
    public function searchByQuery(SearchRequest $request)
    {
        return $this->client->get('v2/search', $request->__toArray());
    }
    /**
     * @param $data
     * @return mixed
     * @throws GuzzleException
     */

     public function searchByRoundQuery(SearchRoundRequest $request)
    {
        return $this->client->get('v2/search', $request->__toArray());
    }
    /**
     * @param $data
     * @return mixed
     * @throws GuzzleException
     */
    public function searchMultiByQuery(SearchMultiRequest $request)
    {
        return $this->client->postWithKey('v2/flights_multi', $request->__toArray(), Client::MULTI_KEY);
    }
}
