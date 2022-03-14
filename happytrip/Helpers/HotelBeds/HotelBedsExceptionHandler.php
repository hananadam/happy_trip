<?php


namespace Helpers\HotelBeds;

use Helpers\HotelBeds\Api\Exceptions\HotelBedsApiSystemException;
use Helpers\HotelBeds\Api\Exceptions\HotelBedsApiUserException;
use App\Exceptions\HotelBeadsException;
use Exception;

class HotelBedsExceptionHandler
{
    /**
     * @param callable $callBack
     * @param callable|null $customCatch
     * @return mixed
     */
    public function handle(callable $callBack, callable $customCatch = null)
    {
        try {
            return $callBack();
        } catch(\Exception $e){

        }
    }
}
