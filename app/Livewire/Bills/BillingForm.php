<?php

namespace App\Livewire\Bills;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\BillingServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\DocumentTypeServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\ObjectServices;
use App\Services\PaymentTermServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

#[Title('Billing')]
class BillingForm extends Component
{

    public int $openStatus = 0;
    public int $ID;
    public int $VENDOR_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $ACCOUNTS_PAYABLE_ID;
    public string $DUE_DATE;
    public string $DISCOUNT_DATE;
    public float $DISCOUNT_PCT;
    public int $PAYMENT_TERMS_ID;
    public string $NOTES;
    public int $STATUS = 0;
    public string $STATUS_DESCRIPTION;
    public int $INPUT_TAX_ID;
    public float $INPUT_TAX_RATE;
    public int $INPUT_TAX_VAT_METHOD;
    public int $INPUT_TAX_ACCOUNT_ID;
    public float $INPUT_TAX_AMOUNT;
    public float $AMOUNT;
    public float $BALANCE_DUE;
    public bool $useAccount = false;

    public $vendorList = [];
    public $locationList = [];
    public $paymentTermList = [];
    public $taxList = [];
    public bool $Modify;
    private $billingServices;
    private $locationServices;
    private $contactServices;
    private $paymentTermServices;
    private $taxServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $objectServices;
    private $accountJournalServices;
    private $accountServices;
    private $documentTypeServices;
    private $itemInventoryServices;
    public function boot(ItemInventoryServices $itemInventoryServices, DocumentTypeServices $documentTypeServices, BillingServices $billingServices, LocationServices $locationServices, ContactServices $contactServices, PaymentTermServices $paymentTermServices, TaxServices $taxServices, UserServices $userServices, DocumentStatusServices $documentStatusServices, SystemSettingServices $systemSettingServices, ObjectServices $objectServices, AccountJournalServices $accountJournalServices, AccountServices $accountServices)
    {
        $this->billingServices = $billingServices;
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
        $this->paymentTermServices = $paymentTermServices;
        $this->taxServices = $taxServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->objectServices = $objectServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->accountServices = $accountServices;
        $this->documentTypeServices = $documentTypeServices;
        $this->itemInventoryServices = $itemInventoryServices;
    }

    public string $tab = 'item';
    #[On('select-tab')]
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }
    public function LoadDropdown()
    {
        $this->vendorList = $this->contactServices->getList(0);
        $this->locationList = $this->locationServices->getList();
        $this->paymentTermList = $this->paymentTermServices->getList();
        $this->taxList = $this->taxServices->getList();
    }
    public function getTax()
    {
        $tax = $this->taxServices->get($this->INPUT_TAX_ID);
        if ($tax) {
            $this->INPUT_TAX_RATE = (float) $tax->INPUT_TAX_RATE;
            $this->INPUT_TAX_VAT_METHOD = (int) $tax->VAT_METHOD;
            $this->INPUT_TAX_ACCOUNT_ID = (int) $tax->TAX_ACCOUNT_ID;
        }
    }

    private function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->CODE = $data->CODE;
        $this->DATE = $data->DATE;
        $this->DUE_DATE = $data->DUE_DATE ? $data->DUE_DATE : '';
        $this->DISCOUNT_DATE = $data->DISCOUNT_DATE ? $data->DISCOUNT_DATE : '';
        $this->DISCOUNT_PCT = $data->DISCOUNT_PCT ? $data->DISCOUNT_PCT : 0;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->VENDOR_ID = $data->VENDOR_ID;
        $this->PAYMENT_TERMS_ID = $data->PAYMENT_TERMS_ID ? $data->PAYMENT_TERMS_ID : 0;
        $this->NOTES = $data->NOTES;
        $this->AMOUNT = $data->AMOUNT ?? 0;
        $this->BALANCE_DUE = $data->BALANCE_DUE ?? 0;
        $this->STATUS = $data->STATUS;
        $this->INPUT_TAX_ID = $data->INPUT_TAX_ID > 0 ? $data->INPUT_TAX_ID : 0;
        $this->INPUT_TAX_RATE = $data->INPUT_TAX_RATE > 0 ? $data->INPUT_TAX_RATE : 0;
        $this->INPUT_TAX_AMOUNT = $data->INPUT_TAX_AMOUNT > 0 ? $data->INPUT_TAX_AMOUNT : 0;
        $this->INPUT_TAX_VAT_METHOD = $data->INPUT_TAX_VAT_METHOD > 0 ? $data->INPUT_TAX_VAT_METHOD : 0;
        $this->INPUT_TAX_ACCOUNT_ID = $data->INPUT_TAX_ACCOUNT_ID > 0 ? $data->INPUT_TAX_ACCOUNT_ID : 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
        $this->ACCOUNTS_PAYABLE_ID = $data->ACCOUNTS_PAYABLE_ID;

        if ($this->useAccount) {
            if ($this->billingServices->isItemTab($data->ID)) {
                $this->tab = "item";
                return;
            }
            $this->tab = "account";
        }
    }

    public function mount($id = null)
    {


        if (is_numeric($id)) {
            try {
                $Bill = $this->billingServices->get($id);
                if ($Bill) {
                    $this->LoadDropdown();
                    $this->getInfo($Bill);

                    $this->Modify = false;
                    return;
                }

                $errorMessage = 'Error occurred: Record not found. ';
                return Redirect::route('vendorsbills')->with('error', $errorMessage);
            } catch (\Exception $e) {
                $errorMessage = 'Error occurred: ' . $e->getMessage();
                return Redirect::route('vendorsbills')->with('error', $errorMessage);
            }
        }



        $this->LoadDropdown();
        $this->tab = "item";
        $this->Modify = true;
        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->DUE_DATE = '';
        $this->DISCOUNT_DATE = '';
        $this->DISCOUNT_PCT = 0;
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->VENDOR_ID = 0; //$this->contactServices->getFirstFromListByID(0);
        $this->PAYMENT_TERMS_ID = (int) $this->systemSettingServices->GetValue('DefaultPaymentTermsId');
        $this->NOTES = '';
        $this->AMOUNT = 0;
        $this->BALANCE_DUE = 0;
        $this->STATUS = 0;
        $this->INPUT_TAX_ID = (int) $this->systemSettingServices->GetValue('InputTaxId');
        $this->INPUT_TAX_RATE = 0;
        $this->INPUT_TAX_AMOUNT = 0;
        $this->INPUT_TAX_VAT_METHOD = 0;
        $this->INPUT_TAX_ACCOUNT_ID = 0;
        $this->STATUS_DESCRIPTION = "";
        $this->ACCOUNTS_PAYABLE_ID = $this->accountServices->getByName('Accounts Payable');
        $this->getTax();
    }
    public function getModify()
    {
        $this->Modify = true;
    }

    public function save()
    {

        try {
            if ($this->ID == 0) {

                $this->validate(
                    [
                        'VENDOR_ID' => 'required|not_in:0',
                        'INPUT_TAX_ID' => 'required|not_in:0',
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                        'PAYMENT_TERMS_ID' => 'required'
                    ],
                    [],
                    [
                        'VENDOR_ID' => 'Vendor',
                        'INPUT_TAX_ID' => 'Tax',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'PAYMENT_TERMS_ID' => 'Payment Terms'
                    ]
                );

                $this->getTax();
                DB::beginTransaction();


                $this->ID = $this->billingServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->VENDOR_ID,
                    $this->LOCATION_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->DUE_DATE,
                    $this->DISCOUNT_DATE,
                    $this->DISCOUNT_PCT,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID,
                    $this->INPUT_TAX_ID,
                    $this->INPUT_TAX_RATE,
                    $this->INPUT_TAX_AMOUNT,
                    $this->INPUT_TAX_VAT_METHOD,
                    $this->INPUT_TAX_ACCOUNT_ID,
                    $this->STATUS
                );

                DB::commit();
                return Redirect::route('vendorsbills_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $this->validate(
                    [
                        'VENDOR_ID' => 'required|not_in:0',
                        'CODE' => 'required|max:20|unique:bill,code,' . $this->ID,
                        'INPUT_TAX_ID' => 'required|not_in:0',
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                        'PAYMENT_TERMS_ID' => 'required'
                    ],
                    [],
                    [
                        'VENDOR_ID' => 'Vendor',
                        'CODE' => 'Reference No.',
                        'INPUT_TAX_ID' => 'Tax',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'PAYMENT_TERMS_ID' => 'Payment Terms'
                    ]
                );

                DB::beginTransaction();

                $this->getTax();
                $this->billingServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->VENDOR_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->DUE_DATE,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID,
                    $this->INPUT_TAX_ID,
                    $this->INPUT_TAX_RATE,
                    $this->INPUT_TAX_AMOUNT,
                    $this->INPUT_TAX_VAT_METHOD,
                    $this->INPUT_TAX_ACCOUNT_ID
                );

                DB::commit();
                $this->billingServices->getUpdateTaxItem($this->ID, $this->INPUT_TAX_ID);
                $getResult = $this->billingServices->ReComputed($this->ID);
                $this->getUpdateAmount($getResult);
                session()->flash('message', 'Successfully updated');
            }
            $this->Modify = false;
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('update-amount')]
    public function getUpdateAmount($result)
    {
        foreach ($result as $list) {
            $this->AMOUNT = $list['AMOUNT'];
            $this->BALANCE_DUE = $list['AMOUNT'];
            $this->INPUT_TAX_AMOUNT = $list['TAX_AMOUNT'];
        }


    }
    public function updateCancel()
    {
        $data = $this->billingServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
        $this->Modify = false;
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }

    private function ItemInventory(): bool
    {
        try {
            $SOURCE_REF_TYPE = (int) $this->documentTypeServices->getId('Bill');
            $data = $this->billingServices->ItemInventory($this->ID);
            if ($data) {
                $this->itemInventoryServices->InventoryExecute($data, $this->LOCATION_ID, $SOURCE_REF_TYPE, $this->DATE, true);
            }
            return true;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    public function OpenJournal()
    {

        $bills = (int) $this->objectServices->ObjectTypeID('BILL');
        $JOURNAL_NO = $this->accountJournalServices->getRecord($bills, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }

    }
    private function AccountJournal(): bool
    {
        try {

            $bills = (int) $this->objectServices->ObjectTypeID('BILL');
            $billItems = (int) $this->objectServices->ObjectTypeID('BILL_ITEMS');
            $billExpenses = (int) $this->objectServices->ObjectTypeID('BILL_EXPENSES');

            $JOURNAL_NO = $this->accountJournalServices->getJournalNo($bills, $this->ID) + 1;

            //Item
            $billCreditItemData = $this->billingServices->getBillItemJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billCreditItemData, $this->LOCATION_ID, $billItems, $this->DATE, "ASSET");
            //Expenses
            $billCreditExpensesData = $this->billingServices->getBillExpenseJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billCreditExpensesData, $this->LOCATION_ID, $billExpenses, $this->DATE, "EXPENSE");


            //Main
            $billData = $this->billingServices->getBillJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billData, $this->LOCATION_ID, $bills, $this->DATE, "AP");

            //Tax
            $billDataTax = $this->billingServices->getBillTaxJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billDataTax, $this->LOCATION_ID, $bills, $this->DATE, "TAX");

            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

            $debit_sum = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum) {
                return true;
            }
            session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
            return false;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    public function getPosted()
    {
        try {

            $count_item = (int) $this->billingServices->CountItems($this->ID, true);
            $count_expense = (int) $this->billingServices->CountItems($this->ID, false);
            $count = $count_item + $count_expense;
            if ($count == 0) {
                session()->flash('error', 'Item not found.');
                return;
            }
            DB::beginTransaction();
            if (!$this->ItemInventory()) {
                DB::rollBack();
                return;
            }

            if (!$this->AccountJournal()) {
                DB::rollBack();
                return;
            }

            $this->billingServices->StatusUpdate($this->ID, 15);
            DB::commit();
            $data = $this->billingServices->get($this->ID);
            if ($data) {
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            session()->flash('message', 'Successfully posted');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function render()
    {
        return view('livewire.bills.billing-form');
    }
}
