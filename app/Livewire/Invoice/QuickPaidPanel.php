<?php

namespace App\Livewire\Invoice;

use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\InvoiceServices;
use App\Services\PaymentPeriodServices;
use App\Services\PaymentServices;
use App\Services\PhilHealthServices;
use App\Services\TaxCreditServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class QuickPaidPanel extends Component
{


    public int $PAYMENT_METHOD_ID = 5;
    public int $ACCOUNTS_RECEIVABLE_ID;
    public int $INVOICE_ID;
    public int $CUSTOMER_ID;

    public bool $showModal = false;
    public int $TAX_ID;
    public string $CODE;
    public string $DATE;
    public string $PO_NUMBER;
    public string $DUE_DATE;
    public string $NAME;
    public float $AMOUNT;
    public float $BALANCE_DUE;
    public int $LOCATION_ID;
    private $invoiceServices;
    private $accountServices;
    private $paymentServices;
    private $taxCreditServices;
    private $contactServices;
    private $taxServices;
    public float $PAYMENT_AMOUNT;
    public float $TAX_AMOUNT;
    public int $PAYMENT_PERIOD_ID;
    public $taxList = [];
    public $paymentPeriodList = [];
    private $paymentPeriodServices;
    private $userServices;
    private $philHealthServices;
    public function boot(
        InvoiceServices $invoiceServices,
        PaymentServices $paymentServices,
        TaxCreditServices $taxCreditServices,
        ContactServices $contactServices,
        TaxServices $taxServices,
        AccountServices $accountServices,
        PaymentPeriodServices $paymentPeriodServices,
        UserServices $userServices,
        PhilHealthServices $philHealthServices
    ) {
        $this->invoiceServices = $invoiceServices;
        $this->paymentServices = $paymentServices;
        $this->taxCreditServices = $taxCreditServices;
        $this->contactServices = $contactServices;
        $this->taxServices = $taxServices;
        $this->accountServices = $accountServices;
        $this->paymentPeriodServices = $paymentPeriodServices;
        $this->userServices = $userServices;
        $this->philHealthServices = $philHealthServices;
    }
    #[On('quick-paid')]
    public function openModal($result)
    {


        $this->INVOICE_ID = $result['INVOICE_ID'];
        $this->TAX_ID = 0;
        $this->PAYMENT_AMOUNT  = 0;
        $this->PAYMENT_PERIOD_ID = 0;
        $data =  $this->invoiceServices->get($this->INVOICE_ID);
        if ($data) {
            $this->CUSTOMER_ID = $data->CUSTOMER_ID;
            $this->ACCOUNTS_RECEIVABLE_ID = $data->ACCOUNTS_RECEIVABLE_ID;
            $this->CODE = $data->CODE ?? '';
            $this->DATE = date('m/d/Y', strtotime($data->DATE));
            $this->DUE_DATE = date('m/d/Y', strtotime($data->DUE_DATE));
            $this->LOCATION_ID = $data->LOCATION_ID;
            $this->ReloadPeriodList();
            $this->PO_NUMBER = $data->PO_NUMBER ?? '';
            $this->AMOUNT =  $data->AMOUNT;
            $this->BALANCE_DUE =  $data->BALANCE_DUE;
            $con = $this->contactServices->getSingleData($this->CUSTOMER_ID);

            if ($con) {
                $this->NAME = $con->NAME ?? '';
            }
        }

        $this->taxList = $this->taxServices->getWTax();
        $this->showModal = true;
    }
    #[On('period-refresh')]
    public function ReloadPeriodList()
    {
        $this->paymentPeriodList = $this->paymentPeriodServices->List($this->LOCATION_ID);
    }
    public float $EWT_RATE;
    public float $AMOUNT_WITHHELD;
    public int $EWT_ACCOUNT_ID;
    public string $TAX_DESCRIPTION;
    public function updatedTaxId()
    {
        $tax = $this->taxServices->get($this->TAX_ID);
        if ($tax) {
            $this->EWT_RATE  = $tax->RATE ?? 0;
            $this->AMOUNT_WITHHELD = $this->BALANCE_DUE * ($this->EWT_RATE / 100);
            $this->EWT_ACCOUNT_ID = $tax->TAX_ACCOUNT_ID ?? 0;
            $acctData =   $this->accountServices->Get($this->EWT_ACCOUNT_ID);
            if ($acctData) {
                $this->TAX_DESCRIPTION = $acctData->NAME  ?? '';
            }
        } else {
            $this->EWT_RATE  = 0;
            $this->AMOUNT_WITHHELD = 0;
            $this->EWT_ACCOUNT_ID =  0;
            $this->TAX_DESCRIPTION = '';
        }
        $this->NewPaymentAMount();
    }
    private function NewPaymentAMount()
    {
        $this->PAYMENT_AMOUNT =   $this->BALANCE_DUE - $this->AMOUNT_WITHHELD;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function AddTAX()
    {
        if ($this->TAX_ID == 0) {
            return true;
        }


        $ID = $this->taxCreditServices->Store(
            "",
            $this->userServices->getTransactionDateDefault(),
            $this->CUSTOMER_ID,
            $this->TAX_ID,
            $this->EWT_RATE,
            $this->EWT_ACCOUNT_ID,
            $this->LOCATION_ID,
            '',
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

        $isGood = $this->taxCreditServices->getPosted($ID, $this->userServices->getTransactionDateDefault(), $this->LOCATION_ID);
        return $isGood;
    }

    public function AddPayment()
    {
        if ($this->paymentServices->PaymenIsOver($this->INVOICE_ID, $this->PAYMENT_AMOUNT) == true) {
            session()->flash('error', 'The payment exceeds the available balance');
            return false;
        }

        $period = $this->paymentPeriodServices->Get($this->PAYMENT_PERIOD_ID);

        if ($period) {

            $ID = $this->paymentServices->Store(
                "",
                $this->userServices->getTransactionDateDefault(),
                $this->CUSTOMER_ID,
                $this->LOCATION_ID,
                $this->PAYMENT_AMOUNT,
                $this->PAYMENT_AMOUNT,
                $this->PAYMENT_METHOD_ID,
                '',
                null,
                $period->RECEIPT_NO,
                null,
                '',
                $period->BANK_ACCOUNT_ID,
                0,
                true,
                $this->ACCOUNTS_RECEIVABLE_ID,
                $this->PAYMENT_PERIOD_ID
            );


            $this->paymentServices->PaymentInvoiceStore(
                $ID,
                $this->INVOICE_ID,
                0,
                $this->PAYMENT_AMOUNT,
                0,
                $this->ACCOUNTS_RECEIVABLE_ID
            );
            $this->invoiceServices->updateInvoiceBalance($this->INVOICE_ID);
            $isGood = $this->paymentServices->getPosted($ID, $this->userServices->getTransactionDateDefault(), $this->LOCATION_ID);
            if ($isGood) {
                $PHILHEALTH_ID =  $this->philHealthServices->Get_ID_by_INVOICE_ID($this->INVOICE_ID);
                if ($PHILHEALTH_ID > 0) {
                    $this->philHealthServices->makePayableForDoctor($PHILHEALTH_ID, $this->LOCATION_ID);
                }
                return true;
            }

            return false;
        }
    }

    public function save()
    {
        $this->validate(
            [
                'PAYMENT_AMOUNT' => 'required|numeric|not_in:0',
                'PAYMENT_PERIOD_ID' => 'required|numeric|not_in:0|exists:payment_period,id',
            ],
            [],
            [
                'PAYMENT_AMOUNT'    => 'Payment Amount',
                'PAYMENT_PERIOD_ID' => 'Payment Period'
            ]
        );

        DB::beginTransaction();
        try {

            $isgood = $this->AddTAX();
            if (!$isgood) {
                DB::rollBack();
                session()->flash('error','rollback tax');
                return;
            }

            $isPayGood = $this->AddPayment();
            if (!$isPayGood) {
                DB::rollBack();
                session()->flash('error','rollback payment');
                return;
            }

            $this->invoiceServices->ReComputed($this->INVOICE_ID);
            DB::commit();
            $this->closeModal();
            $this->dispatch('quick-paid-reload');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.invoice.quick-paid-panel');
    }
}
