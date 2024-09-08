<?php

namespace App\Livewire\PhilHealth;

use App\Services\HemoServices;
use Livewire\Attributes\On;
use Livewire\Component;

class DoctorOrder extends Component
{

    public bool $showModal = false;
    public int $HEMO_ID  = 0;
    public string $DOCTOR_ORDER;
    private $hemoServices;

    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    #[On('doctor-order-show')]
    public function openModal($result)
    {
        $this->HEMO_ID = (int) $result['HEMO_ID'];
        $data = $this->hemoServices->get($this->HEMO_ID);
        if ($data) {
            $this->DOCTOR_ORDER = $data->DOCTOR_ORDER ?? '';
            $this->showModal = true;
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function save()
    {
        $this->hemoServices->UpdateDoctorOrder($this->HEMO_ID, $this->DOCTOR_ORDER);
        session()->flash('message', 'Successfully save');
        $this->dispatch('refresh-treatment-summary');
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        return view('livewire.phil-health.doctor-order');
    }
}
