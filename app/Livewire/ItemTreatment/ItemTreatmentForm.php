<?php

namespace App\Livewire\ItemTreatment;

use App\Services\ItemServices;
use App\Services\ItemTreatmentServices;
use App\Services\LocationServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Item Treatment")]
class ItemTreatmentForm extends Component
{

    public int $ID;
    public int $LOCATION_ID;
    public int $ITEM_ID;
    public int $UNIT_ID;
    public  int $NO_OF_USED;
    public  bool $INACTIVE;

    public $locationList = [];
    public $itemList = [];
    public $unitList = [];

    private $itemTreatmentServices;
    private $itemServices;
    private $locationServices;
    private $unitOfMeasureServices;
    public function boot(ItemTreatmentServices $itemTreatmentServices, ItemServices $itemServices, LocationServices $locationServices, UnitOfMeasureServices $unitOfMeasureServices)
    {
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->itemServices = $itemServices;
        $this->locationServices = $locationServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
    }

    public function mount($id = null)
    {
        $this->locationList = $this->locationServices->getList();
        $this->itemList = $this->itemServices->getInventoryItem(false);
        $this->unitList = [];
        
        if (is_numeric($id)) {
            $data = $this->itemTreatmentServices->Get($id);

            if ($data) {
                $this->ID = $data->ID;
                $this->LOCATION_ID  = $data->LOCATION_ID;
                $this->ITEM_ID =  $data->ITEM_ID;
                $this->UNIT_ID = $data->UNIT_ID ?? 0;
                $this->NO_OF_USED = $data->NO_OF_USED;
                $this->INACTIVE = $data->INACTIVE;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceothersitem_treatment')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->LOCATION_ID = 0;
        $this->ITEM_ID = 0;
        $this->UNIT_ID = 0;
        $this->NO_OF_USED = 0;
        $this->INACTIVE = false;
    }


    public function save()
    {

        $this->validate(
            [
                'LOCATION_ID' => 'required|not_in:0',
                'ITEM_ID' => 'required|not_in:0',
            ],
            [],
            [
                'LOCATION_ID' => 'Location',
                'ITEM_ID' => 'Item',
            ]
        );


        try {
            DB::beginTransaction();
            if ($this->ID === 0) {
                $this->ID = $this->itemTreatmentServices->Store($this->LOCATION_ID, $this->ITEM_ID, $this->UNIT_ID, $this->NO_OF_USED);
                DB::commit();
                return Redirect::route('maintenanceothersitem_treatment_edit', ['id' => $this->ID])->with('message', 'Successfully created.');
            } else {
                $this->itemTreatmentServices->Update($this->ID, $this->LOCATION_ID, $this->ITEM_ID, $this->UNIT_ID, $this->NO_OF_USED, $this->INACTIVE);
                DB::commit();
                session()->flash('message', 'Successfully updated.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
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
        return view('livewire.item-treatment.item-treatment-form');
    }
}
