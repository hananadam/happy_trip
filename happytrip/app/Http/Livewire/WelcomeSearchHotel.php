<?php

namespace App\Http\Livewire;

use App\Models\Hotelbeds\Destination;
use App\Models\HotelSearch;
use App\Models\Search\Hotel\Room;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;

class WelcomeSearchHotel extends Component
{
    protected $listeners = ['checkInCheckOutInputChanged' => 'checkInCheckOutInputChange'];

    public $criteria = false;
    public $openSelectForm = false;
    public $guestCount = 0;

    public $maxRooms = 4;
    public $maxChildPerRoom = 2;
    public $maxAdultPerRoom = 4;

    private $rooms = [];
    private $searchId = null;

    public $destination = null;
    public $destinations = [];

    public $destinationCode;
    public $destinationLabel;

    public $checkInCheckOut;

    public $checkIn;
    public $checkOut;

    public function mount()
    {
        $this->searchId = cache('search_id');
        if (!$this->searchId) {
            $this->searchId = Str::uuid();
            cache()->put('search_id', $this->searchId);
        }
        $this->addRoom();

        $this->checkIn = date('d-M-Y');
        $this->checkOut = date('d-M-Y', strtotime("tomorrow"));
    }

    public function hydrate()
    {
        $this->searchId = cache('search_id');
        $this->rooms = unserialize(cache("{$this->searchId}.rooms"));
    }

    public function render()
    {
        return view('livewire.welcome-search-hotel');
    }

    public function openOrCloseForm()
    {
        $this->openSelectForm = !$this->openSelectForm;
        $this->dispatchBrowserEvent('init-date');
    }

    public function setRooms()
    {
        cache()->put("{$this->searchId}.rooms", serialize($this->rooms));
    }

    public function addRoom()
    {
        array_push($this->rooms, new Room);
        $this->setRooms();
        $this->calculateGusts();
    }

    public function removeRoom()
    {
        array_pop($this->rooms);
        $this->setRooms();
    }

    public function calculateGusts()
    {
        $count = 0;
        /** @var Room $room */
        foreach ($this->rooms as $room) {
            $count += count($room->getChild()) + $room->getAdult();
        }
        $this->guestCount = $count;
    }

    public function getRooms(): array
    {
        return $this->rooms;
    }

    public function addAdult($index)
    {
        /** @var Room $room */
        $room = $this->rooms[$index];
        $room->addAdult();
        $this->guestCount++;
        $this->rooms[$index] = $room;
        $this->setRooms();
    }

    public function removeAdult($index)
    {
        /** @var Room $room */
        $room = $this->rooms[$index];
        if ($room->getAdult() > 1) {
            $room->removeAdult();
            $this->guestCount--;
            $this->rooms[$index] = $room;
            $this->setRooms();
        }
    }

    public function addChild($index)
    {
        /** @var Room $room */
        $room = $this->rooms[$index];
        if (count($room->getChild()) < 2) {
            $room->addChild();
            $this->guestCount++;
            $this->rooms[$index] = $room;
            $this->setRooms();
        }
    }

    public function removeChild($index)
    {
        /** @var Room $room */
        $room = $this->rooms[$index];
        if (count($room->getChild()) > 0) {
            $room->removeChild();
            $this->guestCount--;
            $this->rooms[$index] = $room;
            $this->setRooms();
        }
    }

    public function onChangeDestination()
    {
        if (strlen($this->destination) > 3) {
            $list = [];
            $constraints = Destination::whereName($this->destination)
                ->orWhere('name', 'like', "%{$this->destination}%")
                ->orWhere('code', 'like', "%{$this->destination}%")
                ->orWhere('countryCode', 'like', "%{$this->destination}%")
                ->get()
            ;
            // $destinations = Destination::search($this->destination)->get();
            // $destinations = $destinations->merge($constraints)->unique();

            /** @var Destination $destination */
            foreach ($constraints as $destination) {
                if (!$destination->country) {
                    continue;
                }
                $list[] = [
                    'code'  =>  $destination->code,
                    'label' => "{$destination->name}, {$destination->country->description}"
                ];
            }
            $this->destinations = $list;
        }
    }

    public function choiceDestination(string $label , string $code)
    {
        $this->destinationCode = $code;
        $this->destination = $label;
        $this->destinationLabel = $label;
        $this->destinations = [];
    }

    public function checkInCheckOutInputChange(string $checkIn, string $checkOut)
    {
        // $date = explode(' - ', $date);
        // $this->checkInCheckOut = $date;
        $this->checkIn = $checkIn;
        $this->checkOut = $checkOut;
    }

    public function checkAvailability()
    {
        if ($this->destinationCode) {
            // TODO should be in que 
            //saving hotel search
            $searchObj = New HotelSearch();
            $searchObj->uuid = Str::uuid()->toString();
            $searchObj->destination = $this->destinationCode;
            $searchObj->check_in = date('Y-m-d', strtotime($this->checkIn));
            $searchObj->check_out = date('Y-m-d', strtotime($this->checkOut));
            $searchObj->content = serialize($this->rooms);
            $searchObj->user_ip =  request()->ip();

            if(\Auth::check()){
                $searchObj->user_id=  Auth()->user()->id;
            }
            $searchObj->save();

            \Cache::put('search_uuid', $searchObj->uuid);

            $this->redirectRoute('hotels.search', ['search' => $searchObj->uuid]);
        }
    }
}
