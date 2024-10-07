<?php

namespace App\Livewire\TaxCredit;

use App\Services\ContactServices;
use App\Services\LocationServices;
use App\Services\TaxCreditServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Tax Credit')]
class TaxCreditForm extends Component
{


    public bool $Modify;
    public int $openStatus = 0;

    public int $ID;
    public string $CODE;
    public string $DATE;
    public int $CUSTOMER_ID;
    public int $EWT_ID;
    public float $EWT_RATE;
    public int $EWT_ACCOUNT_ID;
    public int $LOCATION_ID;
    public float $AMOUNT;
    public string $NOTES;
    public int $STATUS;
    public int $STATUS_DESCRIPTION;
    public int $ACCOUNTS_RECEIVABLE_ID;


    public $contactList = [];
    public $locationList = [];
    public $taxList = [];


    private $taxCreditServices;
    private $contactServices;
    private $locationServices;
    private $userServices;
    private $taxServices;
    public function boot(
        TaxCreditServices $taxCreditServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        TaxServices $taxServices
    ) {
        $this->taxCreditServices = $taxCreditServices;
        $this->contactServices  = $contactServices;
        $this->userServices = $userServices;
        $this->locationServices = $locationServices;
        $this->taxServices = $taxServices;
    }
    private function LoadDropdown()
    {
        $this->contactList = $this->contactServices->getCustoPatientList();
        $this->locationList = $this->locationServices->getList();
        $this->taxList = $this->taxServices->getWTax();
    }
    private function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->CODE = $data->CODE ?? '';
        $this->CUSTOMER_ID = $data->CUSTOMER_ID ?? 0;
        $this->LOCATION_ID  = $data->LOCATION_ID ?? 0;
        $this->DATE = $data->DATE;
        $this->EWT_ACCOUNT_ID = $data->EWT_ACCOUNT_ID ?? 0;
        $this->ACCOUNTS_RECEIVABLE_ID = $data->ACCOUNTS_RECEIVABLE_ID ?? 0;
        $this->AMOUNT = $data->AMOUNT ?? 0;
        $this->EWT_RATE = $data->EWT_RATE ?? 0;
        $this->EWT_ID = $data->EWT_ID ?? 0;
        $this->NOTES = $data->NOTES ?? '';
    }

    public function updatedEwtId()
    {
        $data = $this->taxServices->get($this->EWT_ID);
        if ($data) {
            $this->EWT_RATE  = $data->RATE ?? 0;
            $this->EWT_ACCOUNT_ID = $data->TAX_ACCOUNT_ID ?? 0;
            return;
        }
        $this->EWT_RATE  = 0;
        $this->EWT_ACCOUNT_ID = 0;
    }
    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $data = $this->taxCreditServices->Get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('companystock_transfer')->with('error', $errorMessage);
        }


        $this->LoadDropdown();
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->CODE = '';
        $this->CUSTOMER_ID = 0;
        $this->EWT_ACCOUNT_ID = 0;
        $this->ACCOUNTS_RECEIVABLE_ID = 12;
        $this->AMOUNT =  0;
        $this->EWT_RATE =  0;
        $this->EWT_ID = 0;
        $this->ID = 0;
        $this->NOTES = '';
        $this->Modify = true;
    }


    public function save()
    {

        if ($this->ID === 0) {
            $this->validate(
                [
                    'EWT_ID'        => 'required|integer|exists:tax,id',
                    'CUSTOMER_ID'   => 'required|integer|exists:contact,id',
                    'LOCATION_ID'   => 'required|integer|exists:location,id',
                    'DATE'          => 'required|string'
                ],
                [],
                [
                    'EWT_ID'        => 'Withholding Tax Type',
                    'CUSTOMER_ID'   => 'Customer',
                    'LOCATION_ID'   => 'Location'
                ]
            );
        } else {
            $this->validate(
                [
                    'EWT_ID'        => 'required|integer|exists:tax,id',
                    'CUSTOMER_ID'   => 'required|integer|exists:contact,id',
                    'LOCATION_ID'   => 'required|integer|exists:location,id',
                    'DATE'          => 'required|string'
                ],
                [],
                [
                    'EWT_ID'        => 'Withholding Tax Type',
                    'CUSTOMER_ID'   => 'Customer',
                    'LOCATION_ID'   => 'Location'
                ]
            );
        }
        DB::beginTransaction();
        try {

            if ($this->ID == 0) {
                $this->ID =  $this->taxCreditServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->EWT_ID,
                    $this->EWT_RATE,
                    $this->EWT_ACCOUNT_ID,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_RECEIVABLE_ID
                );

                DB::commit();
                return Redirect::route('customerstax_credit_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            }
            DB::beginTransaction();
            $this->taxCreditServices->Update(
                $this->ID,
                $this->CODE,
                $this->EWT_ID,
                $this->EWT_RATE,
                $this->EWT_ACCOUNT_ID,
                $this->NOTES,
                $this->ACCOUNTS_RECEIVABLE_ID
            );
            DB::commit();
            session()->flash('message', 'Successfully updated');
        } catch (\Throwable $e) {
            DB::rollback();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function updateCancel()
    {
        $data = $this->taxCreditServices->Get($this->ID);
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
        return view('livewire.tax-credit.tax-credit-form');
    }
}
