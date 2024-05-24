<?php

namespace App\Livewire\StockTransfer;

use App\Services\DateServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\StockTransferServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Component;



class StockTransferForm extends Component
{

    public int $ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $TRANSFER_TO_ID;
    public int $PREPARED_BY_ID;
    public float $AMOUNT;
    public float $RETAIL_VALUE;
    public string $NOTES;
    public int $ACCOUNT_ID;
    public $locationList = [];
    public $transferList = [];
    public $contactList = [];
    public bool $Modify;
    private $stockTransferServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    private $documentStatusServices;

    public function boot(
        StockTransferServices $stockTransferServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices,
        DocumentStatusServices $documentStatusServices
    ) {
        $this->stockTransferServices = $stockTransferServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
        $this->documentStatusServices = $documentStatusServices;
    }
    public function LoadDropdown()
    {
        $this->locationList = $this->locationServices->getList();
    }
    public function posted()
    {
        // $total_result = $this->stockTransferServices->GetTotal($this->ID);

        // $total_debit = (float) $total_result['TOTAL_DEBIT'];

        // $total_credit = (float) $total_result['TOTAL_CREDIT'];

        // if ($total_debit == 0) {
        //     Session()->flash('error', 'No debit entry');
        //     return;
        // }
        // if ($total_credit == 0) {
        //     Session()->flash('error', 'No credit entry');
        //     return;
        // }

        // if ($total_debit == $total_credit) {
        //     $this->stockTransferServices->StatusUpdate($this->ID, 15);
        //     Session()->flash('message', 'Successfully posted');
        //     return;
        // }

        Session()->flash('error', 'Invalid disbalanced.');
    }
    private function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->CODE = $data->CODE;
        $this->DATE = $data->DATE;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->NOTES = $data->NOTES ?? '';
        $this->TRANSFER_TO_ID = $data->TRANSFER_TO_ID ?? 0;
        $this->AMOUNT = $data->AMOUNT ?? 0;
        $this->RETAIL_VALUE = $data->RETAIL_VALUE ?? 0;
        $this->PREPARED_BY_ID = $data->PREPARED_BY_ID ?? 0;
        $this->ACCOUNT_ID = $data->ACCOUNT_ID ?? 0;
        $this->STATUS = $data->STATUS ?? 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    public function mount($id = null)
    {
        $this->LoadDropdown();

        if (is_numeric($id)) {
            $data = $this->stockTransferServices->Get($id);
            if ($data) {
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('companygeneral_journal')->with('error', $errorMessage);
        }

        $this->Modify = true;
        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = $this->dateServices->NowDate();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->TRANSFER_TO_ID = 0;
        $this->AMOUNT = 0;
        $this->RETAIL_VALUE = 0;
        $this->PREPARED_BY_ID = 0;
        $this->NOTES = '';

        $this->ACCOUNT_ID = 31;
        $this->STATUS = 0;
        $this->STATUS_DESCRIPTION = '';
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

                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                        'TRANSFER_TO_ID' => 'required|not_in:0'

                    ],
                    [],
                    [

                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'TRANSFER_TO_ID' => 'Transfer To'

                    ]
                );


                $this->ID = $this->stockTransferServices->Store(
                    $this->DATE,
                    $this->CODE,
                    $this->LOCATION_ID,
                    $this->TRANSFER_TO_ID,
                    $this->NOTES,
                    $this->PREPARED_BY_ID,
                    $this->ACCOUNT_ID

                );

                return Redirect::route('companystock_transfer_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            } else {

                $this->validate(
                    [

                        'CODE' => 'required|max:20|unique:stock_transfer,code,' . $this->ID,
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                        'TRANSFER_TO_ID' => 'required|not_in:0'

                    ],
                    [],
                    [
                        'CODE' => 'Reference No.',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'TRANSFER_TO_ID' => 'Transfer To'
                    ]
                );


                $this->stockTransferServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->TRANSFER_TO_ID,
                    $this->NOTES,
                    $this->PREPARED_BY_ID
                );
                session()->flash('message', 'Successfully updated');
            }
            $this->updateCancel();
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function updateCancel()
    {
        $BA = $this->stockTransferServices->get($this->ID);
        if ($BA) {
            $this->getInfo($BA);
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
        return view('livewire.stock-transfer.stock-transfer-form');
    }
}
