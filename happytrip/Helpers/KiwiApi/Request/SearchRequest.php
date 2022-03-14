<?php


namespace Helpers\KiwiApi\Request;


class SearchRequest
{
    private string $fly_from;

    private string $fly_to;

    private string $date_from;

    private string $date_to;

    private string $return_from;

    private string $return_to;

    private string $flight_type;


//    private int $nights_in_dst_from = 2;
//    private int $nights_in_dst_to = 3;
//    private int $max_fly_duration = 20;
//    private string $flight_type = "round";
//    private int $one_for_city = 0;
//    private int $one_per_date = 0;
//    private int $adults = 2;
//    private int $children = 2;
//    private $selected_cabins = "C";
//    private $mix_with_cabins = "M";
//    private $adult_hold_bag = "1,0";
//    private $adult_hand_bag = "1,1";
//    private $child_hold_bag = "2,1";
//    private $child_hand_bag = "1,1";
//    private bool $only_working_days = false;
//    private bool $only_weekends = false;
//    private string $partner_market = 'us';
//    private int $max_stopovers = 2;
//    private int $max_sector_stopovers = 2;
//    private string $vehicle_type = "aircraft";
//    private int $limit = 500;

    /**
     * SearchRequest constructor.
     * @param string $flight_type
     * @param string $fly_from
     * @param string $fly_to
     * @param string $date_from
     * @param string $date_to
     * @param string|null $return_from
     * @param string|null $return_to
     */
    public function __construct(
        string $fly_from,
        string $fly_to,
        string $date_from,
        string $date_to,
        ?string $return_from,
        ?string $return_to,
        string $flight_type
    )
    {
        $this->flight_type = $flight_type;
        $this->fly_from = $fly_from;
        $this->fly_to = $fly_to;
        $this->date_from = date('d/m/Y', strtotime($date_from));
        $this->date_to = date('d/m/Y', strtotime($date_to));
        if ($return_to && $return_from) {
            $this->return_from = date('d/m/Y', strtotime($return_from));
            $this->return_to = date('d/m/Y', strtotime($return_to));
        }
    }

    public function __toArray()
    {
        return call_user_func('get_object_vars', $this);
    }
}
