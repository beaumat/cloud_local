<?php

namespace App\Livewire\Hemodialysis;

use Livewire\Component;

class AddDefaultModal extends Component
{
    public bool $showModal = false;
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function render()
    {
        return view('livewire.hemodialysis.add-default-modal');
    }
}
