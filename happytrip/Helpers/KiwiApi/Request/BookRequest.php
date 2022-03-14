<?php


namespace Helpers\KiwiApi\Request;


class BookRequest
{
    private int $bags;
    private string $booking_token;
    private string $currency;
    private string $lang;
    private string $locale;
    private array $passengers;
    private string $session_id;

    /**
     * BookRequest constructor.
     * @param int $bags
     * @param string $booking_token
     * @param string $currency
     * @param string $lang
     * @param string $locale
     * @param array $passengers
     * @param string $session_id
     */
    public function __construct(int $bags, string $booking_token, string $currency, string $lang, string $locale, array $passengers, string $session_id)
    {
        $this->bags = $bags;
        $this->booking_token = $booking_token;
        $this->currency = $currency;
        $this->lang = $lang;
        $this->locale = $locale;
        $this->passengers = $passengers;
        $this->session_id = $session_id;
    }

    public static function fromArray($data): BookRequest
    {
        return new self(
            $data['bags'],
            $data['booking_token'],
            $data['currency'],
            $data['lang'],
            $data['locale'],
            $data['passengers'],
            $data['session_id']
        );
    }
    public function __toArray()
    {
        return call_user_func('get_object_vars', $this);
    }
}
