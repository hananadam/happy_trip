<?php


namespace Helpers\KiwiApi\Request;


class CheckRequest
{
    private string $booking_token;

    private int $bnum;

    private int $pnum;

    private string $currency;

    private string $visitor_uniqid;

    private int $adults;

    private int $children;

    private int $infants;

    /**
     * CheckRequest constructor.
     * @param string $booking_token
     * @param int $bnum
     * @param int $pnum
     * @param string $currency
     * @param string $visitor_uniqid
     * @param int $adults
     * @param int $children
     * @param int $infants
     */
    public function __construct(
        string $booking_token,
        int $bnum,
        int $pnum,
        string $currency = 'USD',
        string $visitor_uniqid = '',
        int $adults = 1,
        int $children = 0,
        int $infants = 0
    )
    {
        $this->booking_token = $booking_token;
        $this->bnum = $bnum;
        $this->pnum = $pnum;
        $this->currency = $currency;
        $this->visitor_uniqid = $visitor_uniqid;
        $this->adults = $adults;
        $this->children = $children;
        $this->infants = $infants;
    }


    public function __toArray()
    {
        return call_user_func('get_object_vars', $this);
    }
}
