<?php

namespace App\Livewire\Invoice;

use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\DocumentStatusServices;
use App\Services\InvoiceServices;
use App\Services\LocationServices;
use App\Services\PaymentTermServices;
use App\Services\ShipViaServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Invoice')]
class InvoiceForm extends Component
{

    public int $ID;
    public int $CUSTOMER_ID;
    public int $SALES_REP_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $CLASS_ID;
    public int $SHIP_VIA_ID;
    public $DUE_DATE;
    public $SHIP_DATE;
    public int $PAYMENT_TERMS_ID;
    public string $PO_NUMBER;
    public $DISCOUNT_DATE;
    public float $DISCOUNT_PCT;
    public string $NOTES;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public int $OUTPUT_TAX_ID;
    public float $OUTPUT_TAX_RATE;
    public int $OUTPUT_TAX_VAT_METHOD;
    public int $OUTPUT_TAX_ACCOUNT_ID;
    public float $OUTPUT_TAX_AMOUNT;
    public float $AMOUNT;
    public float $BALANCE_DUE;
    public float $TAXABLE_AMOUNT;
    public float $NONTAXABLE_AMOUNT;
    public int $ACCOUNTS_RECEIVABLE_ID;
    public $patientList = [];
    public $locationList = [];
    public $shipViaList = [];
    public $paymentTermList = [];
    public $taxList = [];
    public bool $Modify;
    private $locationServices;
    private $contactServices;
    private $shipViaServices;
    private $paymentTermServices;
    private $taxServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $accountServices;
    private $scheduleServices;
    private $invoiceServices;
    private $dateServices;

    public string $tab = "item";
    public function SelectTab(string $select)
    {
        $this->tab = $select;
    }

    public function boot(
        InvoiceServices $invoiceServices,
        LocationServices $locationServices,
        ContactServices $contactServices,
        ShipViaServices $shipViaServices,
        PaymentTermServices $paymentTermServices,
        TaxServices $taxServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        AccountServices $accountServices,
        DateServices $dateServices
    ) {
        $this->invoiceServices = $invoiceServices;
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
        $this->shipViaServices = $shipViaServices;
        $this->paymentTermServices = $paymentTermServices;
        $this->taxServices = $taxServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->accountServices = $accountServices;
        $this->dateServices = $dateServices;
    }
    public function LoadDropdown()
    {

        $this->patientList = $this->contactServices->getList(1);
        $this->locationList = $this->locationServices->getList();
        $this->shipViaList = $this->shipViaServices->getList();
        $this->paymentTermList = $this->paymentTermServices->getList();
        $this->taxList = $this->taxServices->getList();
    }
    public function getTax()
    {
        $tax = $this->taxServices->get($this->OUTPUT_TAX_ID);
        if ($tax) {
            $this->OUTPUT_TAX_RATE = (float) $tax->OUTPUT_TAX_RATE;
            $this->OUTPUT_TAX_VAT_METHOD = (int) $tax->VAT_METHOD;
            $this->OUTPUT_TAX_ACCOUNT_ID = (int) $tax->TAX_ACCOUNT_ID;
        }
    }

    private function getInfo($Data)
    {
        $this->ID = $Data->ID;
        $this->CODE = $Data->CODE;
        $this->DATE = $Data->DATE;
        $this->DUE_DATE = $Data->DUE_DATE ?? null;
        $this->LOCATION_ID = $Data->LOCATION_ID;
        $this->CUSTOMER_ID = $Data->CUSTOMER_ID;
        $this->SALES_REP_ID = $Data->SALES_REP_ID ?? 0;
        $this->SHIP_VIA_ID = $Data->SHIP_VIA_ID ?? 0;
        $this->PAYMENT_TERMS_ID = $Data->PAYMENT_TERMS_ID ? $Data->PAYMENT_TERMS_ID : 0;
        $this->CLASS_ID = $Data->CLASS_ID ? $Data->CLASS_ID : 0;
        $this->PO_NUMBER = $Data->PO_NUMBER ?? '';
        $this->DISCOUNT_DATE = $Data->DISCOUNT_DATE ?? null;
        $this->DISCOUNT_PCT = $Data->DISCOUNT_PCT ?? 0;
        $this->NOTES = $Data->NOTES ?? '';
        $this->AMOUNT = $Data->AMOUNT;
        $this->BALANCE_DUE = $Data->BALANCE_DUE;
        $this->ACCOUNTS_RECEIVABLE_ID = $Data->ACCOUNTS_RECEIVABLE_ID;
        $this->STATUS = $Data->STATUS;
        $this->OUTPUT_TAX_ID = $Data->OUTPUT_TAX_ID ? $Data->OUTPUT_TAX_ID : 0;
        $this->OUTPUT_TAX_RATE = $Data->OUTPUT_TAX_RATE ? $Data->OUTPUT_TAX_RATE : 0;
        $this->OUTPUT_TAX_AMOUNT = $Data->OUTPUT_TAX_AMOUNT ? $Data->OUTPUT_TAX_AMOUNT : 0;
        $this->OUTPUT_TAX_VAT_METHOD = $Data->OUTPUT_TAX_VAT_METHOD ? $Data->OUTPUT_TAX_VAT_METHOD : 0;
        $this->OUTPUT_TAX_ACCOUNT_ID = $Data->OUTPUT_TAX_ACCOUNT_ID ? $Data->OUTPUT_TAX_ACCOUNT_ID : 0;
        $this->TAXABLE_AMOUNT = $Data->TAXABLE_AMOUNT ? $Data->TAXABLE_AMOUNT : 0;
        $this->NONTAXABLE_AMOUNT = $Data->NONTAXABLE_AMOUNT ? $Data->NONTAXABLE_AMOUNT : 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }

    public function updatedPAYMENTTERMSID()
    {
        $this->DUE_DATE = $this->paymentTermServices->getDueDate($this->PAYMENT_TERMS_ID);
    }
    public function mount($id = null)
    {
        $currentDate = $this->dateServices->Now();
        $this->DATE = $currentDate->format('Y-m-d');
        $this->LOCATION_ID = $this->userServices->getLocationDefault();

        if (is_numeric($id)) {
            $this->LoadDropdown();
            $data = $this->invoiceServices->get($id);
            if ($data) {
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('customersinvoice')->with('error', $errorMessage);
        }

        $this->LoadDropdown();
        $this->Modify = true;
        $this->ID = 0;
        $this->CODE = '';
        $this->CUSTOMER_ID = 0;
        $this->SALES_REP_ID = 0;
        $this->SHIP_VIA_ID = $this->shipViaServices->getFirst();
        $this->CLASS_ID = 0;
        $this->PAYMENT_TERMS_ID = (int) $this->systemSettingServices->GetValue('DefaultPaymentTermsId');
        $this->DUE_DATE = $this->paymentTermServices->getDueDate($this->PAYMENT_TERMS_ID);
        $this->NOTES = '';
        $this->AMOUNT = 0;
        $this->BALANCE_DUE = 0;
        $this->ACCOUNTS_RECEIVABLE_ID = (int) $this->accountServices->getByName('Accounts Receivable');
        $this->STATUS = 0;
        $this->OUTPUT_TAX_ID = (int) $this->systemSettingServices->GetValue('OutputTaxId');
        $this->OUTPUT_TAX_RATE = 0;
        $this->OUTPUT_TAX_AMOUNT = 0;
        $this->OUTPUT_TAX_VAT_METHOD = 0;
        $this->OUTPUT_TAX_ACCOUNT_ID = 0;
        $this->TAXABLE_AMOUNT = 0;
        $this->NONTAXABLE_AMOUNT = 0;
        $this->STATUS_DESCRIPTION = "";

        $this->PO_NUMBER = '';
        $this->DISCOUNT_DATE = null;
        $this->DISCOUNT_PCT = 0;
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
                        'CUSTOMER_ID' => 'required|not_in:0',
                        'OUTPUT_TAX_ID' => 'required|not_in:0',
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                        'PAYMENT_TERMS_ID' => 'required'
                    ],
                    [],
                    [
                        'CUSTOMER_ID' => 'Patient',
                        'OUTPUT_TAX_ID' => 'Tax',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'PAYMENT_TERMS_ID' => 'Payment Terms'
                    ]
                );

                $this->getTax();
                $this->ID = (int) $this->invoiceServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->CLASS_ID,
                    $this->SALES_REP_ID,
                    $this->PO_NUMBER,
                    0,
                    $this->SHIP_VIA_ID,
                    $this->SHIP_DATE,
                    $this->PAYMENT_TERMS_ID,
                    $this->DUE_DATE,
                    $this->DISCOUNT_DATE,
                    $this->DISCOUNT_PCT,
                    $this->NOTES,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    $this->STATUS,
                    $this->OUTPUT_TAX_ID,
                    $this->OUTPUT_TAX_RATE,
                    $this->OUTPUT_TAX_VAT_METHOD,
                    $this->OUTPUT_TAX_ACCOUNT_ID
                );
                return Redirect::route('customersinvoice_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $this->validate(
                    [
                        'CUSTOMER_ID' => 'required|not_in:0',
                        'CODE' => 'required|max:20|unique:invoice,code,' . $this->ID,
                        'OUTPUT_TAX_ID' => 'required|not_in:0',
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                        'PAYMENT_TERMS_ID' => 'required'
                    ],
                    [],
                    [
                        'CUSTOMER_ID' => 'Petient',
                        'CODE' => 'Reference No.',
                        'OUTPUT_TAX_ID' => 'Tax',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'PAYMENT_TERMS_ID' => 'Payment Terms'
                    ]
                );

                $this->getTax();
                $this->invoiceServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->CLASS_ID,
                    $this->SALES_REP_ID,
                    $this->PO_NUMBER,
                    0,
                    $this->SHIP_VIA_ID,
                    $this->SHIP_DATE,
                    $this->PAYMENT_TERMS_ID,
                    $this->DUE_DATE,
                    $this->DISCOUNT_DATE,
                    $this->DISCOUNT_PCT,
                    $this->NOTES,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    $this->STATUS,
                    $this->OUTPUT_TAX_ID,
                    $this->OUTPUT_TAX_RATE,
                    $this->OUTPUT_TAX_VAT_METHOD,
                    $this->OUTPUT_TAX_ACCOUNT_ID
                );

                $this->invoiceServices->getUpdateTaxItem($this->ID, $this->OUTPUT_TAX_ID);

                $getResult = $this->invoiceServices->ReComputed($this->ID);

                $this->getUpdateAmount($getResult);

                session()->flash('message', 'Successfully updated');
            }

            $data = $this->invoiceServices->get($this->ID);

            if ($data) {
                $this->getInfo($data);
            }

            $this->Modify = false;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('update-amount')]
    public function getUpdateAmount($result)
    {
        foreach ($result as $list) {
            $this->AMOUNT = $list['AMOUNT'];
            $this->BALANCE_DUE = $list['BALANCE_DUE'];
            $this->OUTPUT_TAX_AMOUNT = $list['TAX_AMOUNT'];
            $this->TAXABLE_AMOUNT = $list['TAXABLE_AMOUNT'];
            $this->NONTAXABLE_AMOUNT = $list['NONTAXABLE_AMOUNT'];
        }
    }
    #[On('update-status')]
    public function updateStatus()
    {
        $data = $this->invoiceServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
    }
    public function updateCancel()
    {
        $data = $this->invoiceServices->get($this->ID);
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
    public function render()
    {
        return view('livewire.invoice.invoice-form');
    }
}
