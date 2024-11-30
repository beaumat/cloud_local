<?php

namespace App\Livewire\DoctorFee;

use App\Services\DateServices;
use App\Services\DoctorPFServices;
use App\Services\LocationServices;
use App\Services\PaymentPeriodServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Component;

class DoctorFeeList extends Component
{


    public int $LOCATION_ID;
    public $locationList = [];
    public $doctorList = [];
    private $doctorPFServices;
    private $locationServices;
    private $userServices;
    private $paymentPeriodServices;
    public int $YEAR;
    public $dataList = [];
    public $headerList = [];
    private $dateServices;
    public function boot(DoctorPFServices $doctorPFServices, LocationServices $locationServices, UserServices $userServices, PaymentPeriodServices $paymentPeriodServices, DateServices $dateServices)
    {
        $this->doctorPFServices = $doctorPFServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->paymentPeriodServices = $paymentPeriodServices;
        $this->dateServices = $dateServices;
    }

    public function mount()
    {
        $this->YEAR  = $this->dateServices->NowYear();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }


    #[On('doctor-fee-list-reload')]

    public function Generate()
    {
        $this->headerList  = $this->paymentPeriodServices->GetYear($this->YEAR, $this->LOCATION_ID);
    }
    public function updatedlocationid()
    {
        $this->doctorList  = [];
        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function openList(int $DOCTOR_ID)
    {
        $data = [
            'DOCTOR_ID' => $DOCTOR_ID,
            'LOCATION_ID' => $this->LOCATION_ID
        ];
        $this->dispatch('pf-open-list', result: $data);
    }
    public function openRemarks(int $DOCTOR_ID)
    {
        $data = [
            'DOCTOR_ID' => $DOCTOR_ID,
            'LOCATION_ID' => $this->LOCATION_ID
        ];
        $this->dispatch('remarks-open-list', result: $data);
    }
    public function render()
    {
        return view('livewire.doctor-fee.doctor-fee-list');
    }
}
