<?php

namespace App\Http\Livewire\Hotels;

use App\Models\Hotelbeds\Hotel;
use App\Models\Hotelbeds\Destination;
use App\Models\HotelSearch;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Hotelbeds\Facility;
use Livewire\WithPagination;

use Carbon\Carbon;
use Helpers\HotelBeds\Config\HotelBedsConfigManager;
use Helpers\HotelBeds\HotelBedsAdapter;
use Helpers\HotelBeds\HotelBedsExceptionHandler;
use Helpers\HotelBeds\HotelBedsHotelFactory;
use Helpers\HotelBeds\HotelBedsHotelsAvailabilityRequest;


class SearchResult extends Component
{
    use WithPagination;
    /** @var HotelBedsDictionaryResponse */

    public $hotelName;

    public $internet; 
    public $swimingpool;
    public $restaurant; 
    public $parking; 

    public $mainImage;

    public $sortField;

    public $sortDirection;

    public $searchRate = [];

    public $facilities = [];

    public $minPrice;

    public $maxPrice;

    public $priceRange;

    public $numResults = 10;

    protected $queryString = ['sortField', 'sortDirection'];

    protected $listeners = [
        'load-more' => 'loadMore',
    ];

    public function sortBy($field){
        if($this->sortField === $field){
            $this->sortDirection = $this->sortDirection ===  'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function loadMore(){
        $this->numResults += 10;
    }

    public function render()
    {
        $search = HotelSearch::where(['uuid' => \Cache::get('search_uuid')])->first();

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

        $hotelsCodes = Hotel::where('destinationCode', $search->destination)->pluck('code')->toArray();
        
        $req = new HotelBedsHotelsAvailabilityRequest(
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
        
        $this->adapter = new HotelBedsAdapter(
            new HotelBedsExceptionHandler(),
            new HotelBedsConfigManager(),
            new HotelBedsHotelFactory(),
        );

        $avhotels = $this->adapter->getAvailabilityByHotels($req);

        $this->minPrice = $avhotels->min('minRateInPoints');
        $this->maxPrice = $avhotels->max('minRateInPoints');

        if($this->hotelName){
            $hotelname = $this->hotelName;
            $avhotels = $avhotels->filter(function($item) use ($hotelname) {
                return stripos($item->name,$hotelname) !== false;
            });
        }

        if($this->priceRange){
            $priceRange = explode(',', $this->priceRange);
            $avhotels = $avhotels->whereBetween('minRateInPoints', $priceRange);
            $this->minPrice = $priceRange[0];
            $this->maxPrice = $priceRange[1];
        }

        if(!empty($this->searchRate)){
            $searchRate = $this->searchRate;
            $avhotels = $avhotels->whereIn('ratingStars', $searchRate);
        }

        if(!empty($this->facilities)){
            foreach($avhotels as $key => $hotel){
                $intersect = array_intersect($hotel->facilities, $this->facilities);
                if(empty($intersect)){
                    $avhotels = $avhotels->forget($key);
                }
            }
        }

        if($this->sortField){
            if($this->sortDirection == 'desc'){
                $avhotels = $avhotels->sortByDesc($this->sortField);
            }else{
                $avhotels = $avhotels->sortBy($this->sortField);
            }
        }

        $avhotels = $avhotels->take($this->numResults);

        return view('livewire.hotels.search-result', [
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
            'hotels' => $avhotels,
            'total' => $avhotels->count(),
        ]);
    }

    public function getFacilities($facilities){
        foreach($facilities as $facility){
            if(in_array($facility, $this->facilities)){
                if (($key = array_search($facility, $this->facilities)) !== false) {
                    unset($this->facilities[$key]);
                }
            }else{
                array_push($this->facilities, $facility);
            }
        }
        
    }
}
