<?php

namespace App\Livewire\Bills;

use App\Services\AccountServices;
use App\Services\BillingServices;
use App\Services\ClassServices;
use App\Services\ComputeServices;
use App\Services\ObjectServices;
use App\Services\TaxServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillingFormAccounts extends Component
{
    public int $OBJECT_TYPE;

    #[Reactive]
    public int $JOURNAL_NO;
    #[Reactive]
    public int $BILL_ID;
    #[Reactive]
    public int $TAX_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public string $DATE;

    public int $ID;
    public int $LINE_NO;
    public int $ACCOUNT_ID;
    public int $AMOUNT;

    public int $TAXABLE;
    public float $TAXABLE_AMOUNT;
    public float $TAX_AMOUNT;
    public string $PARTICULARS;
    public int $CLASS_ID;
    public int $STATUS;
    public int $openStatus = 2;
    
    public $expenses = [];
    public bool $codeBase = false;
    public $acctDescList = [];
    public $acctCodeList = [];
    public $classList = [];
    public string $ACCOUNT_CODE;
    public string $ACCOUNT_DESCRIPTION;
    public $saveSuccess;
    public $editExpensesId = null;
    public float $lineAmount;
    public bool $lineTaxable;
    public string $lineParticulars;
    public int $lineClassId;
    public float $lineTaxableAmt;
    public float $lineTaxAmount;
    private $billingServices;
    private $accountServices;
    private $classServices;
    private $taxServices;
    private $computeServices;
    private $objectServices;


    public function boot(
        BillingServices $billingServices,
        AccountServices $accountServices,
        ClassServices $classServices,
        TaxServices $taxServices,
        ComputeServices $computeServices,
        ObjectServices $objectServices,
    ) {
        $this->billingServices = $billingServices;
        $this->accountServices = $accountServices;
        $this->classServices = $classServices;
        $this->taxServices = $taxServices;
        $this->computeServices = $computeServices;
        $this->objectServices = $objectServices;
    }
    public function updatedaccountid()
    {
        $acct = $this->accountServices->get($this->ACCOUNT_ID);

        if ($acct) {
            $this->ACCOUNT_CODE = $acct->TAG ? $acct->TAG : '';
            $this->ACCOUNT_DESCRIPTION = $acct->NAME;
            $this->TAXABLE = true;
            $this->PARTICULARS = '';
        }
    }
    public function updatedcodebase()
    {
        if ($this->codeBase) {
            return $this->acctCodeList = $this->accountServices->getAccount(true);
        }
        return $this->acctDescList = $this->accountServices->getAccount(false);
    }

    public function mount()
    {
        $this->OBJECT_TYPE = (int) $this->objectServices->ObjectTypeID('BILL_EXPENSES');
        $this->ACCOUNT_ID = 0;
        $this->AMOUNT = 0;
        $this->PARTICULARS = '';
        $this->TAXABLE = true;
        $this->updatedcodeBase();
        $this->CLASS_ID = 0;
        $this->classList = $this->classServices->GetList();
    }


    public function saveExpenses()
    {

        $this->validate(
            [
                'ACCOUNT_ID' => [
                    'required',
                    'not_in:0'
                ],
                'AMOUNT' => 'required|not_in:0'
            ],
            [],
            [
                'ACCOUNT_ID' => 'Account',
                'AMOUNT' => 'Amount'
            ]
        );

        $recordExists = (bool) DB::table('bill_expenses')->where('BILL_ID', $this->BILL_ID,)->where('ACCOUNT_ID', $this->ACCOUNT_ID)->exists();

        if ($recordExists) {
            session()->flash('error', 'Account already exists');
            return;
        }


        try {
            $taxRate = $this->taxServices->getRate($this->TAX_ID);

            $tax_result = $this->computeServices->ItemComputeTax($this->AMOUNT, $this->TAXABLE, $this->TAX_ID, $taxRate);

            if ($tax_result) {
                $this->TAXABLE_AMOUNT = $tax_result['TAXABLE_AMOUNT'];
                $this->TAX_AMOUNT = $tax_result['TAX_AMOUNT'];
            }

            $EXPENSES_ID =  (int)  $this->billingServices->ExpenseStore(
                $this->BILL_ID,
                $this->ACCOUNT_ID,
                $this->AMOUNT,
                $this->TAXABLE,
                $this->TAXABLE_AMOUNT,
                $this->TAX_AMOUNT,
                $this->PARTICULARS,
                $this->CLASS_ID
            );

            $this->AccountJournal($EXPENSES_ID, $this->ACCOUNT_ID, 0, $this->TAXABLE ? $this->TAXABLE_AMOUNT :  $this->AMOUNT, $this->AMOUNT >= 0 ? 0 : 1, 'EXPENSES');
            $this->ACCOUNT_ID = 0;
            $this->AMOUNT = 0;
            $this->TAXABLE = true;
            $this->TAXABLE_AMOUNT = 0;
            $this->TAX_AMOUNT = 0;
            $this->PARTICULARS = '';
            $this->CLASS_ID = 0;
            $this->ACCOUNT_CODE = '';
            $this->ACCOUNT_DESCRIPTION = '';

            $getResult = $this->billingServices->ReComputed($this->BILL_ID);
            $this->dispatch('update-amount', result: $getResult);
            $this->saveSuccess = $this->saveSuccess ? false : true;
            $this->updatedcodeBase();
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function editExpenses(int $lineId, float $amount, bool $tax, string $particulars, int $Class_id)
    {
        $this->lineAmount = $amount;
        $this->lineTaxable = $tax;
        $this->lineParticulars = $particulars;
        $this->lineClassId = $Class_id;
        $this->editExpensesId = $lineId;
    }
    public function cancelExpenses()
    {
        $this->editExpensesId = null;
    }
    public function updateExpenses(int $id)
    {
        $this->validate(
            [
                'lineAmount' => 'required|not_in:0'
            ],
            [],
            [

                'lineAmount' => 'Amount'
            ]
        );

        try {
            $taxRate = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($this->lineAmount, $this->lineTaxable, $this->TAX_ID, $taxRate);
            if ($tax_result) {
                $this->lineTaxableAmt = $tax_result['TAXABLE_AMOUNT'];
                $this->lineTaxAmount = $tax_result['TAX_AMOUNT'];
            }

            $this->billingServices->ExpenseUpdate(
                $id,
                $this->BILL_ID,
                $this->lineAmount,
                $this->lineTaxable,
                $this->lineTaxableAmt,
                $this->lineTaxAmount,
                $this->lineParticulars,
                $this->lineClassId
            );

            $this->AccountJournal($id, 0, 0, $this->lineTaxable ? $this->lineTaxableAmt :  $this->lineAmount, $this->lineAmount >= 0 ? 0 : 1, 'EXPENSES');

            $getResult = $this->billingServices->ReComputed($this->BILL_ID);
            $this->dispatch('update-amount', result: $getResult);
            $this->cancelExpenses();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    private function AccountJournal(int $ID, int $ACCOUNT_ID, int $SUBSIDIARY_ID, float $AMOUNT, int $TYPE, string $EXTENSION)
    {
        $this->billingServices->updateJournal($ID, $ACCOUNT_ID, $this->JOURNAL_NO, $this->LOCATION_ID, $this->DATE, $SUBSIDIARY_ID, $this->OBJECT_TYPE, $AMOUNT, $TYPE, $EXTENSION);
    }
    public function deleteExpenses(int $id)
    {
        $this->AccountJournal($id, 0, 0, 0, 0, 'EXPENSES');
        $this->billingServices->ExpenseDelete($id, $this->BILL_ID);
        $getResult = $this->billingServices->ReComputed($this->BILL_ID);
        $this->dispatch('update-amount', result: $getResult);
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
        $this->expenses = $this->billingServices->ExpenseView($this->BILL_ID);
        return view('livewire.bills.billing-form-accounts');
    }
}
