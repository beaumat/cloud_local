<?php

namespace App\Livewire\PatientReport;

use App\Exports\PatientSalesReportExport;
use App\Services\ContactServices;
use App\Services\PatientReportServices;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\LocationServices;

use App\Services\UserServices;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Sales Report')]
class PatientSalesReport extends Component
{

    public int $PATIENT_ID;
    public int $LOCATION_ID;
    public $locationList = [];
    public $patientList = [];
    public string $DATE_COLLECTION_FROM;
    public string $DATE_COLLECTION_TO;
    public string $DATE_TRANSACTION_FROM;
    public string $DATE_TRANSACTION_TO;
    public string $tempName;
    public bool $is_add;
    public float $running_balance;
    public string $sc_code;
    public bool $is_code;
    private $locationServices;
    private $userServices;
    public $dataList = [];

    public int $PREV_SC_ITEM_REF_ID = 0;
    public bool $not_to_charge = false;
    public float $TOTAL_CHARGE = 0;


    public float $TOTAL_PAID = 0;
    public float $CASH_AMOUNT;
    public float $PHILHEALTH_AMOUNT;
    public float $DSWD_AMOUNT;
    public float $LINGAP_AMOUNT;
    public float $PCSO_AMOUNT;
    public float $OTHER_GL_AMOUNT;



    public int $NO_OF_PATIENT = 0;


    private $contactServices;
    private $patientReportServices;
    public function boot(LocationServices $locationServices, UserServices $userServices, ContactServices $contactServices, PatientReportServices $patientReportServices)
    {

        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->contactServices = $contactServices;
        $this->patientReportServices = $patientReportServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->patientList = $this->contactServices->getList(3);
        $this->resetFilter();
    }
    public function export()
    {
        return Excel::download(new PatientSalesReportExport(
            $this->patientReportServices,
            $this->DATE_TRANSACTION_FROM,
            $this->DATE_TRANSACTION_TO,
            $this->DATE_COLLECTION_FROM,
            $this->DATE_COLLECTION_TO,
            $this->LOCATION_ID,
            $this->PATIENT_ID
        ), 'sales-report.xlsx');
    }
    public function showfilter()
    {
        $this->NO_OF_PATIENT  = 0;
        $this->TOTAL_CHARGE = 0;
        $this->TOTAL_PAID = 0;
        $this->CASH_AMOUNT = 0;
        $this->PHILHEALTH_AMOUNT = 0;
        $this->DSWD_AMOUNT = 0;
        $this->LINGAP_AMOUNT = 0;
        $this->PCSO_AMOUNT = 0;
        $this->OTHER_GL_AMOUNT = 0;


        $this->dataList = $this->patientReportServices->generateSalesReportData(
            $this->DATE_COLLECTION_FROM,
            $this->DATE_COLLECTION_TO,
            $this->DATE_TRANSACTION_FROM,
            $this->DATE_TRANSACTION_TO,
            $this->LOCATION_ID,
            $this->PATIENT_ID
        );
    }

    public function resetFilter()
    {
        $this->DATE_COLLECTION_FROM = '';
        $this->DATE_COLLECTION_TO = '';

        $this->DATE_TRANSACTION_FROM = $this->userServices->getTransactionDateDefault();
        $this->DATE_TRANSACTION_TO = $this->userServices->getTransactionDateDefault();

        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->PATIENT_ID = 0;
    }

    public function render()
    {

        return view('livewire.patient-report.patient-sales-report');
    }
}
