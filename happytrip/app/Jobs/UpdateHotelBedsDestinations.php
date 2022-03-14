<?php

namespace App\Jobs;

use App\Models\Hotelbeds\Destination;
use Helpers\HotelBeds\Config\HotelBedsConfigManager;
use Helpers\HotelBeds\HotelBedsAdapter;
use Helpers\HotelBeds\HotelBedsExceptionHandler;
use Helpers\HotelBeds\HotelBedsHotelFactory;
use Helpers\HotelBeds\ImportStatic\HotelBedsDestinationDictionaryItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateHotelBedsDestinations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $from = 1;
        $to = 1000;
        $adapter = new HotelBedsAdapter(
            new HotelBedsExceptionHandler(),
            new HotelBedsConfigManager(),
            new HotelBedsHotelFactory(),
        );
        $items = collect();

        do {
            $destinations = $adapter->getDestinations([
                'fields'    =>  'all',
                'language'  =>  'ENG',
                'from'  =>  $from,
                'to'    =>  $to,
                'useSecondaryLanguage'  =>  'true'
            ]);

            $items = $items->merge($destinations->items);

            if ($destinations->total > $to) {
                $from = $to + 1;
                $to = $to + 1000;
            }

        } while ($destinations->total > $to);

        /** @var HotelBedsDestinationDictionaryItem $item */
        foreach ($items as $item) {
            Destination::insert(['code' =>  $item->code, 'name' => $item->name, 'countryCode' => $item->countryCode]);
        }
    }
}
