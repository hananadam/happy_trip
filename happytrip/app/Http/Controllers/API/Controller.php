<?php

namespace App\Http\Controllers\API;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Dingo\Api\Routing\Helpers;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="HT Api Documentation",
     *      description="Happy Trip description",
     *      @OA\Contact(
     *          email="it@happytbooking.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="DEV API Server"
     * )
     *
     */
}
