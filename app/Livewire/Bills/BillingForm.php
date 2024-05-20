<?php

namespace App\Livewire\Bills;

use App\Services\BillingServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\PaymentTermServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

#[Title('Bills')]
class BillingForm extends Component
{

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
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public int $INPUT_TAX_ID;
    public float $INPUT_TAX_RATE;
    public int $INPUT_TAX_VAT_METHOD;
    public int $INPUT_TAX_ACCOUNT_ID;
    public float $INPUT_TAX_AMOUNT;
    public float $AMOUNT;
    public float $BALANCE_DUE;

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
    public string $tab = 'item';
    #[On('select-tab')]
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }
    public function boot(
        BillingServices $billingServices,
        LocationServices $locationServices,
        ContactServices $contactServices,
        PaymentTermServices $paymentTermServices,
        TaxServices $taxServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices

    ) {
        $this->billingServices = $billingServices;
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
        $this->paymentTermServices = $paymentTermServices;
        $this->taxServices = $taxServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices = $systemSettingServices;
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
        $this->CLASS_ID = $data->CLASS_ID ? $data->CLASS_ID : 0;
        $this->NOTES = $data->NOTES;
        $this->AMOUNT = $data->AMOUNT;
        $this->BALANCE_DUE = $data->BALANCE_DUE ?? 0;
        $this->STATUS = $data->STATUS;
        $this->INPUT_TAX_ID = $data->INPUT_TAX_ID > 0 ? $data->INPUT_TAX_ID : 0;
        $this->INPUT_TAX_RATE = $data->INPUT_TAX_RATE > 0 ? $data->INPUT_TAX_RATE : 0;
        $this->INPUT_TAX_AMOUNT = $data->INPUT_TAX_AMOUNT > 0 ? $data->INPUT_TAX_AMOUNT : 0;
        $this->INPUT_TAX_VAT_METHOD = $data->INPUT_TAX_VAT_METHOD > 0 ? $data->INPUT_TAX_VAT_METHOD : 0;
        $this->INPUT_TAX_ACCOUNT_ID = $data->INPUT_TAX_ACCOUNT_ID > 0 ? $data->INPUT_TAX_ACCOUNT_ID : 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
        $this->ACCOUNTS_PAYABLE_ID = $data->ACCOUNTS_PAYABLE_ID;

        if ($this->billingServices->isItemTab($data->ID)) {
            $this->tab = "item";
            return;
        }
        $this->tab = "account";
    }
    public function mount($id = null)
    {
        $this->LoadDropdown();
        if (is_numeric($id)) {
            $Bill = $this->billingServices->get($id);
            if ($Bill) {
                $this->getInfo($Bill);

                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('vendorsbills')->with('error', $errorMessage);
        }
        $this->tab = "item";
        $this->Modify = true;
        $this->ID = 0;
        $this->CODE = '';
        $currentDate = Carbon::now();
        $this->DATE = $currentDate->format('Y-m-d');
        $this->DUE_DATE = '';
        $this->DISCOUNT_DATE = '';
        $this->DISCOUNT_PCT = 0;
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->VENDOR_ID = 0;
        $this->CLASS_ID = 0;
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
        $this->ACCOUNTS_PAYABLE_ID = 21;
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

                return Redirect::route('vendorsbills_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            } else {

                $this->validate(
                    [
                        'VENDOR_ID' => 'required|not_in:0',
                        'CODE' => 'required|max:20|unique:purchase_order,code,' . $this->ID,
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


                $this->getTax();
                $this->billingServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DATE,
                    $this->VENDOR_ID,
                    $this->LOCATION_ID,
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
                $this->billingServices->getUpdateTaxItem($this->ID, $this->INPUT_TAX_ID);
                $getResult = $this->billingServices->ReComputed($this->ID);
                $this->getUpdateAmount($getResult);
                session()->flash('message', 'Successfully updated');
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
    // public function getSubmit()
    // {
    //     try {
    //         $this->billingServices->StatusUpdate($this->ID, 2);
    //         $BILL = $this->billingServices->get($this->ID);
    //         if ($BILL) {
    //             $this->getInfo($BILL);
    //             $this->Modify = false;
    //             return;
    //         }

    //     } catch (\Exception $e) {
    //         $errorMessage = 'Error occurred: ' . $e->getMessage();
    //         session()->flash('error', $errorMessage);
    //     }

    // }
    // public function getVoid()
    // {
    //     try {

    //         $this->billingServices->StatusUpdate($this->ID, 7);
    //         $BILL = $this->billingServices->get($this->ID);

    //         if ($BILL) {
    //             $this->getInfo($BILL);
    //             $this->Modify = false;
    //             return;
    //         }

    //     } catch (\Exception $e) {
    //         $errorMessage = 'Error occurred: ' . $e->getMessage();
    //         session()->flash('error', $errorMessage);
    //     }
    // }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }


    public function render()
    {
        return view('livewire.bills.billing-form');
    }
}
