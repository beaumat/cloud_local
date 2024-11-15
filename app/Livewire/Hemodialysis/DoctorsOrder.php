<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DoctorsOrder extends Component
{       
    #[Reactive]
    public int $HEMO_ID;
    public string $DOCTOR_ORDER;
    private $hemoServices;
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    public function mount() {
        $this->DOCTOR_ORDER = $this->hemoServices->GetDoctorOrder($this->HEMO_ID);
    }
    public function saveIt()
    {
        $this->hemoServices->UpdateDoctorOrder($this->HEMO_ID, $this->DOCTOR_ORDER);
        session()->flash('message', 'Doctors order updated.');
    }
    public function render()
    {
        return view('livewire.hemodialysis.doctors-order');
    }
}
