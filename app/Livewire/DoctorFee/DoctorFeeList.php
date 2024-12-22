<?php

namespace App\Livewire\DoctorFee;

use App\Services\DateServices;
use App\Services\DoctorPFServices;
use App\Services\LocationServices;
use App\Services\PaymentPeriodServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Doctor Professional Fee')]
class DoctorFeeList extends Component
{

    public int $LOCATION_ID;
    public int $YEAR;
    public array $SelectedPaymentPeriod = [];
    public $filterPaymentPeriod = [];
    public bool $refreshComponent = false;
    public $locationList = [];
    public $doctorList = [];
    private $doctorPFServices;
    private $locationServices;
    private $userServices;
    private $paymentPeriodServices;

    public $dataList = [];
    public $headerList = [];
    public $totalList = [];
    private $dateServices;


    public $DATE_FROM;
    public $DATE_TO;
    public int $row;
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
        $this->DATE_TO = $this->dateServices->NowDate();
        $this->DATE_FROM = $this->dateServices->GetFirstDay_Year($this->DATE_TO);
    }
    private function getPeriodList()
    {
        $this->filterPaymentPeriod = $this->paymentPeriodServices->getPeriodbyYear($this->LOCATION_ID, $this->YEAR);
        $this->refreshComponent =  $this->refreshComponent ? false : true;
    }
    #[On('doctor-fee-list-reload')]
    public function Generate()
    {
        $this->getPeriodList();
        $this->filterPeriod();
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

    public function filterPeriod()
    {

        $dataHeader  = $this->paymentPeriodServices->GetData($this->LOCATION_ID, $this->DATE_FROM, $this->DATE_TO);

        $this->headerList = [];
        $this->doctorList = [];
        $this->totalList =  [];

        foreach ($dataHeader as $list) {

            $dataRow = [
                'ID'            => $list->ID,
                'RECEIPT_NO'    => $list->RECEIPT_NO,
                'DATE_FROM'     => $list->DATE_FROM,
                'DATE_TO'       => $list->DATE_TO,
                'DATE'          => $list->DATE
            ];

            $this->headerList[] = $dataRow;
        }

        $dataDoctorActive = $this->paymentPeriodServices->getDoctorByDatePeriod($this->LOCATION_ID, $this->DATE_FROM, $this->DATE_TO);

        $this->totalList = [];
        $daTotal = [];
        foreach ($dataDoctorActive as $list) {
            $dataH = [
                'DOCTOR_ID'     => $list->DOCTOR_ID,
                'DOCTOR_NAME'   => $list->DOCTOR_NAME,
            ];
            $row = 0;

            foreach ($dataHeader as $listHeader) {
                $row++;

                $AMOUNT = (float)  $this->paymentPeriodServices->getDoctorFeeTotal(
                    $this->LOCATION_ID,
                    $listHeader->ID,
                    $list->DOCTOR_ID
                );

                $dataH[$row] = $AMOUNT;

                $PREV_AMOUNT   =   $daTotal[$row] ?? 0;
                $daTotal[$row] =  $PREV_AMOUNT  + $AMOUNT;
            }
            $this->row = $row;
            $this->doctorList[] = $dataH;
        }
        $row = 0;
        foreach ($dataHeader as $listHeader) {
            $row++;
            $this->totalList[$row] =     $daTotal[$row];
        }
    }


    public function render()
    {
        return view('livewire.doctor-fee.doctor-fee-list');
    }
}
