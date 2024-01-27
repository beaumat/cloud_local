<?php

namespace App\Livewire;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class AlertLayout extends Component
{
    #[Reactive]
    public $errors ;
    #[Reactive]
    public $message;
    #[Reactive]
    public $error;

    public function mount($errors , $message, $error)
    {
        $this->$errors = $errors;
        $this->$error = $error;
        $this->$message = $message;
    }
    public function render()
    {
        return view('livewire.alert-layout');
    }

    public function dismissAlert()
    {
        $this->dispatch('clear-alert');
    }
}
