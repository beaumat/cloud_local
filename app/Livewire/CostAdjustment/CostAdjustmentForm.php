<?php

namespace App\Livewire\CostAdjustment;

use App\Services\CostAdjustmentServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cost Adjustment')]
class CostAdjustmentForm extends Component
{

    public int $ID;
    public string $CODE;
    public string $DATE;
    public int $LOCATION_ID;
    public $locationList = [];
    public bool $Modify = false;
    public int $STATUS = 0;
    public string $STATUS_DESCRIPTION = '';
    private $costAdjustmentServices;
    private $locationServices;
    private $userServices;
    private $documentStatusServices;
    public function boot(
        CostAdjustmentServices $costAdjustmentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices
    ) {
        $this->costAdjustmentServices = $costAdjustmentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
    }
    private function loadList()
    {
        $this->locationList = $this->locationServices->getList();
    }
    public function mount($id = null)
    {
        try {

            if (is_numeric($id)) {
                $data = $this->costAdjustmentServices->Get($id);
                if ($data) {
                    $this->loadList();
                    $this->getInfo($data);
                    $this->Modify = false;
                    return;
                }
                return Redirect::route('companycost_adjustment')->with('error', 'record not found');
            }

            $this->loadList();
            $this->LOCATION_ID = $this->userServices->getLocationDefault();
            $this->DATE = $this->userServices->getTransactionDateDefault();
            $this->CODE = "";
            $this->ID = 0;
            $this->Modify = true;
            $this->STATUS_DESCRIPTION = "";
            $this->STATUS = 0;

        } catch (\Throwable $th) {
            return Redirect::route('companycost_adjustment')->with('error', $th->getMessage());
        }

    }
    public function save()
    {

        $this->validate([
            'DATE' => 'required|date',
            'LOCATION_ID' => 'required|not_in:0|exists:location,id',
            'CODE' => 'nullable|max:20|unique:cost_adjustment,code,' . ($this->ID > 0 ? $this->ID : 'NULL') . ',id',
        ]);

        try {
            if ($this->ID == 0) {
                $this->ID = $this->costAdjustmentServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->LOCATION_ID
                );
                return redirect::route('companycost_adjustment_edit', ['id' => $this->ID])
                    ->with('message', 'Successfully created!');
            }

            $this->costAdjustmentServices->Update(
                $this->ID,
                $this->CODE,
                $this->DATE,
                $this->LOCATION_ID
            );
            $this->Modify = false;
            session()->flash('message', 'Successfully updated');
        } catch (\Throwable $th) {
            session()->flash('error', $th);
        }
    }
    public function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->CODE = $data->CODE;
        $this->DATE = $data->DATE;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->STATUS = $data->STATUS;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    public function updateCancel()
    {
        return redirect::route('companycost_adjustment_edit', ['id' => $this->ID]);
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function delete()
    {
        $this->costAdjustmentServices->Delete($this->ID);
    }
    public function posted()
    {
        $this->costAdjustmentServices->StatusUpdate($this->ID, 15);
        return redirect::route('companycost_adjustment_edit', ['id' => $this->ID])->with('message', "Successfully posted!");
    }
    public function render()
    {
        return view('livewire.cost-adjustment.cost-adjustment-form');
    }
}
