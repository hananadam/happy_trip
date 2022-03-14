<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NavbarAuth extends Component
{
    public $login = false, $register = false;

    public $email, $password;

    public function render()
    {
        return view('livewire.navbar-auth');
    }

    public function loginTab()
    {
        $this->login = true;
        $this->register = false;
    }

    public function registerTab()
    {
        $this->login = false;
        $this->register = true;
    }


    private function resetInputFields(){
        $this->password = null;
        $this->email = null;
        $this->login = false;
        $this->register = false;
    }

}
