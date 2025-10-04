<?php
namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\LocationServices;
use App\Services\PatientReportServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Sales Report')]
class PatientSalesReport extends Component
{
    public bool $showFilter = false;

    public bool $refreshComponent = false;
    public int $PATIENT_ID;
    public int $LOCATION_ID;
    public $locationList = [];
    public $patientList  = [];
    public string $DATE_TRANSACTION_FROM;
    public string $DATE_TRANSACTION_TO;
    public string $tempName;
    public bool $is_add;
    public float $running_balance;

    public string $sc_code;
    public bool $is_code;
    private $locationServices;
    private $userServices;
    public $dataList                = [];
    public $preDataList             = [];
    public int $PREV_SC_ITEM_REF_ID = 0;
    public bool $not_to_charge      = false;
    public float $TOTAL_CHARGE      = 0;
    public float $TOTAL_PAID        = 0;
    public float $CASH_AMOUNT;
    public float $PRE_COLLECTION;
    public float $PHILHEALTH_AMOUNT;
    public float $DSWD_AMOUNT;
    public float $LINGAP_AMOUNT;
    public float $PCSO_AMOUNT;
    public float $OTHER_GL_AMOUNT;
    public float $OP_AMOUNT;
    public float $OVP_AMOUNT;

    public int $NO_OF_PATIENT   = 0;
    public int $NO_OF_TREATMENT = 0;
    private $contactServices;
    private $patientReportServices;

    public $filterPatient   = [];
    public $selectedPatient = [];
    public $filterItem      = [];
    public $selectedItem    = [];
    public $filterMethod    = [];
    public $selectedMethod  = [];
    public function boot(
        LocationServices $locationServices,
        UserServices $userServices,
        ContactServices $contactServices,
        PatientReportServices $patientReportServices,

    ) {
        $this->locationServices      = $locationServices;
        $this->userServices          = $userServices;
        $this->contactServices       = $contactServices;
        $this->patientReportServices = $patientReportServices;
    }
    public function updatedselectedPatient()
    {
        //$this->shortFilter();
    }
    public function updatedselectedItem()
    {
        //$this->shortFilter();
    }
    public function updatedselectedMethod()
    {
       // $this->shortFilter();
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID  = $this->userServices->getLocationDefault();

        $this->updatedLocationId();
        $this->resetFilter();
    }
    public function updatedLocationId()
    {
        $this->PATIENT_ID       = 0;
        $this->patientList      = $this->contactServices->getPatientList($this->LOCATION_ID);
        $this->refreshComponent = $this->refreshComponent ? false : true;

        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }


    public function shortFilter()
    {
        $this->NO_OF_PATIENT     = 0;
        $this->TOTAL_CHARGE      = 0;
        $this->TOTAL_PAID        = 0;
        $this->CASH_AMOUNT       = 0;
        $this->PHILHEALTH_AMOUNT = 0;
        $this->PRE_COLLECTION    = 0;
        $this->DSWD_AMOUNT       = 0;
        $this->LINGAP_AMOUNT     = 0;
        $this->PCSO_AMOUNT       = 0;
        $this->OTHER_GL_AMOUNT   = 0;
        $this->OP_AMOUNT         = 0;
        $this->OVP_AMOUNT        = 0;
        $this->NO_OF_TREATMENT   = 0;

        // $this->dataList = $this->patientReportServices->generateSalesReportData(
        //     $this->DATE_TRANSACTION_FROM,
        //     $this->DATE_TRANSACTION_TO,
        //     $this->LOCATION_ID,
        //     $this->selectedPatient,
        //     $this->selectedItem,
        //     $this->selectedMethod
        // );

        // $this->preDataList = $this->patientReportServices->generatePrevCollection(
        //     $this->DATE_TRANSACTION_FROM,
        //     $this->DATE_TRANSACTION_TO,
        //     $this->LOCATION_ID,
        //     $this->selectedPatient,
        //     $this->selectedItem,
        //     $this->selectedMethod
        // );

        // foreach ($this->preDataList as $data) {
        //     $this->PRE_COLLECTION = $this->PRE_COLLECTION + $data->PP_PAID ?? 0;
        // }
    }
    public function print()
    {
        return redirect()->away(route('reportspatient_sales_report_print', []));
    }
    public function resetFilter()
    {

        $this->DATE_TRANSACTION_FROM = $this->userServices->getTransactionDateDefault();
        $this->DATE_TRANSACTION_TO   = $this->userServices->getTransactionDateDefault();
        $this->PATIENT_ID            = 0;

        $this->selectedItem    = [];
        $this->selectedPatient = [];
        $this->selectedMethod  = [];
    }
    public function generateReport()
    {
        try {
            $url = route('reportspatient_sales_report_view', [
                'date_from'   => $this->DATE_TRANSACTION_FROM,
                'date_to'     => $this->DATE_TRANSACTION_TO,
                'location_id' => $this->LOCATION_ID,
                'patient'     => ! empty($this->selectedPatient) ? implode(',', $this->selectedPatient) : 'none',
                'item'        => ! empty($this->selectedItem) ? implode(',', $this->selectedItem) : 'none',
                'method'      => ! empty($this->selectedMethod) ? implode(',', $this->selectedMethod) : 'none',
            ]);

            $this->js("window.open(" . json_encode($url) . ", '_blank');");

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }

    }
    public function updatedDateTransactionFrom()
    {
        $this->selectedItem    = [];
        $this->selectedPatient = [];
        $this->selectedMethod  = [];
        $this->DATE_TRANSACTION_TO = $this->DATE_TRANSACTION_FROM;
        $this->updatedshowFilter();
    }
    public function updatedDateTransactionTo()
    {
        $this->selectedItem    = [];
        $this->selectedPatient = [];
        $this->selectedMethod  = [];
        $this->updatedshowFilter();
    }
    public function updatedshowFilter()
    {
        try {
         if ($this->showFilter) {
            $this->filterPatient    = $this->contactServices->getPatientListViaReport($this->LOCATION_ID, $this->DATE_TRANSACTION_FROM, $this->DATE_TRANSACTION_TO);
            $this->filterItem       = $this->patientReportServices->getItemListViaReport($this->LOCATION_ID, $this->DATE_TRANSACTION_FROM, $this->DATE_TRANSACTION_TO);
            $this->filterMethod     = $this->patientReportServices->getMethodListViaReport($this->LOCATION_ID, $this->DATE_TRANSACTION_FROM, $this->DATE_TRANSACTION_TO);

        } else {
            $this->selectedItem    = [];
            $this->selectedPatient = [];
            $this->selectedMethod  = [];
        }
        } catch (\Throwable $th) {
            //throw $th;
        }




        $this->refreshComponent = $this->refreshComponent ? false : true;
    }
    public function render()
    {
        return view('livewire.patient-report.patient-sales-report');
    }

}
