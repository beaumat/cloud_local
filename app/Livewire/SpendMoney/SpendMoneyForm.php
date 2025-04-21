<?php

namespace App\Livewire\SpendMoney;

use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\SpendMoneyServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Spend Money')]
class SpendMoneyForm extends Component
{


    public $locationList = [];
    public $accountList = [];

    public bool $Modify = false;
    public int $ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $ACCOUNT_ID;
    public string $NOTES;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    private $spendMoneyServices;
    private $locationServices;
    private $accountServices;
    private $userServices;
    private $dateServices;
    private $documentStatusServices;
    public function boot(
        SpendMoneyServices $spendMoneyServices,
        AccountServices $accountServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices,
        DocumentStatusServices $documentStatusServices
    ) {
        $this->spendMoneyServices = $spendMoneyServices;
        $this->locationServices = $locationServices;
        $this->accountServices = $accountServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
        $this->documentStatusServices = $documentStatusServices;
    }

    private function LoadDropdown()
    {
        $this->locationList = $this->locationServices->getList();
        $this->accountList = $this->accountServices->getBankAccount();
    }
    public function mount($id = null)
    {

        if ($id != null) {


            $data = $this->spendMoneyServices->Get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->ID = $data->ID;
                $this->DATE = $data->DATE;
                $this->CODE = $data->CODE;
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->ACCOUNT_ID = $data->ACCOUNT_ID;
                $this->NOTES = $data->NOTES;
                $this->STATUS = $data->STATUS;
                $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
                $this->Modify = false;
                return;
            }
        }
        $this->LoadDropdown();
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->ACCOUNT_ID = 0;
        $this->NOTES = '';
        $this->CODE = '';
        $this->Modify = true;
        $this->ID = 0;
        $this->STATUS = 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);


        // Initialize any properties or perform actions when the component is mounted
    }
    public function save()
    {
        $this->validate(
            [
                'DATE' => 'required|date_format:Y-m-d',
                'LOCATION_ID' => 'required|numeric|exists:location,id',
                'ACCOUNT_ID' => 'required|numeric|exists:account,id',
                'NOTES' => 'nullable|string|max:255',
            ],
            [],
            [
                'DATE' => 'Date',
                'LOCATION_ID' => 'Location',
                'ACCOUNT_ID' => 'Account',
                'NOTES' => 'Notes',
            ]
        );




        try {

            if ($this->ID > 0) {
                $this->spendMoneyServices->Update(
                    $this->ID,
                    $this->DATE,
                    $this->CODE,
                    $this->LOCATION_ID,
                    $this->ACCOUNT_ID,
                    $this->NOTES
                );


                $this->Modify = false;
                session()->flash('message', 'Successfully updated');
            } else {
                $this->ID = $this->spendMoneyServices->Store(
                    $this->DATE,
                    $this->CODE,
                    $this->LOCATION_ID,
                    $this->ACCOUNT_ID,
                    $this->NOTES
                );

                return redirect()->route('spendmoney_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            }


        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);

        }
    }

    public function getModify()
    {
        $this->Modify = true;
      
    }
    public function updatedlocationid()
    {

        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.spend-money.spend-money-form');
    }
}
