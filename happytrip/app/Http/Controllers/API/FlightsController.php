<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\FlightAirLinesRequest;
use App\Http\Requests\API\FlightBookRequest;
use App\Http\Requests\API\FlightCheckRequest;
use App\Http\Requests\API\FlightLocationRequest;
use App\Http\Requests\API\FlightMultiSearchRequest;
use App\Http\Requests\API\FlightSearchRequest;
use App\Models\Kiwi\Location;
use Dingo\Api\Routing\Helpers;
use Helpers\KiwiApi\Client;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Passenger;
use App\Models\Hotelbeds\Hotel;
use App\Models\Rating;
use App\Models\User;
use Hash,DB,Auth,Str;
use Carbon\Carbon;

use Helpers\KiwiApi\Request\BookRequest;
use Helpers\KiwiApi\Request\CheckRequest;
use Helpers\KiwiApi\Request\LocationRequest;
use Helpers\KiwiApi\Request\SearchMultiRequest;
use Helpers\KiwiApi\Request\SearchRequest;
use Illuminate\Http\JsonResponse;

class FlightsController extends Controller
{
    use Helpers;

    /**
     * @OA\Get(
     *      path="/flights/locations",
     *      operationId="locations",
     *      tags={"Flights"},
     *      summary="Get list of locations by name",
     *      description="Returns list of locations",
     *     @OA\Parameter(
     *          name="term",
     *          description="Location name term",
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
    public function locations(FlightLocationRequest $request): JsonResponse
    {
        $kRequest = new LocationRequest($request->term);

        $client = new Client();

        $locations = $client->getLocations()->searchByQuery($kRequest);

        return response()->json([
            'message' => 'success',
            'data' => $locations['locations'],
        ]);
    }

    /**
     * @OA\Get(
     *      path="/flights/check",
     *      operationId="checkFlights",
     *      tags={"Flights"},
     *      summary="Get flight details by token",
     *      description="flight details",
     *     @OA\Parameter(
     *          name="booking_token",
     *          description="booking token",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="bnum",
     *          description="The number of bags for the booking, even if bags_price states that the first (or even second) checked baggage is free, it is necessary to request it",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="pnum",
     *          description="Number of passengers. Allowed range is 1-9",
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
    public function checkFlights(FlightCheckRequest $request): JsonResponse
    {
        $kRequest = new CheckRequest(
            $request->booking_token,
            $request->bnum,
            $request->pnum,
        );

        $client = new Client();
        $data = $client->getBooking()->searchByQuery($kRequest);

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/flights/search",
     *      operationId="flightsSearch",
     *      tags={"Flights"},
     *      summary="Get flights by date and location",
     *      description="list of flight",
     *     @OA\Parameter(
     *          name="fly_from",
     *          description="departure location Link to Locations API",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="fly_to",
     *          description="It accepts the same values in the same format as the 'fly_from' parameter",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="date_from",
     *          description="Use parameters date_from and date_to as a date range for the flight departure Example : 01-04-2021",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="date_to",
     *          description="search flights upto this date (dd-mm-yyyy) Example : 03-04-2021",
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
    public function flightsSearch(FlightSearchRequest $request): JsonResponse
    {
        $requestData = new SearchRequest(
            $request->fly_from,
            $request->fly_to,
            $request->date_from,
            $request->date_to,
            $request->return_from,
            $request->return_to,
            $request->flight_type
        );
        $client = new Client(); 

        $data = $client->getFlights()->searchByQuery($requestData);

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @OA\POST(
     *      path="/flights/search_multi",
     *      operationId="flightsMultiSearch",
     *      tags={"Flights"},
     *      summary="Post multi flights by date and location",
     *      description="post multi flight",
     *     @OA\Parameter(
     *          name="requests",
     *          description="adding multi flights request to get list of options",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="json"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function flightsMultiSearch(FlightMultiSearchRequest $request): JsonResponse
    {
        $requestData = new SearchMultiRequest(
            $request->requests
        );
        $client = new Client();

        $data = $client->getFlights()->searchMultiByQuery($requestData);
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @OA\POST(
     *      path="/flights/search_multi",
     *      operationId="flightsMultiSearch",
     *      tags={"Flights"},
     *      summary="Post multi flights by date and location",
     *      description="post multi flight",
     *     @OA\Parameter(
     *          name="bags",
     *          description="adding bags number for flight",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="currency",
     *          description="adding Currency code for flight",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="lang",
     *          description="adding Language code for flight",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="locale",
     *          description="adding Language code for flight",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="booking_token",
     *          description="adding booking token coming from flight search",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="session_id",
     *          description="adding session coming from check flight api",
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
    public function bookFlight(FlightBookRequest $request): JsonResponse
    { 
        if (auth('sanctum')->check()) {
            $data = auth('sanctum')->user();
            $name = explode(" ", $data->name);
            $firstName = $name[0];
            $lastName = $name[1];
            $email = $data->email;

            $customerInfo = $data->customerInfo()->first();
            $customerId = $customerInfo->id;
            $title = $customerInfo->title;
            $country = $customerInfo->country;
            $nationality = $customerInfo->nationality;
            $mobile_num = $customerInfo->mobile_num;
            $dob = $customerInfo->dob;
        } 
        else {
            $firstName = $request->passengers[0]['name'];
            $lastName  =  $request->middleName . ' '. $request->passengers[0]['surname'];
            $email = $request->passengers[0]['email'];
            $title=$request->passengers[0]['title'];
            $country = $request->country;
            $nationality = $request->passengers[0]['nationality'];
            $mobile_num = $request->passengers[0]['phone'];
            $dob = $request->passengers[0]['birthday'];

            if ($user = User::where('email', $email)->first()) {
                if ($user->customerInfo) {
                    $customerId = $user->customerInfo->id;
                } else {
                    $customerData = $user->createCustomer('male',$title,$country,$nationality,$mobile_num,$dob);
                    $customerId = $customerData->id;
                }
            } else {
                $user = new User();
                $user->name = $firstName . ' ' . $lastName;
                $user->email = $email;
                $user->password = Hash::make(Str::random(10));
                $user->save();
                $customerData = $user->createCustomer('male',$title,$country,$nationality,$mobile_num,$dob);
                $customerId = $customerData->id;
            }
        }

        $data = BookRequest::fromArray($request->all());
        $client = new Client();
        $data = $client->getBooking()->book($data);
        $customer = Customer::where('id', $customerId)->first();

        foreach($request->passengers as $passenger) {
            Passenger::create([
                'booking_id' =>  $data['booking_id'],
                'title' => $passenger['title'],
                'name' => $passenger['name'],
                'surname' => $passenger['surname'],
                'email' => $passenger['email'],
                'nationality' => $passenger['nationality'],
                'birthday' => $passenger['birthday'],
                'phone' => $passenger['phone'],
                'cardno' => $passenger['cardno'],
                'expiration' => $passenger['expiration']
            ]);
        }

        $bookingData = $customer->saveBooking(
                    'flight',
                    'KIWIAPi',
                    $data['booking_id'],
                    $data['flights'][0]['checkin'],
                    $data['flights'][0]['utc_departure'],
                    $data['flights'][0]['utc_arrival'],
                    $data['flights'][0]['flight_no'],
                    0,
                    0,
                    0,
                    0,
                    0,
                    1,
                    $data['tickets_price'],
                    $data['sp_fee'],
                    $data['total'],
                    'flightBooking',
                    $data['flights'][0]['src_name'],
                    $data['flights'][0]['dst_name'],
                    $request->bags,
                    30,

        );

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/flights/airlines",
     *      operationId="airlines",
     *      tags={"Flights"},
     *      summary="Get list of airlines by name code and type",
     *      description="Returns list of locations",
     *     @OA\Parameter(
     *          name="type",
     *          description="Location type airline,bus",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="code",
     *          description="Location code",
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
    public function airlines(FlightAirLinesRequest $request): JsonResponse
    {

        $data = Location::whereCode($request->code)->whereType($request->type)->get();
        $data = $data->map(function (Location $location) {
            return [
                'id' => $location->id,
                'code' => $location->code,
                'name' => $location->name,
                'type' => $location->type,
                'image' => $location->getImage($location->code)
            ];
        });
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }
}
