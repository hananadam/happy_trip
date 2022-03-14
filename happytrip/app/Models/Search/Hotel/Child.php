<?php


namespace App\Models\Search\Hotel;


class Child
{
    const ages = [
        2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
    ];
    private $age;

    public function __construct(int $age = 2)
    {
        $this->age = $age;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }
}
