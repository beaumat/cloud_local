<?php

namespace App\Livewire\PatientReport;


use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
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
    private $dateServices;
    private $locationServices;
    private $userServices;
    public $dataList = [];


    public float $TOTAL_CHARGE = 0;
    public float $TOTAL_PAID = 0;

    private $serviceChargeServices;
    private $patientPaymentServices;
    public function boot(
        DateServices $dateServices,
        LocationServices $locationServices,
        UserServices $userServices,
        ServiceChargeServices $serviceChargeServices,
        PatientPaymentServices $patientPaymentServices
    ) {
        $this->dateServices = $dateServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->patientPaymentServices = $patientPaymentServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->resetFilter();
    }

    public function showfilter()
    {
        $this->TOTAL_CHARGE = $this->serviceChargeServices->getSum($this->DATE_TRANSACTION_FROM, $this->DATE_TRANSACTION_TO, $this->LOCATION_ID, 0);
        $this->TOTAL_PAID = $this->patientPaymentServices->getSumApplied($this->DATE_COLLECTION_FROM, $this->DATE_COLLECTION_TO, $this->LOCATION_ID, 0);
        $this->dataList = $this->generateData($this->DATE_COLLECTION_FROM, $this->DATE_COLLECTION_TO, $this->DATE_TRANSACTION_FROM, $this->DATE_TRANSACTION_TO, $this->LOCATION_ID);
    }
    private function generateData(string $collectFrom, string $collectTo, string $transFrom, string $transTo, int  $locatoinId)
    {
        $results = DB::table('service_charges_items as sci')
            ->select(
                'sc.ID as SC_ID',
                'sc.CODE as SC_CODE',
                'sc.DATE as SC_DATE',
                'sci.AMOUNT as SC_AMOUNT',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as PATIENT_NAME"),
                'i.CODE as ITEM_CODE',
                'i.DESCRIPTION as ITEM_NAME',
                'pp.ID as PP_ID',
                'pp.DATE as PP_DATE',
                'pp.CODE as PP_CODE',
                'pm.DESCRIPTION as PAYMENT_METHOD',
                DB::raw('IFNULL(pp.AMOUNT, 0) as PP_DEPOSIT'),
                DB::raw('IFNULL(ppc.AMOUNT_APPLIED, 0) as PP_PAID'),
                'l.NAME as LOCATION_NAME',
                DB::raw('(select d.PRINT_NAME_AS  from patient_doctor  as pd join contact as d on d.ID = pd.DOCTOR_ID where pd.PATIENT_ID = c.ID limit 1) as DOCTOR_NAME ')
            )
            ->join('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->join('location as l', 'l.ID', '=', 'sc.LOCATION_ID')
            ->join('contact as c', 'c.ID', '=', 'sc.PATIENT_ID')
            ->leftJoin('patient_payment_charges as ppc', 'ppc.SERVICE_CHARGES_ITEM_ID', '=', 'sci.ID')
            ->leftJoin('patient_payment as pp', function ($join) {
                $join->on('pp.ID', '=', 'ppc.PATIENT_PAYMENT_ID')
                    ->on('pp.LOCATION_ID', '=', 'sc.LOCATION_ID');
            })
            ->leftJoin('payment_method as pm', 'pm.ID', '=', 'pp.PAYMENT_METHOD_ID')
            ->where(function ($query) use (&$collectFrom, &$collectTo, &$transFrom, &$transTo) {
                $query->whereBetween('pp.DATE', [$collectFrom, $collectTo])
                    ->orWhereBetween('sc.DATE', [$transFrom, $transTo]);
            })
            ->when($locatoinId > 0, function ($query) use (&$locatoinId) {
                $query->where('sc.LOCATION_ID', $locatoinId);
            })
            ->orderBy('c.LAST_NAME')
            ->orderBy('sc.CODE')
            ->get();

        return $results;
    }

    public function resetFilter()
    {
        $this->DATE_COLLECTION_FROM = $this->dateServices->NowDate();
        $this->DATE_COLLECTION_TO = $this->dateServices->NowDate();

        $this->DATE_TRANSACTION_FROM = $this->dateServices->NowDate();
        $this->DATE_TRANSACTION_TO = $this->dateServices->NowDate();

        $this->LOCATION_ID = $this->userServices->getLocationDefault();
    }
    public function render()
    {
        return view('livewire.patient-report.patient-sales-report');
    }
}
