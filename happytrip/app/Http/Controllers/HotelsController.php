<?php


namespace App\Http\Controllers;

use App\Jobs\UpdateHotelBedsHotels;
use App\Models\Hotelbeds\Destination;
use App\Models\Hotelbeds\Facility;
use App\Models\Hotelbeds\Hotel;
use App\Models\HotelSearch;
use App\Models\Kiwi\Location;
use App\Models\Search\Hotel\Room;
use App\Models\User;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use AshAllenDesign\LaravelExchangeRates\Rules\ValidCurrency;
use Carbon\Carbon;
use Helpers\HotelBeds\Config\HotelBedsConfigManager;
use Helpers\HotelBeds\HotelBedsAdapter;
use Helpers\HotelBeds\HotelBedsExceptionHandler;
use Helpers\HotelBeds\HotelBedsHotelFactory;
use Helpers\HotelBeds\HotelBedsHotelsAvailabilityRequest;
use Helpers\HotelBeds\ImportStatic\HotelBedsHotelDictionaryItem;
use Helpers\KiwiApi\Client;
use Illuminate\Http\Request;
use Ricadesign\LaravelKiwiScanner\FlightApi;
use Ricadesign\LaravelKiwiScanner\FlightSearcher;
use Ricadesign\LaravelKiwiScanner\FlightSearchQueryBuilder;
use Ricadesign\LaravelKiwiScanner\Model\FlightScheduleParameter;

//use Request;

class HotelsController extends Controller
{
    /** @var HotelBedsAdapter */
    private $adapter;

    public function __construct()
    {
        $this->adapter = new HotelBedsAdapter(
            new HotelBedsExceptionHandler(),
            new HotelBedsConfigManager(),
            new HotelBedsHotelFactory(),
        );
    }

    public function showSearchResults(HotelSearch $search, Request $request)
    {
        //dd($search->content);
        $rooms = unserialize($search->content);

        $adults = 0;
        $children = 0;
        /** @var Room $room */
        foreach ($rooms as $room) {
            $adults += $room->getAdult();
            $children += count($room->getChild());
        }

        $roomCount = count($rooms);

        $destination = Destination::whereCode($search->destination)->first();

        $internet = Facility::where('description', 'LIKE', '%internet%')->pluck('code')->toArray();
        $swimingpool = Facility::where('description', 'LIKE', '%swimming pool%')->pluck('code')->toArray();
        $restaurant = Facility::where('description', 'LIKE', '%Restaurant%')->pluck('code')->toArray();
        $parking = Facility::where('description', 'LIKE', '%parking%')->pluck('code')->toArray();

        return view('pages.hotels', [
            'guests' => $adults + $children,
            'roomCount' => $roomCount,
            'destination' => $destination,
            'checkIn' => $search->check_in,
            'checkOut' => $search->check_out,
            'internet' => json_encode($internet),
            'swimingpool' => json_encode($swimingpool),
            'restaurant' => json_encode($restaurant),
            'parking' => json_encode($parking),
        ]);
    }

    public function showHotelDetails($hotelCode, Request $request)
    {
        $hotel = Hotel::whereCode($hotelCode)->first();
        $search = HotelSearch::where(['uuid' => \Cache::get('search_uuid')])->first();

        // $criteria = base64_decode($criteria);
        // $criteria = json_decode($criteria);
        // $rooms = unserialize($criteria->rooms);
        // $criteria->rooms = $rooms;
        $rooms = unserialize($search->content);
        $adults = 0;
        $children = 0;
        /** @var Room $room */
        foreach ($rooms as $room) {
            $adults += $room->getAdult();
            $children += count($room->getChild());
        }

        $roomCount = count($rooms);
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
        //dd($availability);


        //recomended hotels
        $hotelsCodes = Hotel::where('destinationCode', $search->destination)->pluck('code')->toArray();
        $allreq = new HotelBedsHotelsAvailabilityRequest(
            Carbon::make($search->check_in),
            Carbon::make($search->check_out),
            $roomCount,
            $adults,
            $children,
            null,
            null,
            $hotelsCodes,
            $search->destination,
            null,
        );
        $avhotels = $this->adapter->getAvailabilityByHotels($allreq)->take(4);
        //dd($availability);
        //reviews
        $filters1 = ['hotel' => $hotelCode];
        $reviews = $this->adapter->getRatecomments($filters1);
        $reviews = collect($reviews['rateComments']);
        //dd($reviews);

        $reviews = $reviews->where('hotel', $hotelCode);
        // dd($reviews);
        //hotel details

        if (!empty($availability)) {
            return view('pages.hotel_details', [
                'data' => $availability[0],
                'searchData' => $search,
                'reservationDetails' => $allreq,
                'avhotels' => $avhotels,
                'reviews' => $reviews,
            ]);
        } else {
            return view('pages.no_data');
        }
    }

    // for booking and adding customer data
    public function booking(Request $request)
    {
        // if($request->ajax){
        $validatedData = $request->validate([
            'email' => 'required|unique:users,email',
            'title' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'dailing_code' => 'required',
            'mobile' => 'required|numeric',
        ]);
        $customer = new Customer();
        $customer->title = $request->title;
        $customer->firstname = $request->firstname;
        $customer->lastname = $request->lastname;
        $customer->dailing_code = $request->dailing_code;
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;

        $users = User::orderBy('id', 'desc')->get();
        foreach ($users as $key => $user) {
            if ($user->email == $request->email) {
                $customer->user_id = $user->id;
            }
        }


        $customer->save();

        // }
    }

    // testing wallet
    public function test()
    {
        \Config::set('kiwi-scanner.partner', '9Ftlraxi37JysnSTjYIrt9cxv33vQNiH');


        dd(100);
    }
}
