<?php

namespace App\Http\Livewire\Hotels;

use Livewire\Component;
use Helpers\HotelBeds\ImportStatic\HotelBedsHotelDictionaryItem;

class HotelCard extends Component
{
    /** @var HotelBedsHotelDictionaryItem */
    protected $hotel;
    public $mainImage;

    public function mount($hotel)
    {
        $this->hotel = $hotel;
    }

    public function render()
    {
        return view('livewire.hotels.hotel-card', [
            'hotel' => $this->hotel
        ]);
    }

    public function getPrice()
    {
        return 0;
    }
}
