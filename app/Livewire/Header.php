<?php

namespace App\Livewire;

use App\Livewire\Actions\Logout;
use Livewire\Component;

class Header extends Component
{

    
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect(route('login'));
    }
    public function render()
    {
        return view('livewire.header');
    }
}
