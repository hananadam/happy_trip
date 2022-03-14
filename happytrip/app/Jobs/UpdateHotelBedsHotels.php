<?php

namespace App\Jobs;

use App\Models\Hotelbeds\Destination;
use App\Models\Hotelbeds\Hotel;
use Helpers\HotelBeds\Config\HotelBedsConfigManager;
use Helpers\HotelBeds\HotelBedsAdapter;
use Helpers\HotelBeds\HotelBedsExceptionHandler;
use Helpers\HotelBeds\HotelBedsHotelFactory;
use Helpers\HotelBeds\HotelBedsHotelListItem;
use Helpers\HotelBeds\ImportStatic\HotelBedsDestinationDictionaryItem;
use Helpers\HotelBeds\ImportStatic\HotelBedsHotelDictionaryItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateHotelBedsHotels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = public_path("/hotels/{$file}");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $factory = new HotelBedsHotelFactory();
        $json = file_get_contents($this->file);

        $json = json_decode($json, true);

        /** @var HotelBedsHotelDictionaryItem $item */
        foreach ($json['hotels'] as $item) {
            $item = $factory->createHotelBedsStaticsHotel($item);
            Hotel::insert([
                'code' => $item->code,
                'name' => $item->name,
                'slug' => \Str::slug($item->name),
                'description' => $item->description,
                'countryCode' => $item->countryCode,
                'destinationCode' => $item->destinationCode,
                'images' => $item->generalImages->toJson(),
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'ratingStars' => $item->rating,
                'address' => $item->address,
                'city' => $item->city,
                'city_slug' => \Str::slug($item->city),
                'stateCode' =>  $item->stateCode,
                'zoneCode'  =>  $item->zoneCode,
                'categoryCode'  =>  $item->categoryCode,
                'categoryGroupCode' =>  $item->categoryGroupCode,
                'categoryName' =>  $item->categoryName,
                'chainCode' =>  $item->chainCode,
                'accommodationTypeCode' =>  $item->accommodationTypeCode,
                'postalCode'    =>  $item->postalCode,
                'email' =>  $item->email,
                'phones'    =>  $item->phones->toJson(),
                'rooms' =>  $item->rooms->toJson(),
                'facilities'    =>  $item->facilities->toJson(),
                'terminals' =>  $item->terminals->toJson(),
                'issues'    =>  $item->issues->toJson(),
                'wildcards' =>  $item->wildcards->toJson(),
                'web'   =>  $item->web,
                'lastUpdate'    =>  $item->lastUpdate,
                'ranking'   =>  $item->ranking
            ]);
        }

        unlink($this->file);
    }
}
