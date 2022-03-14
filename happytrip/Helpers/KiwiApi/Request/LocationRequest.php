<?php


namespace Helpers\KiwiApi\Request;


class LocationRequest
{
    private string $term;

    private string $locale;

    private string $location_types;

    private int $limit;

    private bool $active_only;

    private string $sort;

    /**
     * LocationRequest constructor.
     * @param string $term
     * @param string $locale
     * @param string $location_types
     * @param int $limit
     * @param bool $active_only
     * @param string $sort
     */
    public function __construct(
        string $term,
        string $locale = 'en-US',
        string $location_types = 'airport',
        int $limit = 10,
        bool $active_only = true,
        string $sort = 'name'
    )
    {
        $this->term = $term;
        $this->locale = $locale;
        $this->location_types = $location_types;
        $this->limit = $limit;
        $this->active_only = $active_only;
        $this->sort = $sort;
    }

    public function __toArray()
    {
        return call_user_func('get_object_vars', $this);
    }
}
