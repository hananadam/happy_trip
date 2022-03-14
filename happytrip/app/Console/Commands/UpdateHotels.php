<?php

namespace App\Console\Commands;

use App\Models\Hotelbeds\Hotel;
use Helpers\HotelBeds\HotelBedsHotelFactory;
use Helpers\HotelBeds\ImportStatic\HotelBedsHotelDictionaryItem;
use Illuminate\Console\Command;

class UpdateHotels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:hotels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $factory = new HotelBedsHotelFactory();
        $json = file_get_contents(public_path("/hotels/1-1000.json"));

        $json = json_decode($json, true);

        $bar = $this->output->createProgressBar(count($json['hotels']));


        /** @var HotelBedsHotelDictionaryItem $item */
        foreach ($json['hotels'] as $item) {
            $bar->start();

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

            $bar->advance();
        }

        $bar->finish();

        return true;
    }
}
