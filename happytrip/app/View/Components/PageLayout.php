<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PageLayout extends Component
{
    public $class;

    /**
     * Create a new component instance.
     *
     * @param string|null $class
     */
    public function __construct(?string $class)
    {
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('layouts.page');
    }
}
