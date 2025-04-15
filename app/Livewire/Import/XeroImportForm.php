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
    public $year;
    public $month;

    public $yearList = [];
    public $monthList = [];

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

    public function generate()
    {

        $this->validate([
            'locationid' => 'required|exists:location,id'
        ], [], [
            'locationid' => 'Location'
        ]);

        $this->dataList = $this->xeroDataServices->viewData($this->locationid, $this->year, $this->month);
    }
    public function makeEntry()
    {

    }
    public function GetReferenceEntry(string $REF)
    {
        $data = $this->xeroDataServices->callReference($REF);
        DB::beginTransaction();
        try {
            foreach ($data as $list) {
                $docTypeId = (int) $this->xeroDataServices->DocumentType($list->SOURCE_TYPE);
                $accountId = (int) $this->accountServices->getAccountNameIntoId($list->ACCOUNT);

                if ($docTypeId == 0) {
                    DB::rollBack();
                    session()->flash('error', 'Error entry: document ID not found');
                }


                if ($accountId == 0) {
                    DB::rollBack();
                    session()->flash('error', 'Error entry: Account ID not found');
                }

                $CONTACT_ID = $this->xeroDataServices->getCONTACT_NAME_VIA_DESCRIPTION($list->DESCRIPTION);
                
                $result = (bool) $this->newEntry(
                    $docTypeId,
                    $accountId,
                    $list->REFERENCE,
                    $list->DATE,
                    $CONTACT_ID,
                    $list->DEBIT,
                    $list->CREDIT,
                    $list->DESCRIPTION
                );

                if ($result) {

                }

            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Error entry:' . $th->getMessage());

        }
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
        $this->yearList = $this->dateServices->YearList();
        $this->monthList = $this->dateServices->MonthList();

        $this->year = $this->dateServices->NowYear();
        $this->month = $this->dateServices->NowMonth();
    }
    public function render()
    {
        return view('livewire.import.xero-import-form');
    }
}
