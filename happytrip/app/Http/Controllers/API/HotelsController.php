<?php


namespace App\Http\Controllers\API;
//use Stevebauman\Location\Facades\Location;
use App\Jobs\SaveHotelSearchResults;
use App\Models\Booking;
use App\Models\City;
use App\Models\Customer;
use App\Models\Hotelbeds\Destination;
use App\Models\Hotelbeds\Hotel;
use App\Models\HotelSearch;
use App\Models\Rating;
use App\Models\User;
use App\Models\CustomerCard;
use App\Http\Requests\API\HotelSearchRequest;
use App\Http\Requests\API\RatingRequest;
use App\Http\Requests\API\HotelBookRequest;
use Auth;
use Cache;
use Carbon\Carbon;
use DB;
use Dingo\Api\Routing\Helpers;
use Helpers\HotelBeds\Request\CodesRequest;
use Helpers\HotelBeds\Request\CoordinateRequest;
use Helpers\HotelBeds\Config\HotelBedsConfigManager;
use Helpers\HotelBeds\HotelBedsAdapter;
use Helpers\HotelBeds\HotelBedsExceptionHandler;
use Helpers\HotelBeds\HotelBedsHotelFactory;
use Helpers\HotelBeds\HotelBedsHotelsAvailabilityRequest;
use Helpers\HotelBeds\HotelBedsOrderInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Location;
use Monarobase\CountryList\CountryListFacade;
use Storage;
use Str,URL;

class HotelsController extends Controller
{
    use Helpers;

    private $adapter;

    public function __construct()
    {
        $this->adapter = new HotelBedsAdapter(
            new HotelBedsExceptionHandler(),
            new HotelBedsConfigManager(),
            new HotelBedsHotelFactory(),
        );
    }

    /**
     * @OA\Get(
     *      path="/citites",
     *      operationId="citites",
     *      tags={"Citites"},
     *      summary="Get list of all citites",
     *      description="Returns list of citites",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function getCities()
    {
        $data = City::all();
        $data = $data->map(function (City $city) {
            return [
                'id' => $city->id,
                'destination' => $city->destination->code,
                'name' => $city->name,
                'image' => Storage::url($city->image)
            ];
        });

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/countries",
     *      operationId="countries",
     *      tags={"Countries"},
     *      summary="Get list of all countries",
     *      description="Returns list of countries",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function getCountries(Request $request)
    {
        $header_data = $request->header('lang');
        if ($request->hasHeader('lang')) {
            $language = $header_data;
        }
        else {
            $lang = Cache::get('locale');
            if ($lang) {
                $language = $lang;
            } else {
                if (auth('sanctum')->check()) {
                    $user = User::find(auth('sanctum')->user()->id);
                    $language = $user->customerInfo->language;
                } else {
                    $language = 'en';
                }
            }
        }
        $data = CountryListFacade::getList($language);
        return response()->json([
            'message' => 'success',
            'language' => $language,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/hotels/city",
     *      operationId="city",
     *      tags={"Hotels"},
     *      summary="Get list of hotels by city",
     *      description="Returns list of hotels",
     *     @OA\Parameter(
     *          name="city",
     *          description="hotel by city",
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
    public function hotelsByCity($city)
    {
        $hotels = Hotel::where('city', $city)->simplePaginate(10);
        $count = count($hotels);
        return response()->json([
            'message' => 'success',
            'count' => $count,
            'data' => $hotels,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/hotels/availableByCity",
     *      operationId="availableByCity",
     *      tags={"Hotels"},
     *      summary="Get list of available hotels by city",
     *      description="Returns list of hotels",
     *     @OA\Parameter(
     *          name="codes",
     *          description="hotel by codes",
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
    public function availableHotelsByCity(Request $request)
    {
        $hotelsCodes = explode(',', $request->codes);
        $avhotels = $this->adapter->currentAvailableHotels($hotelsCodes,$request->destination);
        $count = count($avhotels);
        return response()->json([
            'message' => 'success',
            'count' => $count,
            'data' => $avhotels,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/hotels/bylocation",
     *      operationId="bylocation",
     *      tags={"Hotels"},
     *      summary="Get list of available hotels in user location",
     *      description="Returns list of hotels",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function hotelsByLocation()
    {
        $ip = $this->adapter->getIp();
        $location = Location::get($ip);
        $city = $location->cityName;
        $hotels = Hotel::where('city', $city)->take(4)->get();
        $hotelsCodes = $hotels->pluck('code')->toArray();
        $destination = Destination::select('code')->where('name', $city)->first();
        if ($destination) {
            $destinationCode = $destination->code;
            $avhotels = $this->adapter->currentAvailableHotels($hotelsCodes, $destinationCode);
            $count = count($avhotels);
            return response()->json([
                'message' => 'success',
                'count' => $count,
                'data' => $avhotels,
                'city' => $city,
                'ip' => $ip,
            ]);
        }
        else {
            return response()->json(['message' => 'error', 'data' => 'no hotels in this city', 'ip' => $ip], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/hotels/nearby",
     *      operationId="nearby",
     *      tags={"Hotels"},
     *      summary="Get list of available hotels by coordiates",
     *      description="Returns list of hotels",
     *     @OA\Parameter(
     *          name="lat",
     *          description="hotel by latitude",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="float"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="lng",
     *          description="hotel by longitude",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="float"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function nearbyHotels(Request $request)
    {
        $coordinates = new CoordinateRequest($request->lat,$request->lng);
        $latitude = $request->lat;
        $longitude = $request->lng;

        $hotels = Hotel::select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"));
        $hotels = $hotels->having('distance', '<', 20)->orderBy('distance', 'asc')->take(4)->get();

        $hotelsCodes = $hotels->pluck('code')->toArray();
        $destinationCode =$coordinates;
        $distances = [];
        foreach ($hotels as $key => $value) {
            $distances[$value->code] = $value->distance;
        }
        $avhotels = $this->adapter->currentAvailableHotels($hotelsCodes, $destinationCode);
        foreach ($avhotels as $key => $hotel) {
            $hotel->reviews = count($this->getHotelReviews($hotel->code,1));
        }

        $count = count($avhotels);
        return response()->json([
            'message' => 'success',
            'count' => $count,
            'data' => $avhotels,
            'distances' => $distances,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/destinations",
     *      operationId="destinations",
     *      tags={"Destinations"},
     *      summary="Get list of all destinations",
     *      description="Returns list of destinations",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function allDestinations()
    {
        $data = Destination::select('name', 'code')->get();

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/hotels/search",
     *      operationId="search",
     *      tags={"Hotels"},
     *      summary="search for hotels",
     *      description="Returns list of hotels",
     *     @OA\Parameter(
     *          name="destinationCode",
     *          description="hotel destination country code like CAI for cairo",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="checkIn",
     *          description="hotel check in date",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="checkOut",
     *          description="hotel check out date",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="adults_num",
     *          description="number of adults for hotel suitable room search",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="bed",
     *          description="hotel bed type for suitable room search",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="array"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="room_num",
     *          description="hotel rooms number for available rooms search",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="children_num",
     *          description="hotel children number for suitable room search",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="ages",
     *          description="hotel children ages for suitable room search",
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
    public function showSearchResults(HotelSearchRequest $request)
    {
        $hotelSearch = new HotelSearch();
        $data = $request->all();
        $searchData = $hotelSearch->createSearch($data,$request->ages,$request->bed);

        $hotels = Hotel::where('destinationCode', $searchData->destination)->paginate(25);
        foreach ($hotels as $key => $hotel) {
            $filters = [];
            $getHotel = $this->adapter->getHotelDetails($hotel->code, $filters);
            if (!empty($getHotel['hotel']['facilities'])) {
                $hotel->facilities = $getHotel['hotel']['facilities'];
            }
            if (!empty($getHotel['hotel']['issues'])) {
                $hotel->covid = $getHotel['hotel']['issues'];
            }
        }
        $destination = Destination::whereCode($searchData->destination)->get()->first();
        return response()->json([
            'message' => 'success',
            'adults' => $request->adults_num,
            'children' => $request->children_num,
            'ages' => $request->ages,
            'roomCount' => $request->room_num,
            'destination' => $destination->name,
            'checkIn' => $request->checkIn,
            'checkOut' => $request->checkOut,
            'hotels' => $hotels,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/hotels/available",
     *      operationId="available",
     *      tags={"Hotels"},
     *      summary="Get list of available hotels from hotels previous search results",
     *      description="Returns list of available hotels",
     *     @OA\Parameter(
     *          name="codes",
     *          description="available hotels by codes",
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
    public function checkAvailableHotels(Request $request)
    {
        $search = HotelSearch::where(['uuid' => Cache::get('search_uuid')])->firstOrFail();
        $roomData = unserialize($search->content);

        $adults = $roomData['adults'];
        $children = $roomData['children'];
        $roomCount = $roomData['rooms'];

        $req = new HotelBedsHotelsAvailabilityRequest(
            Carbon::make($search->check_in),
            Carbon::make($search->check_out),
            $roomCount,
            $adults,
            $children ?? 0,
            null,
            null,
            explode(',', $request->codes),
            $search->destination,
            null,
        );
        $avhotels = $this->adapter->getAvailabilityByHotels($req);
        if (!isset($avhotels['hotels']['hotels'])) {
            $avhotels = [];
        } else {
            $avhotels = $avhotels['hotels']['hotels'];
        }

//        dd($avhotels['hotels']['hotels']);
//        Cache::put('hotels_codes', explode(',', $request->codes), config('session.lifetime'));
        // foreach ($avhotels as $key => $hotel) {
          
        //    $hotel['reviews'] = count($this->getHotelReviews($hotel['code'],1));
        //    $hotel['reviews_count']= count($this->getHotelReviews($hotel['code'],1));
        // }
        // $array=[];
        // foreach ($avhotels as $key => $avhotel) {
        //     $array[$key]['code']=$avhotel->code;
        //     $array[$key]['price']=$avhotel->minRateInPoints;

        // }

        dispatch(new SaveHotelSearchResults($search, collect($avhotels)));

        $destination = Destination::whereCode($search->destination)->get()->first();

        return response()->json([
            'message' => 'success',
            'adults' => $adults,
            'children' => $children,
            'roomCount' => $roomCount,
            'destination' => $destination->name,
            'checkIn' => $search->check_in,
            'checkOut' => $search->check_out,
            'hotels' => $avhotels,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/hotels/sort",
     *      operationId="sort",
     *      tags={"Hotels"},
     *      summary="Sort available hotels desc and asc by rate, price, location and review",
     *      description="Returns list of available sorted hotels",
     *     @OA\Parameter(
     *          name="rating",
     *          description=" hotels from highest rate to lowest",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="price",
     *          description=" hotels from highest price to lowest",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="best_reviewed",
     *          description=" hotels from best reviewed rate to worset reviewed",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="near_me",
     *          description=" hotels from closest to user location to farthest location",
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
    public function sort(string $type, string $by, Request $request)
    {
        $search = HotelSearch::where(['uuid' => Cache::get('search_uuid')])->first();

        $roomData = unserialize($search->content);
        $adults = $roomData['adults'];
        $children = $roomData['children'];
        $roomCount = $roomData['rooms'];
        $destination = Destination::whereCode($search->destination)->get()->first();

        $req = new HotelBedsHotelsAvailabilityRequest(
            Carbon::make($search->check_in),
            Carbon::make($search->check_out),
            $roomCount,
            $adults,
            $children ?? 0,
            null,
            null,
            Cache::get('hotels_codes'),
            $search->destination,
            null,
        );
        $hotels = $this->adapter->getAvailabilityByHotels($req);

        foreach ($hotels as $key => $hotel) {
            // $hotel->minRateInPoints = $hotel->getPrice();
            $reviewsData = $this->getHotelReviews($hotel->code,1);
            $hotel->reviewsCount = count($reviewsData);
            if($hotel->reviewsCount > 0){
                $rates=0;
                foreach ($reviewsData as $key => $review) {
                    $rates+=$review['rate'];
                }
                $hotel->reviewsRate = collect($rates)->avg();
            }
        }
        switch ($by) {
            case 'rating':
                $hotels = $hotels->sortBy('ratingStars', SORT_REGULAR, $type == 'desc')->values();
                break;
            case 'price':
                $hotels = $hotels->sortBy('minRateInPoints', SORT_REGULAR, $type == 'desc')->values();
                break;
            case 'best_reviewed':
                $hotels = $hotels->sortBy('reviewsRate', SORT_REGULAR, $type == 'desc')->values();
                break;
            case 'most_reviewed':
                $hotels = $hotels->sortBy('reviewsCount', SORT_REGULAR, $type == 'desc')->values();
                break;
            case 'near_me':
                $longitude = $request->lng;
                $latitude = $request->lat;
                $dist_hotels = Hotel::select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"));
                $near_hotels = $dist_hotels->having('distance', '<', 20)->orderBy('distance', 'asc')->get();
                if(!empty($near_hotels)){
                    foreach ($near_hotels as $key => $near) {
                        if (in_array($near['name'], $hotels->toArray())) {
                            $hotels = $near_hotels;
                        }
                    }
                }
                else{
                    $hotels=[];
                }
                break;
        }

        return response()->json([
            'message' => 'success',
            'adults' => $adults,
            'children' => $children,
            'roomCount' => $roomCount,
            'destination' => $destination->name,
            'checkIn' => $search->check_in,
            'checkOut' => $search->check_out,
            'hotels' => $hotels,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/hotels/filter",
     *      operationId="filter",
     *      tags={"Hotels"},
     *      summary="Filter available hotels by name, price and stars",
     *      description="Returns list of available filtered hotels",
     *     @OA\Parameter(
     *          filter="name",
     *          name="hotelname",
     *          description="Get hotel by name",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          filter="price",
     *          name="priceRange",
     *          description="Get hotels in this price range like : 100,300",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          filter="stars",
     *          name="starRate",
     *          description=" hotels in this star rate like:5  for 5 showing stars hotels",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function filters($filter, Request $request)
    {
        $search = HotelSearch::where(['uuid' => Cache::get('search_uuid')])->first();

        $roomData = unserialize($search->content);
        $adults = $roomData['adults'];
        $children = $roomData['children'];
        $roomCount = $roomData['rooms'];
        $destination = Destination::whereCode($search->destination)->get()->first();

        //$hotels = Hotel::where('destinationCode', $search->destination)->simplePaginate(10);
        $req = new HotelBedsHotelsAvailabilityRequest(
            Carbon::make($search->check_in),
            Carbon::make($search->check_out),
            $roomCount,
            $adults,
            $children ?? 0,
            null,
            null,
            Cache::get('hotels_codes'),
            $search->destination,
            null,
        );
        $hotels = $this->adapter->getAvailabilityByHotels($req);

        if ($filter == 'name') {
            $hotelname = $request->hotelname;
            $hotels = $hotels->filter(function ($item) use ($hotelname) {
                return stripos($item->name, $hotelname) !== false;
            });

        }
        if ($filter == 'stars') {
            $starRate=$request->starRate;
            $hotels = $hotels->filter(function ($item) use ($starRate) {
                return stripos($item->ratingStars, $starRate) !== false;
            });
            $hotels=$hotels->values();
        }
        if ($filter == 'price') {
            $priceRange = explode(',', $request->priceRange);
            $hotels = $hotels->whereBetween('minRateInPoints', $priceRange)->values();
        }
        if ($request->starRate && $request->priceRange) {
            $starRate=$request->starRate;
            $priceRange = explode(',', $request->priceRange);
            $hotels = $hotels->filter(function ($item) use ($starRate) {
                return stripos($item->ratingStars, $starRate) !== false;
            });
            $hotels=$hotels->whereBetween('minRateInPoints', $priceRange)->values();
        }

        return response()->json([
            'message' => 'success',
            'adults' => $adults,
            'children' => $children,
            'roomCount' => $roomCount,
            'destination' => $destination->name,
            'checkIn' => $search->check_in,
            'checkOut' => $search->check_out,
            'hotels' => $hotels,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/hotels/details",
     *      operationId="details",
     *      tags={"Hotels"},
     *      summary="Get one of previous search result hotel details by its code ",
     *      description="Returns hotel details",
     *     @OA\Parameter(
     *          name="code",
     *          description="Gey hotel details by code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function getHotelDetails($hotelCode)
    {
        $hotel = Hotel::where('code', $hotelCode)->first();
        $search = HotelSearch::where(['uuid' => Cache::get('search_uuid')])->first();

        $roomData = unserialize($search->content);
        $adults = $roomData['adults'];
        $children = $roomData['children'];
        $roomCount = $roomData['rooms'];

        $req = new HotelBedsHotelsAvailabilityRequest(
            Carbon::make($search->check_in),
            Carbon::make($search->check_out),
            $roomCount,
            $adults,
            $children,
            null,
            null,
            [$hotelCode],
            $search->destination,
            null,
        );
        $availability = $this->adapter->getAvailabilityByHotels($req);

        $ReviewsCount = count($this->getHotelReviews($hotelCode,1));
        $filters = [];
        $getHotel = $this->adapter->getHotelDetails($hotelCode, $filters);
        if (!empty($getHotel['hotel']['facilities'])) {
            $hotel->facilities = $getHotel['hotel']['facilities'];
        }
        if (!empty($getHotel['hotel']['issues'])) {
            $hotel->covid = $getHotel['hotel']['issues'];
        }
        if (!empty($availability)) {
            return response()->json([
                'message' => 'success',
                'reviewsCount' => $ReviewsCount,
                'data' => $availability,
                'searchData' => $search,
                // 'reservationDetails' => $allreq,
                //'avhotels' => $avhotels,
            ]);
        }

        return response()->json([
            'message' => 'success',
            'data' => $hotel,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/hotels/data",
     *      operationId="data",
     *      tags={"Hotels"},
     *      summary="Get hotel basic data by its code ",
     *      description="Returns hotel data",
     *     @OA\Parameter(
     *          name="code",
     *          description="Gey hotel data by code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function getHotelData($hotelCode)
    {
        $hotel = Hotel::where('code', $hotelCode)->first();
        $filters = [];
        $getHotel = $this->adapter->getHotelDetails($hotelCode, $filters);
        if (!empty($getHotel['hotel']['facilities'])) {
            $hotel->facilities = $getHotel['hotel']['facilities'];
        }
        if (!empty($getHotel['hotel']['issues'])) {
            $hotel->covid = $getHotel['hotel']['issues'];
        }
        $hotel->reviews = count($this->getHotelReviews($hotelCode,1));
        return response()->json([
            'message' => 'success',
            'data' => $hotel,

        ]);
    }

    /**
     * @OA\Get(
     *      path="/hotels/reviews",
     *      operationId="reviews",
     *      tags={"Hotels"},
     *      summary="Get hotel reviews",
     *      description="Returns list of hotels reviews and count",
     *     @OA\Parameter(
     *          name="hotelCode",
     *          description="hotel review by code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function getHotelReviews($hotelCode,$hotelReview=0)
    {

        $filters = ['hotel' => $hotelCode];
        $rating = Rating::where('code', $hotelCode)->get();
        $ratingData = []; 
       
        foreach ($rating as $key => $rate) {
            $ratingData[$key]['id'] = $rate->id;
            $ratingData[$key]['code'] = $rate->code;
            $ratingData[$key]['customer_name'] = $rate->customer->name;
            $ratingData[$key]['customer_photo'] = URL::to('/uploads') . '/' . $rate->customer->photo;
            $ratingData[$key]['rate'] = $rate->rate;
            $ratingData[$key]['comment'] = $rate->comment;
            $ratingData[$key]['date'] = $rate->created_at;
            // switch ($ratingData[$key]['rate']) {
            //     case $ratingData[$key]['rate'] =5:
            //         $level='Excellent';
            //         break;
            //     case $ratingData[$key]['rate'] =4:
            //         $level='Good';
            //         break;
            //     case $ratingData[$key]['rate'] =3:
            //         $level='Avetage';
            //         break;
            //     case $ratingData[$key]['rate'] =2:
            //         $level='Poor';
            //         break;
            //     default:
            //         break;
            // }
            // $ratingData[$key]['rate_level'] = $level;
        }
        $reviews = $this->adapter->getRatecomments($filters);
        if($reviews){
            $reviews = collect($reviews['rateComments']);
            $reviews = $reviews->where('hotel', $hotelCode);
            $allReviews = array_merge($reviews->toArray(), $ratingData);
        }
        else{
            $allReviews =$ratingData;
        }
        $reviews_count = count($allReviews);
        if($hotelReview == 1){
            return array_merge($reviews->toArray(), $ratingData);
        }
        else{
            return response()->json([
                'message' => 'success',
                'count' => $reviews_count,
                'reviews' => $allReviews,
            ]);
        }

    }

    /**
     * @OA\Get(
     *      path="/user/rating",
     *      operationId="rating",
     *      tags={"User"},
     *      summary="Post hotel review",
     *      description="add a new review for hotel",
     *     @OA\Parameter(
     *          name="hotelCode",
     *          description="hotel review by code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function Rating(RatingRequest $request, $hotelCode)
    {
        $customer = Customer::where('user_id', auth()->user()->id)->first();
        $rating = $customer->createRate($hotelCode, $request->rate, $request->comment);

        return response()->json([
            'message' => 'success',
            'data' => $rating,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/hotels/booking",
     *      operationId="booking",
     *      tags={"Hotels"},
     *      summary="book selected hotel rooms",
     *      description="make a hotel reservation",
     *     @OA\Parameter(
     *          name="rateKey",
     *          description="hotel rateKey ... from hotel details data",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="firstName",
     *          description="user firstName",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="lastName",
     *          description="user lastName",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="email",
     *          description="user email",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="gender",
     *          description="user gender",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="country",
     *          description="user country",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="mobile_num",
     *          description="user mobile_num",
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
    public function booking(HotelBookRequest $request)
    {
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
            $name = explode(" ", $user->name);
            $holderFirstName = $name[0];
            $holderLastName = $name[1];
            $email = $user->email;
            $customerInfo = $user->customerInfo()->first();
            $customerId = $customerInfo->id;
        } else {
            $holderFirstName = $request->firstName;
            $holderLastName = $request->lastName;
            if ($user = User::where('email', $request->email)->first()) {
                if ($user->customerInfo) {
                    $customerId = $user->customerInfo->id;
                } else {
                    $customerData = $user->createCustomer($request->gender,'',$request->country,'',$request->mobile_num,'');
                    $customerId = $customerData->id;
                }
            } else {
                $user = new User();
                $user->name = $holderFirstName . ' ' . $holderLastName;
                $user->email = $request->email;
                $user->password = Hash::make(Str::random(10));
                $user->save();
                $customerData = $user->createCustomer($request->gender,'',$request->country,'',$request->mobile_num,'');
                $customerId = $customerData->id;
            }
        }

        $remark = 'test';

        $rate = explode('|', $request->rateKey);
        $data = explode('~', $rate[9]);
        $roomCount = (int) $data[0];
        $adults = $data[1];
        $childrens = isset($data[2]) ? $data[2] : 0;

        $adultsList = [];
        $childrenList = [];

        for ($x = 0; $x < $roomCount; $x++) {
            for ($a = 0; $a < $adults; $a++) {
                $adultsList[] = [
                    'roomId'    =>  $x+1,
                    "type"  =>  "AD",
                    "name"  =>  $holderFirstName,
                    "surname"  =>  $holderLastName
                ];
            }

            for ($c = 0; $c < $childrens; $c++) {
                $childrenList[] = [
                    'roomId'    =>  $x+1,
                    "type"  =>  "CH",
                    "name"  =>  $holderFirstName,
                    "surname"  =>  $holderLastName
                ];
            }
        }

        $rooms = array_merge($adultsList, $childrenList);

        $body = new HotelBedsOrderInput(
            $request->rateKey,
            $holderFirstName,
            $holderLastName,
            $rooms,
            $remark,
            'happytrip'
        );
        $data = $this->adapter->bookings($body);

        $total=$data->offerInfo->rates->net + ($data->offerInfo->rates->net * 10) / 100;
        $customer = Customer::where('id', $customerId)->first();

        if($request->payment_method == 'card'){
            if($request->card_id){
                $cardData=CustomerCard::where('id',$request->card_id)->first();
            }
            else{
                $cardData =['name'=>$request->name,
                            'number'=>$request->number,
                            'cvv'=>$request->cvv,
                            'expire_month'=>$request->expire_month,
                            'expire_year'=>$request->expire_year,
                            'address'=>'test',
                            'confirmed'=>1
                        ];
                $card = $customer->createCard($cardData);
                $cardData = CustomerCard::latest('created_at')->first();
            }
            $card_id=$cardData['id'];

        }
        if($request->payment_method == 'wallet'){
            $balance = $user->balance;
            if((int)$balance > $total){
                $user->withdraw($total);
                $current= $user->balance;
            }
            else{
                return response()->json(['message' => 'you dont have enough money in wallet'], 400);
            }
            $card_id=0;
        }
        $bookingData = $customer->saveBooking(
                    'hotel',
                    'hotelbeds',
                    $data->offerInfo->reference,
                    $data->offerInfo->creationDate,
                    $data->hotelInfo->checkIn,
                    $data->hotelInfo->checkOut,
                    $data->hotelInfo->code,
                    $data->hotelInfo->name,
                    $data->offerInfo->rates->adults,
                    $data->offerInfo->rates->net,
                    0,
                    0,
                    1,
                    $data->offerInfo->rates->net,
                    '10%',
                    $total,
                    $remark,
                    '',
                    '',
                    0,
                    0,
                    $request->payment_method,
                    $card_id
        );
        $bookingData->card_name=$cardData['name'];
        $bookingData->card_number=$cardData['number'];
        $bookingData->card_cvv=$cardData['cvv'];
        $bookingData->card_expire=$cardData['expire_month'] . '/' . $cardData['expire_year'];

        return response()->json([
            'message' => 'success',
            'bookingData' => $bookingData,
        ]);
    }
}
