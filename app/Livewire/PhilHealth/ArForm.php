<?php

namespace App\Livewire\PhilHealth;

use App\Services\PhilHealthServices;
use Livewire\Attributes\On;
use Livewire\Component;

class ArForm extends Component
{
    public bool $showModal = false;
    public int $PHILHEALTH_ID;

    public string $AR_DATE;
    public string $AR_NO;

    public string $CODE;
    public string $DATE;

    private $philHealthServices;
    public function boot(PhilHealthServices $philHealthServices)
    {
        $this->philHealthServices = $philHealthServices;
    }
    public function save()
    {

        if ($this->AR_DATE == '' && $this->AR_NO <> '') {
            session()->flash('error', 'LHIO Date Requred');
            return;
        }

        if ($this->AR_DATE <> '' && $this->AR_NO == '') {
            session()->flash('error', 'LHIO No. Requred');
            return;
        }
        $this->philHealthServices->UpdateAR($this->PHILHEALTH_ID, $this->AR_NO, $this->AR_DATE);

        $ar = [
            'AR_DATE' => $this->AR_DATE,
            'AR_NO'  => $this->AR_NO,
            'PHILHEALTH_ID' => $this->PHILHEALTH_ID
        ];
        $this->dispatch('ar-form-data', ar: $ar);
        session()->flash('message', 'Successfully save.');    
    }
    #[On('ar-form-show')]
    public function openModal($result)
    {
        $this->PHILHEALTH_ID = $result['PHILHEALTH_ID'];
        $data =  $this->philHealthServices->get($this->PHILHEALTH_ID);
        if ($data) {
            $this->CODE = $data->CODE;
            $this->DATE = $data->DATE;

            $this->AR_DATE = $data->AR_DATE ?? '';
            $this->AR_NO = $data->AR_NO ?? '';
            $this->showModal = true;
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
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
        return view('livewire.phil-health.ar-form');
    }
}
