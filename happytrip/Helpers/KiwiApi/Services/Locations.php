<?php

namespace Helpers\KiwiApi\Services;

use Helpers\KiwiApi\Client;
use Helpers\KiwiApi\Request\LocationRequest;

/**
 * Class Locations
 * Doc: https://docs.kiwi.com/locations/
 *
 * @package Helpers\KiwiApi\Model
 */
class Locations implements IService
{
    private Client $client;

    /**
     * Service constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function searchByQuery(LocationRequest $request)
    {
        return $this->client->get('/locations/query', $request->__toArray());
    }

}
