<?php

namespace App\Livewire\Invoice;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\InvoiceServices;
use App\Services\TaxCreditServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class TaxCredit extends Component
{
    #[Reactive]
    public int $INVOICE_ID;
    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $ACCOUNTS_RECEIVABLE_ID;
    #[Reactive]
    public int $INVOICE_STATUS_ID;
    #[Reactive]
    public float $AMOUNT;
    public int $openStatus = 0;
    public float $AMOUNT_WITHHELD;
    public string $NOTES = '';
    public string $TAX_DESCRIPTION;
    public int $EWT_ID;
    public float $EWT_RATE;
    public int $EWT_ACCOUNT_ID;
    public $dataList = [];
    public $accountList = [];
    public $taxList =  [];
    private $taxCreditServices;
    private $userServices;
    private $invoiceServices;
    private $accountJournalServices;
    private $taxServices;
    private $accountServices;
    public function boot(
        TaxCreditServices $taxCreditServices,
        UserServices $userServices,
        InvoiceServices $invoiceServices,
        TaxServices $taxServices,
        AccountJournalServices $accountJournalServices,
        AccountServices $accountServices
    ) {
        $this->taxCreditServices = $taxCreditServices;
        $this->userServices = $userServices;
        $this->invoiceServices = $invoiceServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->taxServices = $taxServices;
        $this->accountServices = $accountServices;
    }
    public function clearValue()
    {
        $this->EWT_ID = 0;
        $this->AMOUNT_WITHHELD = 0;
        $this->TAX_DESCRIPTION = '';
        $this->NOTES = '';
        $this->EWT_ACCOUNT_ID = 0;
        $this->EWT_RATE = 0;
    }
    public function LoadList()
    {

        $this->taxList = $this->taxServices->getWTax();
    }
    public function updatedEwtId()
    {
        $tax = $this->taxServices->get($this->EWT_ID);
        if ($tax) {
            $this->EWT_RATE  = $tax->RATE ?? 0;
            $this->AMOUNT_WITHHELD = $this->AMOUNT * ($this->EWT_RATE / 100);
            $this->EWT_ACCOUNT_ID = $tax->TAX_ACCOUNT_ID ?? 0;
            $acctData =   $this->accountServices->Get($this->EWT_ACCOUNT_ID);
            if ($acctData) {
                $this->TAX_DESCRIPTION = $acctData->NAME  ?? '';
            }
        } else {
            $this->clearValue();
        }
    }
    public function AddPayment()
    {

        $this->validate([
            'EWT_ID'            => 'required|exists:tax,id',
            'EWT_RATE'          => 'required|numeric|not_in:0',
            'EWT_ACCOUNT_ID'    => 'required|exists:account,id',
            'AMOUNT_WITHHELD'   => 'required|numeric|not_in:0'
        
        ], [], [
            'EWT_ID'            => 'Tax Type',
            'EWT_RATE'          => 'Rate',
            'EWT_ACCOUNT_ID'    => 'Account',
            'AMOUNT_WITHHELD'   => 'Withholding Tax Amount'
        ]);

        DB::beginTransaction();
        try {
            $ID = $this->taxCreditServices->Store(
                "",
                $this->userServices->getTransactionDateDefault(),
                $this->CUSTOMER_ID,
                $this->EWT_ID,
                $this->EWT_RATE,
                $this->EWT_ACCOUNT_ID,
                $this->LOCATION_ID,
                $this->NOTES,
                $this->ACCOUNTS_RECEIVABLE_ID
            );


            $this->taxCreditServices->StoreInvoice(
                $ID,
                $this->INVOICE_ID,
                $this->AMOUNT_WITHHELD,
                $this->ACCOUNTS_RECEIVABLE_ID
            );
            $total  = $this->taxCreditServices->GetTotal($ID);
            $this->taxCreditServices->setTotal($ID, $total);
            $this->invoiceServices->updateInvoiceBalance($this->INVOICE_ID);

            $isGood = $this->getPosted($ID, $this->userServices->getTransactionDateDefault());
            if ($isGood) {
                DB::commit();
                $getResult = $this->invoiceServices->ReComputed($this->INVOICE_ID);
                $this->dispatch('update-amount', result: $getResult);
                $this->clearValue();
            } else {
                DB::rollBack();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function TaxCreditList()
    {
        $this->dataList = $this->taxCreditServices->InvoiceTaxCreditList($this->INVOICE_ID, $this->CUSTOMER_ID);
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }

    public function getPosted(int $TAX_CREDIT_ID, string $DATE): bool
    {

        $taxCredit = $this->taxCreditServices->object_type_tax_credit;
        $taxCreditInvoices = $this->taxCreditServices->object_type_tax_credit_invoices;
        $JOURNAL_NO  = (int) $this->accountJournalServices->getRecord($taxCredit, $TAX_CREDIT_ID);
        if ($JOURNAL_NO  == 0) {
            $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($taxCredit, $TAX_CREDIT_ID) + 1;
        }

        $paymentData = $this->taxCreditServices->TaxCreditJournal($TAX_CREDIT_ID);

        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $paymentData,
            $this->LOCATION_ID,
            $taxCredit,
            $DATE,
            "TAX"
        );


        $paymentDataR = $this->taxCreditServices->TaxCreditJournalRemaining($TAX_CREDIT_ID);

        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $paymentDataR,
            $this->LOCATION_ID,
            $taxCredit,
            $DATE,
            "A/R"
        );


        $paymentInvoiceData = $this->taxCreditServices->TaxCreditInvoicejournal($TAX_CREDIT_ID);

        $this->accountJournalServices->JournalExecute(
            $JOURNAL_NO,
            $paymentInvoiceData,
            $this->LOCATION_ID,
            $taxCreditInvoices,
            $DATE,
            "A/R"
        );


        $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
        $debit_sum = (float) $data['DEBIT'];
        $credit_sum = (float) $data['CREDIT'];

        if ($debit_sum == $credit_sum) {
            $this->taxCreditServices->StatusUpdate($TAX_CREDIT_ID, 15);
            return true;
        }

        return false;
    }

    public function render()
    {
        $this->LoadList();
        $this->TaxCreditList();

        return view('livewire.invoice.tax-credit');
    }
}
