<?php


namespace App\Models\Search\Hotel;


class Room
{
    private $adult;

    private $child;

    /**
     * Room constructor.
     * @param $adult
     * @param $child
     */
    public function __construct(int $adult = 1, array $child = [])
    {
        $this->adult = $adult;
        $this->child = $child;
    }

    /**
     * @return int
     */
    public function getAdult(): int
    {
        return $this->adult;
    }

    /**
     * @return array
     */
    public function getChild(): array
    {
        return $this->child;
    }

    /**
     * @param array $child
     */
    public function setChild(array $child): void
    {
        $this->child = $child;
    }

    public function addChild()
    {
        $this->child[] = new Child;
    }

    public function removeChild()
    {
        array_pop($this->child);
    }

    public function addAdult()
    {
        $this->adult++;
    }

    public function removeAdult()
    {
        $this->adult--;
    }
}
