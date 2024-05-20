<?php

namespace App\Livewire\BillCredit;

use App\Services\BillCreditServices;
use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Bill Credits')]
class BillCreditForm extends Component
{

    public int $ID;
    public int $VENDOR_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $ACCOUNTS_PAYABLE_ID;
    public string $NOTES;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public int $INPUT_TAX_ID;
    public float $INPUT_TAX_RATE;
    public int $INPUT_TAX_VAT_METHOD;
    public int $INPUT_TAX_ACCOUNT_ID;
    public float $INPUT_TAX_AMOUNT;
    public float $AMOUNT;
    public float $AMOUNT_APPLIED;
    public $vendorList = [];
    public $locationList = [];
    public $taxList = [];
    public bool $Modify;
    private $billCreditServices;
    private $locationServices;
    private $contactServices;
    private $taxServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $dateServices;
    public string $tab = 'item';
    #[On('select-tab')]
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }
    public function boot(
        BillCreditServices $billCreditServices,
        LocationServices $locationServices,
        ContactServices $contactServices,
        TaxServices $taxServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices
    ) {
        $this->billCreditServices = $billCreditServices;
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
        $this->taxServices = $taxServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices = $dateServices;
    }
    public function LoadDropdown()
    {
        $this->vendorList = $this->contactServices->getList(0);
        $this->locationList = $this->locationServices->getList();
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
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->VENDOR_ID = $data->VENDOR_ID;
        $this->CLASS_ID = $data->CLASS_ID ? $data->CLASS_ID : 0;
        $this->NOTES = $data->NOTES;
        $this->AMOUNT = $data->AMOUNT;
        $this->AMOUNT_APPLIED = $data->AMOUNT_APPLIED ?? 0;
        $this->STATUS = $data->STATUS;
        $this->INPUT_TAX_ID = $data->INPUT_TAX_ID > 0 ? $data->INPUT_TAX_ID : 0;
        $this->INPUT_TAX_RATE = $data->INPUT_TAX_RATE > 0 ? $data->INPUT_TAX_RATE : 0;
        $this->INPUT_TAX_AMOUNT = $data->INPUT_TAX_AMOUNT > 0 ? $data->INPUT_TAX_AMOUNT : 0;
        $this->INPUT_TAX_VAT_METHOD = $data->INPUT_TAX_VAT_METHOD > 0 ? $data->INPUT_TAX_VAT_METHOD : 0;
        $this->INPUT_TAX_ACCOUNT_ID = $data->INPUT_TAX_ACCOUNT_ID > 0 ? $data->INPUT_TAX_ACCOUNT_ID : 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
        $this->ACCOUNTS_PAYABLE_ID = $data->ACCOUNTS_PAYABLE_ID;

        if ($this->billCreditServices->isItemTab($data->ID)) {
            
            $this->tab = "item";
            return;
        }
        $this->tab = "account";
    }
    public function mount($id = null)
    {
        $this->LoadDropdown();
        if (is_numeric($id)) {
            $data = $this->billCreditServices->get($id);
            if ($data) {
                $this->getInfo($data);

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
        $this->DATE = $this->dateServices->NowDate();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->VENDOR_ID = 0;
        $this->CLASS_ID = 0;
        $this->NOTES = '';
        $this->AMOUNT = 0;
        $this->AMOUNT_APPLIED = 0;
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
                        'LOCATION_ID' => 'required'

                    ],
                    [],
                    [
                        'VENDOR_ID' => 'Vendor',
                        'INPUT_TAX_ID' => 'Tax',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location'

                    ]
                );

                $this->getTax();
                $this->ID = $this->billCreditServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->VENDOR_ID,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID,
                    $this->INPUT_TAX_ID,
                    $this->INPUT_TAX_RATE,
                    $this->INPUT_TAX_AMOUNT,
                    $this->INPUT_TAX_VAT_METHOD,
                    $this->INPUT_TAX_ACCOUNT_ID

                );

                return Redirect::route('vendorsbill_credit_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            } else {

                $this->validate(
                    [
                        'VENDOR_ID' => 'required|not_in:0',
                        'CODE' => 'required|max:20|unique:purchase_order,code,' . $this->ID,
                        'INPUT_TAX_ID' => 'required|not_in:0',
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required'

                    ],
                    [],
                    [
                        'VENDOR_ID' => 'Vendor',
                        'CODE' => 'Reference No.',
                        'INPUT_TAX_ID' => 'Tax',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location'

                    ]
                );


                $this->getTax();
                $this->billCreditServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DATE,
                    $this->VENDOR_ID,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID,
                    $this->INPUT_TAX_ID,
                    $this->INPUT_TAX_RATE,
                    $this->INPUT_TAX_AMOUNT,
                    $this->INPUT_TAX_VAT_METHOD,
                    $this->INPUT_TAX_ACCOUNT_ID
                );
                $this->billCreditServices->getUpdateTaxItem($this->ID, $this->INPUT_TAX_ID);
                $getResult = $this->billCreditServices->ReComputed($this->ID);
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
            $this->INPUT_TAX_AMOUNT = $list['TAX_AMOUNT'];

        }
        $this->AMOUNT_APPLIED = 0;
    }
    public function updateCancel()
    {
        $BILL = $this->billCreditServices->get($this->ID);
        if ($BILL) {
            $this->getInfo($BILL);
        }
        $this->Modify = false;
    }
    // public function getSubmit()
    // {
    //     try {
    //         $this->billCreditServices->StatusUpdate($this->ID, 2);
    //         $BILL = $this->billCreditServices->get($this->ID);
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

    //         $this->billCreditServices->StatusUpdate($this->ID, 7);
    //         $BILL = $this->billCreditServices->get($this->ID);

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
        return view('livewire.bill-credit.bill-credit-form');
    }
}
