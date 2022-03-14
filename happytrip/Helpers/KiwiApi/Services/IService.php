<?php

namespace Helpers\KiwiApi\Services;

use Helpers\KiwiApi\Client;

interface IService
{
    public function __construct(Client $client);
}
