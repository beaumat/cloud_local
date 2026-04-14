<?php
namespace App\Livewire\PhilHealth;

<<<<<<< HEAD
use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\PhilHealthSoaCustomServices;
use App\Services\UserServices;
=======
use App\Services\AccountJournalServices;
use App\Services\BillPaymentServices;
use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\InvoiceServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\PaymentServices;
use App\Services\PhilHealthServices;
use App\Services\PhilHealthSoaCustomServices;
use App\Services\ServiceChargeServices;
use App\Services\TaxCreditServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
>>>>>>> 3c71ebe73138bc062399be5f2d00a80bc03c62a2
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Philhealth')]

class PhilHealthForm extends Component
{
    public bool $Modify = false;
    public string $STATUS_DESCRIPTION;
    public int $STATUS;
    public int $ID       = 0;
    public string $tab   = "soa";
    public $patientList  = [];
    public $locationList = [];
    public int $LOCATION_ID;
    public int $CONTACT_ID;
    public string $CODE;
    public float $PAYMENT_AMOUNT = 0.00;
    public $DATE;
    public $DATE_ADMITTED;
    public $TIME_ADMITTED;
    public $DATE_DISCHARGED;
    public $TIME_DISCHARGED;
    public $TIME_HIDE;
    public bool $IS_HIDE = false;
    public string $FINAL_DIAGNOSIS;
    public string $OTHER_DIAGNOSIS;
    public string $FIRST_CASE_RATE;
    public string $SECOND_CASE_RATE;
    public int $STATUS_ID;
    public bool $isPaid = false;
    public string $AR_DATE;
    public string $AR_NO;

    private $philHealthServices;
    private $hemoServices;
    private $contactServices;
    private $locationServices;
    private $userServices;
    private $philHealthSoaCustomServices;
<<<<<<< HEAD
    public function SelectTab($tab)
=======
    private $taxCreditServices;
    private $patientPaymentServices;
    private $paymentServices;
    private $invoiceServices;
    private $accountJournalServices;
    private $serviceChargeServices;
    private $billPaymentServices;
  
    public function boot(
        PhilHealthServices $philHealthServices,
        HemoServices $hemoServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        PhilHealthSoaCustomServices $philHealthSoaCustomServices,
        TaxCreditServices $taxCreditServices,
        PatientPaymentServices $patientPaymentServices,
        PaymentServices $paymentServices,
        InvoiceServices $invoiceServices,
        AccountJournalServices $accountJournalServices,
        ServiceChargeServices $serviceChargeServices,
        BillPaymentServices $billPaymentServices

    ) {
        $this->philHealthServices          = $philHealthServices;
        $this->hemoServices                = $hemoServices;
        $this->contactServices             = $contactServices;
        $this->locationServices            = $locationServices;
        $this->userServices                = $userServices;
        $this->philHealthSoaCustomServices = $philHealthSoaCustomServices;
        $this->taxCreditServices           = $taxCreditServices;
        $this->paymentServices             = $paymentServices;
        $this->patientPaymentServices      = $patientPaymentServices;
        $this->invoiceServices             = $invoiceServices;
        $this->accountJournalServices      = $accountJournalServices;
        $this->serviceChargeServices       = $serviceChargeServices;
        $this->billPaymentServices         = $billPaymentServices;
    }

      public function SelectTab($tab)
>>>>>>> 3c71ebe73138bc062399be5f2d00a80bc03c62a2
    {
        $this->tab = $tab;
    }
    #[On('ar-form-data')]
    public function arForm($ar)
    {
        $this->AR_DATE = $ar['AR_DATE'];
        $this->AR_NO   = $ar['AR_NO'];
    }
<<<<<<< HEAD
    public function boot(
        PhilHealthServices $philHealthServices,
        HemoServices $hemoServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        PhilHealthSoaCustomServices $philHealthSoaCustomServices
    ) {
        $this->philHealthServices          = $philHealthServices;
        $this->hemoServices                = $hemoServices;
        $this->contactServices             = $contactServices;
        $this->locationServices            = $locationServices;
        $this->userServices                = $userServices;
        $this->philHealthSoaCustomServices = $philHealthSoaCustomServices;
    }
=======
>>>>>>> 3c71ebe73138bc062399be5f2d00a80bc03c62a2
    public function UpdatedContactId()
    {

        $data = $this->hemoServices->getDateTime($this->CONTACT_ID, $this->LOCATION_ID);

        if ($data) {

            $this->DATE_ADMITTED   = $data['FIRST_DATE'];
            $this->TIME_ADMITTED   = $data['FIRST_TIME'];
            $this->DATE_DISCHARGED = $data['LAST_DATE'];
            $this->TIME_DISCHARGED = $data['LAST_TIME'];

            return;
        }
        $this->DATE_ADMITTED   = '';
        $this->TIME_ADMITTED   = '';
        $this->DATE_DISCHARGED = '';
        $this->TIME_DISCHARGED = '';
    }
    private function LoadDropDown()
    {
        $this->locationList = $this->locationServices->getList();
        $this->patientList  = $this->contactServices->getList(3);
    }
    private function GotHide()
    {
        $data = $this->philHealthSoaCustomServices->GetFirst($this->LOCATION_ID);
        if ($data) {
            if ($data->HIDE_FEE > 0) {
                $this->IS_HIDE = true;
                return;
            }
        }
        $this->IS_HIDE = false;
    }
    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $data = $this->philHealthServices->get($id);
            if ($data) {
                $this->LoadDropDown();
                $this->ID = $data->ID;

                $this->isPaid = $this->philHealthServices->isPaid($this->ID);

                $this->CODE        = $data->CODE;
                $this->DATE        = $data->DATE;
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->GotHide();
                $this->CONTACT_ID       = $data->CONTACT_ID;
                $this->DATE_ADMITTED    = $data->DATE_ADMITTED;
                $this->TIME_ADMITTED    = $data->TIME_ADMITTED;
                $this->DATE_DISCHARGED  = $data->DATE_DISCHARGED;
                $this->TIME_DISCHARGED  = $data->TIME_DISCHARGED;
                $this->TIME_HIDE        = $data->TIME_HIDE ?? '';
                $this->FINAL_DIAGNOSIS  = $data->FINAL_DIAGNOSIS;
                $this->OTHER_DIAGNOSIS  = $data->OTHER_DIAGNOSIS;
                $this->FIRST_CASE_RATE  = $data->FIRST_CASE_RATE;
                $this->SECOND_CASE_RATE = $data->SECOND_CASE_RATE;
                $this->STATUS_ID        = $data->STATUS_ID;
                $this->AR_DATE          = $data->AR_DATE ?? '';
                $this->AR_NO            = $data->AR_NO ?? '';
                $this->PAYMENT_AMOUNT   = $data->PAYMENT_AMOUNT ?? 0.00;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('patientsphic')->with('error', $errorMessage);
        }
        $this->LoadDropDown();
        $this->ID          = 0;
        $this->CODE        = '';
        $this->DATE        = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->GotHide();
        $this->CONTACT_ID       = 0;
        $this->DATE_ADMITTED    = null;
        $this->TIME_ADMITTED    = null;
        $this->DATE_DISCHARGED  = null;
        $this->TIME_DISCHARGED  = null;
        $this->TIME_HIDE        = null;
        $this->FINAL_DIAGNOSIS  = '';
        $this->OTHER_DIAGNOSIS  = '';
        $this->FIRST_CASE_RATE  = '';
        $this->SECOND_CASE_RATE = '';
        $this->AR_DATE          = '';
        $this->AR_NO            = '';
        $this->STATUS_ID        = 0;
        $this->Modify           = true;
        $this->PAYMENT_AMOUNT   = 0.00;
    }
    public function print()
    {
<<<<<<< HEAD
        if( $this->ID == 0) {

        return;
        }


        $ds = $this->philHealthServices->get($this->ID);
        if ($ds) {
            // if (! empty($ds->AR_NO)) {
            //     session()->flash('error', 'cannot be print. this document already set AR info');
            //     return;
            // }
            // if (floatval($ds->PAYMENT_AMOUNT ?? 0) > 0) {
            //     session()->flash('error', 'cannot be print. this document having a payment collection');
            //     return;
            // }

            // restriction end
=======
        if ($this->ID == 0) {

            return;
        }

        $ds = $this->philHealthServices->get($this->ID);
        if ($ds) {
>>>>>>> 3c71ebe73138bc062399be5f2d00a80bc03c62a2
            $data = [
                'PHILHEALTH_ID' => $this->ID,
            ];

            $this->dispatch('philhealth-print-data', result: $data);
        }
    }
    public function updateCancel()
    {
        return Redirect::route('patientsphic_edit', ['id' => $this->ID]);
    }
    public function save()
    {

        $this->validate(
            [
                'CONTACT_ID'      => 'required|not_in:0|exists:contact,id',
                'DATE'            => 'required|date',
                'LOCATION_ID'     => 'required|exists:location,id',
                'DATE_ADMITTED'   => 'required|date',
                'TIME_ADMITTED'   => 'required',
                'DATE_DISCHARGED' => 'required|date',
                'TIME_DISCHARGED' => 'required',
            ],
            [],
            [
                'CONTACT_ID'      => 'Patient',
                'DATE'            => 'Date',
                'LOCATION_ID'     => 'Location',
                'DATE_ADMITTED'   => 'Date Admitted',
                'TIME_ADMITTED'   => 'Time Admiited',
                'DATE_DISCHARGED' => 'Date Discharged',
                'TIME_DISCHARGED' => 'Time Discharged',
            ]
        );

        if ($this->ID == 0) {

            $this->ID = $this->philHealthServices->preSave(
                $this->CODE,
                $this->DATE,
                $this->LOCATION_ID,
                $this->CONTACT_ID,
                $this->DATE_ADMITTED,
                $this->TIME_ADMITTED,
                $this->DATE_DISCHARGED,
                $this->TIME_DISCHARGED,
                $this->FINAL_DIAGNOSIS,
                $this->OTHER_DIAGNOSIS,
                $this->FIRST_CASE_RATE,
                $this->SECOND_CASE_RATE
            );
            $this->philHealthServices->DefaultEntry($this->ID);
            $this->Modify = false;
            return Redirect::route('patientsphic_edit', ['id' => $this->ID])->with('message', 'Successfully created');
        } else {

            $this->philHealthServices->preUpdate(
                $this->ID,
                $this->CODE,
                $this->DATE,
                $this->LOCATION_ID,
                $this->CONTACT_ID,
                $this->DATE_ADMITTED,
                $this->TIME_ADMITTED,
                $this->DATE_DISCHARGED,
                $this->TIME_DISCHARGED,
                $this->FINAL_DIAGNOSIS,
                $this->OTHER_DIAGNOSIS,
                $this->FIRST_CASE_RATE,
                $this->SECOND_CASE_RATE
            );

            $this->philHealthServices->DefaultEntry($this->ID);
            $this->Modify = false;
            return Redirect::route('patientsphic_edit', ['id' => $this->ID])->with('message', 'Successfully updated');
        }
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function getARForm()
    {
        $data = [
            'PHILHEALTH_ID' => $this->ID,
        ];

        $this->dispatch('ar-form-show', result: $data);
    }
<<<<<<< HEAD
=======

>>>>>>> 3c71ebe73138bc062399be5f2d00a80bc03c62a2
    public function getChangeDoctor()
    {
        $this->dispatch('call-open-update-pf');

    }
    public function getComputation()
    {
        $this->philHealthServices->DefaultEntry($this->ID);
        return Redirect::route('patientsphic_edit', ['id' => $this->ID])->with('message', 'Successfully updated');

    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function finder()
    {
        $this->dispatch('open-finder');
    }
<<<<<<< HEAD
=======
    public function deletePaymentProccess()
    {
        if (! UserServices::GetUserRightAccess('customer.received-payment.delete')) {
            session()->flash('error', 'You don`t have permission to delete');
            return;
        }

        $data = $this->philHealthServices->get($this->ID);
        if ($data) {
            $this->DeletePaid($data->PAYMENT_ID);
        }
    }
    private function DeletePaid(int $PAYMENT_ID)
    {

        DB::beginTransaction();
        try {

            if ($PAYMENT_ID > 0) {
                $gotTaxCreditDelete = false;
                $tax_ID             = $this->taxCreditServices->GetTaxID($PAYMENT_ID);
                if ($tax_ID > 0) {
                    if ($this->TaxCreditdeleteEntry($tax_ID)) {
                        $gotTaxCreditDelete = true;
                        // NEW STYLE
                    }
                    if (! $gotTaxCreditDelete) {
                        session()->flash('error', 'No tax credit entry found for this payment');
                        DB::rollBack();
                        return;
                    }
                }

                if ($this->PaymentdeleteEntry($PAYMENT_ID)) {

                }

                $PH_DATA = $this->philHealthServices->getDataByPayment($PAYMENT_ID);
                if ($PH_DATA) {

                    $this->getTreamentSummary($PH_DATA);

                 

                   if($this->philHealthServices->deletePayableForDoctor($PH_DATA->ID)) {
                       
                    session()->flash('error', 'This payment cannot be deleted. This is Bill payment for doctor fee has already posted to accounts payable. Please delete the bill payment entry first to proceed deleting this payment');
                   
                   DB::rollBack();

                    return;
                   }
                    $this->philHealthServices->UpdatePayment($PH_DATA->ID, 0, $PAYMENT_ID);




                    DB::commit();
            
                    return Redirect::route('patientsphic_edit', ['id' => $this->ID])->with('message', 'Payment canceled');
                }

                // delete service charge from patient_payment_

                DB::rollBack();
                session()->flash('error', 'No philhealth entry found for this payment');

            }

        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollBack();
        }

    }

    private function getTreamentSummary($phData)
    {
        $PATIENT_PAYMENT_ID = $this->patientPaymentServices->PH_exists($phData->ID);

        $summaryList = $this->hemoServices->GetSummary($phData->CONTACT_ID, $phData->LOCATION_ID, $phData->DATE_ADMITTED, $phData->DATE_DISCHARGED);

        foreach ($summaryList as $sumList) {
            $PP_ITEM_ID = $this->patientPaymentServices->PaymentChargesExist($PATIENT_PAYMENT_ID, $sumList->SCI_ID);

            if ($PP_ITEM_ID > 0) {
                $this->patientPaymentServices->PaymentChargesDelete($PP_ITEM_ID, $PATIENT_PAYMENT_ID, $sumList->SCI_ID);
            }

            $this->serviceChargeServices->updateServiceChargesItemPaid($sumList->SCI_ID);
            $this->serviceChargeServices->updateServiceChargesBalance($sumList->SERVICE_CHARGES_ID);
        }

        $this->patientPaymentServices->PH_Delete($PATIENT_PAYMENT_ID, $phData->ID);
    }
    public function PaymentdeleteEntry(int $id): bool
    {
        $data = $this->paymentServices->get($id);
        if ($data) {

            if ($data->DEPOSITED == 1) {

                if ($data->STATUS == 15) {
                    $this->PaymentdeleteJournal($data, $id);
                    $paymentList = $this->paymentServices->PaymentInvoiceList($data->ID);
                    $this->paymentServices->delete($data->ID);

                    foreach ($paymentList as $list) {
                        $this->invoiceServices->updateInvoiceBalance($list->INVOICE_ID);
                    }
                    return true;
                }
            }
        }

        return false;
    }
    public function PaymentdeleteJournal(object $data, int $id)
    {

        $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->paymentServices->object_type_payment, $id);
        $payData    = $this->paymentServices->PaymentInvoiceList($id);
        foreach ($payData as $list) {

            $this->accountJournalServices->DeleteJournal(
                $list->ACCOUNTS_RECEIVABLE_ID,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $data->CUSTOMER_ID,
                $list->ID,
                $this->paymentServices->object_type_payment_invoices,
                $data->DATE,
                1
            );
        }

        $this->accountJournalServices->DeleteJournal(
            $data->UNDEPOSITED_FUNDS_ACCOUNT_ID,
            $data->LOCATION_ID,
            $JOURNAL_NO,
            $data->CUSTOMER_ID,
            $data->ID,
            $this->paymentServices->object_type_payment,
            $data->DATE,
            0
        );

        if ($data->ACCOUNTS_RECEIVABLE_ID > 0) {
            // optional if remaining
            $this->accountJournalServices->DeleteJournal(
                $data->ACCOUNTS_RECEIVABLE_ID ?? 0,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $data->CUSTOMER_ID,
                $data->ID,
                $this->paymentServices->object_type_payment,
                $data->DATE,
                1
            );
        }
    }
    private function TaxCreditdeleteJournal($data, int $id)
    {

        $JOURNAL_NO      = (int) $this->accountJournalServices->getRecord($this->taxCreditServices->object_type_tax_credit, $id);
        $invoiceListData = $this->taxCreditServices->GetInvoiceList($id);
        $this->accountJournalServices->DeleteJournal(
            $data->EWT_ACCOUNT_ID,
            $data->LOCATION_ID,
            $JOURNAL_NO,
            $data->CUSTOMER_ID,
            $data->ID,
            $this->taxCreditServices->object_type_tax_credit,
            $data->DATE,
            0
        );

        foreach ($invoiceListData as $list) {
            $this->accountJournalServices->DeleteJournal(
                $list->ACCOUNTS_RECEIVABLE_ID,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $list->INVOICE_ID,
                $list->ID,
                $this->taxCreditServices->object_type_tax_credit_invoices,
                $data->DATE,
                1
            );
        }

        // optional if remaining
        $this->accountJournalServices->DeleteJournal(
            $data->ACCOUNTS_RECEIVABLE_ID ?? 0,
            $data->LOCATION_ID,
            $JOURNAL_NO,
            $data->CUSTOMER_ID,
            $data->ID,
            $this->taxCreditServices->object_type_tax_credit,
            $data->DATE,
            1
        );
    }
    public function TaxCreditdeleteEntry(int $id)
    {
        $data = $this->taxCreditServices->Get($id);
        if ($data) {
            if ($data->STATUS == 15) {
                $this->TaxCreditdeleteJournal($data, $id);
            }
            $invoiceList = $this->taxCreditServices->GetInvoiceList($id); // get first invoice Tax Credit
            $this->taxCreditServices->Delete($id);                        // Delete Main and Invoice tax credit
            foreach ($invoiceList as $list) {
                $this->invoiceServices->updateInvoiceBalance($list->INVOICE_ID);
            }
            return true;
        }

        return false;
    }

>>>>>>> 3c71ebe73138bc062399be5f2d00a80bc03c62a2
    public function render()
    {
        return view('livewire.phil-health.phil-health-form');
    }
}
