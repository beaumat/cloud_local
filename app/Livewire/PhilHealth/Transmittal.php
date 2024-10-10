<?php

namespace App\Livewire\PhilHealth;

use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\InvoiceServices;
use App\Services\PatientPaymentServices;
use App\Services\PaymentMethodServices;
use App\Services\PaymentServices;
use App\Services\PhilHealthServices;
use App\Services\ServiceChargeServices;
use App\Services\TaxCreditServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Transmittal extends Component
{
    #[Reactive]
    public int $PHILHEALTH_ID;
    public int $PHIL_ACCOUNT_ID = 0;
    public int $ACCOUNTS_RECEIVABLE_ID;
    public int $PATIENT_PAYMENT_ID;
    public float $INVOICE_AMOUNT = 0;
    public float $RECEIVED_AMOUNT = 0;
    public float $TAX_CREDIT_AMOUNT = 0;
    public float $INVOICE_BALANCE = 0;


    public int $INVOICE_ID;
    private $philHealthServices;
    private $patientPaymentServices;
    private $invoiceServices;
    private $serviceChargeServices;
    private $dateServices;
    private $accountServices;
    private $paymentMethodServices;
    private $hemoServices;
    private $paymentServices;
    private $taxCreditServices;
    public function boot(
        PhilHealthServices $philHealthServices,
        PatientPaymentServices $patientPaymentServices,
        InvoiceServices $invoiceServices,
        ServiceChargeServices $serviceChargeServices,
        DateServices $dateServices,
        AccountServices $accountServices,
        PaymentMethodServices $paymentMethodServices,
        HemoServices $hemoServices,
        PaymentServices $paymentServices,
        TaxCreditServices $taxCreditServices
    ) {
        $this->philHealthServices = $philHealthServices;
        $this->patientPaymentServices = $patientPaymentServices;
        $this->invoiceServices = $invoiceServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->dateServices = $dateServices;
        $this->accountServices = $accountServices;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->hemoServices = $hemoServices;
        $this->paymentServices = $paymentServices;
        $this->taxCreditServices = $taxCreditServices;
    }
    public function mount()
    {
        $data = $this->philHealthServices->getTwoId($this->PHILHEALTH_ID);
        if ($data) {
            $this->INVOICE_ID = $data['INVOICE_ID'];
            $this->PATIENT_PAYMENT_ID = $data['PATIENT_PAYMENT_ID'];
            $this->getDetails();
        }
        $PayMethod = $this->paymentMethodServices->get($this->patientPaymentServices->PHILHEALTH_METHOD_ID);
        $acctData = $this->accountServices->get($PayMethod->GL_ACCOUNT_ID ?? 0);

        if ($acctData) {
            $this->PHIL_ACCOUNT_ID =  $acctData->ID ?? 0;
        }

        $this->ACCOUNTS_RECEIVABLE_ID = (int) $this->accountServices->getByName('Accounts Receivable');
    }
    public function makeInvoice()
    {

        if ($this->INVOICE_ID > 0) {
            return "";
        }

        DB::beginTransaction();
        try {

            //code...
            if ($this->PATIENT_PAYMENT_ID  == 0) {
                $PH_DATA  = $this->philHealthServices->get($this->PHILHEALTH_ID);
                if ($PH_DATA) {
                    if (empty($PH_DATA->AR_NO)) {
                        session()->flash('error', 'Invalid Lhio No. is required.');
                        DB::rollBack();
                        return;
                    }
                    $this->PATIENT_PAYMENT_ID = $this->patientPaymentServices->Store(
                        "",
                        $this->dateServices->NowDate(),
                        $PH_DATA->CONTACT_ID,
                        $PH_DATA->LOCATION_ID,
                        $PH_DATA->P1_TOTAL,
                        0,
                        $this->patientPaymentServices->PHILHEALTH_METHOD_ID,
                        "",
                        null,
                        $PH_DATA->AR_NO,
                        $PH_DATA->AR_DATE,
                        "",
                        $this->PHIL_ACCOUNT_ID,
                        0,
                        0,
                        $this->ACCOUNTS_RECEIVABLE_ID,
                        $this->PHILHEALTH_ID,
                        0,
                        0,
                        0
                    );




                    $scDataItem = $this->hemoServices->GetSummary(
                        $PH_DATA->CONTACT_ID,
                        $PH_DATA->LOCATION_ID,
                        $PH_DATA->DATE_ADMITTED,
                        $PH_DATA->DATE_DISCHARGED
                    );

                    foreach ($scDataItem as $list) {
                        $this->patientPaymentServices->PaymentChargeStore(
                            $this->PATIENT_PAYMENT_ID,
                            $list->SCI_ID,
                            0,
                            $list->AMOUNT,
                            0,
                            $this->PHIL_ACCOUNT_ID
                        );

                        $this->serviceChargeServices->updateServiceChargesItemPaid($list->SCI_ID);
                    }


                    $this->philHealthServices->setUpdateTwoId(
                        $this->PHILHEALTH_ID,
                        $this->PATIENT_PAYMENT_ID,
                        0
                    );

                    DB::commit();
                }
            }

            $data = [
                'PAYMENT_METHOD_ID' => $this->patientPaymentServices->PHILHEALTH_METHOD_ID,
                'PATIENT_PAYMENT_ID' => $this->PATIENT_PAYMENT_ID
            ];

            $this->dispatch('make-invoice-show', result: $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
    }
    public function getDetails()
    {
        $dataInvoice =   $this->invoiceServices->get($this->INVOICE_ID);
        if ($dataInvoice) {
            $this->INVOICE_AMOUNT = $dataInvoice->AMOUNT ?? 0;
            $this->INVOICE_BALANCE = $dataInvoice->BALANCE_DUE ?? 0;
        }

        $this->RECEIVED_AMOUNT  = $this->paymentServices->getTotalPay($this->INVOICE_ID, 0);
        $this->TAX_CREDIT_AMOUNT = $this->taxCreditServices->getTotalPay($this->INVOICE_ID, 0);
    }

    public function render()
    {
        return view('livewire.phil-health.transmittal');
    }
}
