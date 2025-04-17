<?php

namespace App\Livewire\Import;

use App\Imports\ExcelDataImport;
use App\Models\Bill;
use App\Services\AccountServices;
use App\Services\BillingServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\XeroDataServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;


#[Title('Import Xero - Transaction Account')]
class XeroImportForm extends Component
{


    use WithFileUploads;
    public $file = null;

    public $dataList = [];
    public $locationList = [];

    public $locationid = 0;
    private $locationServices;
    private $xeroDataServices;
    private $dateServices;
    private $billingServices;
    private $accountServices;
    public function boot(LocationServices $locationServices, XeroDataServices $xeroDataServices, DateServices $dateServices, BillingServices $billingServices, AccountServices $accountServices)
    {
        $this->locationServices = $locationServices;
        $this->xeroDataServices = $xeroDataServices;
        $this->accountServices = $accountServices;
        $this->dateServices = $dateServices;
        $this->billingServices = $billingServices;
    }
    public function onMake(string $DATE, string $SOURCE_TYPE, string $REFERENCE) {

        $dataSend = [
            'DATE' => $DATE,
            'SOURCE_TYPE' => $SOURCE_TYPE,
            'REFERENCE' => $REFERENCE,
            'locationid' => $this->locationid
        ];
        

        $this->dispatch('dataSend', $dataSend);
    }   
    public function generate()
    {
        $this->dataList = [];
        $this->validate([
            'locationid' => 'required|exists:location,id'
        ], [], [
            'locationid' => 'Location'
        ]);

        $this->dataList = $this->xeroDataServices->viewData($this->locationid);
    }
    public function generateNoReference() {
        $this->dataList = [];
        $this->validate([
            'locationid' => 'required|exists:location,id'
        ], [], [
            'locationid' => 'Location'
        ]);

        $this->dataList = $this->xeroDataServices->viewNoRefrence($this->locationid);
    }
    public function makeEntry()
    {

    }

    public function newEntry(int $doctTypeId, int $accountId, string $CODE, $DATE, int $CONTACT_ID, float $DEBIT, float $CREDIT, string $DESCRIPTION): bool
    {

        switch ($doctTypeId) {
            case 1:
                if ($accountId == $this->billingServices->ACCOUNTS_PAYABLE_ID) {
                    $ID = $this->billingServices->Store(
                        $CODE,
                        $DATE,
                        $CONTACT_ID,
                        $this->locationid,
                        0,
                        '',
                        '',
                        0,
                        '',
                        $this->billingServices->ACCOUNTS_PAYABLE_ID,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0
                    );

                    $this->billingServices->UpdateAmount($ID, $CREDIT);
                    return true;
                } else {
                    $BILL_ID = $this->billingServices->CallBillHeader($CODE, $DATE, $this->locationid);
                    if ($BILL_ID == 0) {

                        return false;
                    }
                    $this->billingServices->ExpenseStore($BILL_ID, $accountId, $DEBIT, 0, 0, 0, $DESCRIPTION);

                    return true;
                }


            default:
                return false;
        }


    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();

    }
    public function render()
    {
        return view('livewire.import.xero-import-form');
    }
}
