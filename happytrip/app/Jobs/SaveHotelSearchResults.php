<?php

namespace App\Jobs;

use App\Models\HotelSearch;
use App\Models\HotelSearchResult;
use Helpers\HotelBeds\HotelBedsHotelListItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SaveHotelSearchResults implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var HotelSearch */
    public $search;

    /** @var Collection */
    public $results;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(HotelSearch $search, Collection $results)
    {
        $this->search = $search;
        $this->results = $results;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var HotelBedsHotelListItem $result */
        foreach ($this->results as $result) {
            HotelSearchResult::updateOrCreate([
                'code'  =>  $result->code,
                'hotel_search_id'   =>  $this->search->uuid,
                'content'   =>  json_encode($result, true)
            ]);
        }
    }
}
