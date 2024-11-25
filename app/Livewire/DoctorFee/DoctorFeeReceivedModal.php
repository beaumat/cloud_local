<?php

namespace App\Livewire\DoctorFee;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\PhilHealthServices;
use Livewire\Attributes\On;
use Livewire\Component;

class DoctorFeeReceivedModal extends Component
{   

    public $DOCTOR_NAME = '';
    public $dataList = [];
    public float $TOTAL = 0;
    public bool $showModal = false;
    public int $DOCTOR_ID = 0;
    public int $LOCATION_ID = 0;
    private $philHealthServices;
    private $dateServices;
    private $contactServices;
    public function boot(PhilHealthServices $philHealthServices, DateServices $dateServices, ContactServices $contactServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->dateServices = $dateServices;
        $this->contactServices = $contactServices;
    }

    #[On('remarks-open-list')]
    public function openModal($result)
    {
        $this->LOCATION_ID = $result['LOCATION_ID'];
        $this->DOCTOR_ID = $result['DOCTOR_ID'];
        $contactData = $this->contactServices->get($this->DOCTOR_ID, 4);
        $this->DOCTOR_NAME =  $contactData->PRINT_NAME_AS;
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
        $this->dispatch('doctor-fee-list-reload');
    }
    public function received(int $PHILHEALTH_ID)
    {
        try {
            $this->philHealthServices->setReceived($PHILHEALTH_ID, $this->dateServices->NowDate());
            $this->dispatch('doctor-fee-list-reload');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    public function render()
    {
        if ($this->DOCTOR_ID > 0 && $this->LOCATION_ID > 0) {
            $this->TOTAL = 0;
            $this->dataList = $this->philHealthServices->getListNonReceivedPFDoctor($this->DOCTOR_ID, $this->LOCATION_ID);
        }


        return view('livewire.doctor-fee.doctor-fee-received-modal');
    }
}
