<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WelcomeSearch extends Component
{
    public $type = 'Hotel';
    public $typeLabel = null;

    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->typeLabel = __($this->type);
    }

    public function render()
    {
        return view('livewire.welcome-search');
    }

    public function changeToType(string $type)
    {
        $this->type = $type;
        $this->typeLabel = __($this->type);

        $this->dispatchBrowserEvent('init-date');
    }
}
