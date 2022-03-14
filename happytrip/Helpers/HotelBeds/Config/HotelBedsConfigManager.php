<?php

namespace Helpers\HotelBeds\Config;

class HotelBedsConfigManager
{
    /**
     * @return HotelBedsConfig
     */
    public function getConfig()
    {
        return new HotelBedsConfig(
            'https://api.test.hotelbeds.com/',
            'https://api-secure.test.hotelbeds.com/',
            'application/json',
            'gzip',
            '2b927dee78c44cf625bb61a0c2136801',
            '6d1e3494ba',
            ''
        );
    }

}
