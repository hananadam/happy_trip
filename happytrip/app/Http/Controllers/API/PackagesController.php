<?php

namespace App\Http\Controllers\API;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Http\Requests\API\CouponRequest;
use App\Http\Requests\API\ContactRequest;
use App\Models\Ad;
use App\Models\Coupon;
use App\Models\Message;
use App\Models\Setting;
use App\Models\User;
use App\Models\Package;
use App\Models\PackageDetail;
use Carbon\Carbon;

use Cache;
use Config;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;
class PackagesController extends Controller
{
    use Helpers;

    /**
     * @OA\Get(
     *      path="/packages",
     *      operationId="packages",
     *      tags={"Packages"},
     *      summary="Get list of all packages",
     *      description="Returns list of packages",   
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function getPackages(Request $request)
    {
        $data = Package::get();
        
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

     /**
     * @OA\Get(
     *      path="/package-details",
     *      operationId="packages",
     *      tags={"Packages details"},
     *      summary="Get list of all package details",
     *      description="Returns list of package details",   
     *     @OA\Parameter(
     *          name="id",
     *          description="id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function getPackageDetails(Request $request,$id)
    {
        $data = Package::where('id',$id)->first();
        $data->scheduale = PackageDetail::where('package_id',$id)->get();
        //$data->images = $data->images()->get();
        $data->images = $data->getMedia('packages');
        //$data->image=$data->getFirstMediaUrl('packages');
        
        return response()->json([
            'message' => 'success',
            'data' => $data, 
        ]);
    }

     /**
     * @OA\Get(
     *      path="/packages/filter",
     *      operationId="filter",
     *      tags={"packages"},
     *      summary="Filterpackages by title, price ",
     *      description="Returns list of filtered packages",
     *     @OA\Parameter(
     *          filter="title",
     *          name="package title",
     *          description="Get package by title",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          filter="price",
     *          name="priceRange",
     *          description="Get packages in this price range like : 100,300",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function filters(Request $request)
    {
        $packages = Package::get();
 		$title = $request->title;
        $priceRange = explode(',', $request->priceRange);
        if ($request->priceRange) {
            $packages = $packages->whereBetween('price', $priceRange)->values();
        }

        if ($request->title) {
            $packages = $packages->filter(function ($item) use ($title) {
                return stripos($item->title, $title) !== false;
            });
            $packages = array_values($packages->toArray());
        } 

        elseif ($request->priceRange && $request->title) {
            if($packages)
            {
                $packages=$packages->whereBetween('price', $priceRange)->values();
                $packages = $packages->filter(function ($item) use ($title) {
                    return stripos($item->title, $title) !== false;
                });
                $packages = array_values($packages->toArray());
            }
          
        }
       
        return response()->json([
            'message' => 'success',
            'data' => $packages,
        ]);
    }
}