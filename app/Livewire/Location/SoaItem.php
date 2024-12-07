<?php

namespace App\Livewire\Location;

use App\Services\ItemSoaServices;
use App\Services\LocationServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Soa Items')]
class SoaItem extends Component
{

    public int $LOCATION_ID;
    public int $ID;
    public int $TYPE;
    public string $ITEM_NAME;
    public string $UNIT_NAME;
    public float $RATE;

    public  $editid = null;
    public int $editTYPE;
    public string $editITEM_NAME;
    public string $editUNIT_NAME;
    public float $editRATE;



    public $dataList = [];
    public $search;
    public $typeList = [];
    private $itemSoaServices;
    private $locationServices;
    public function boot(ItemSoaServices $itemSoaServices, LocationServices $locationServices)
    {
        $this->itemSoaServices = $itemSoaServices;
        $this->locationServices = $locationServices;
    }

    public function mount($id)
    {
        $data = $this->locationServices->get($id);

        if ($data) {

            $this->LOCATION_ID = $id;
            $this->typeList = $this->itemSoaServices->TypeList();
            $this->CleanAdd();

            return;
        }

        return Redirect::route('maintenancesettingslocation')->with('Location Not found');
    }
    public function CleanAdd()
    {

        $this->ID = 0;
        $this->TYPE = 0;
        $this->ITEM_NAME = '';
        $this->UNIT_NAME = '';
        $this->RATE = 0;
    }
    public function Add()
    {

        $this->validate(
            [
                'TYPE' => 'required|numeric|exists:soa_item_type,id',
                'ITEM_NAME' => 'required|string',
                'UNIT_NAME' => 'required|string',
                'RATE'      => 'required|numeric|not_in:0',
            ],
            [],
            [
                'TYPE'      => 'Type',
                'ITEM_NAME' => 'Item Name',
                'UNIT_NAME' => 'Unit Name',
                'RATE'      => 'Rate'
            ]
        );

        try {

            $this->itemSoaServices->Store(
                $this->LOCATION_ID,
                $this->TYPE,
                $this->ITEM_NAME,
                $this->UNIT_NAME,
                $this->RATE
            );

            $this->CleanAdd();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }



    public function Edit(int $ID)
    {
        $data = $this->itemSoaServices->Get($ID);

        if ($data) {
            $this->editid = (int)  $data->ID ?? 0;

            $this->editTYPE = $data->TYPE;
            $this->editITEM_NAME = $data->ITEM_NAME ?? '';
            $this->editUNIT_NAME = $data->UNIT_NAME ?? '';
            $this->editRATE = $data->RATE ?? 0;
        }
    }
    public function Update()
    {

        $this->validate(
            [
                'editTYPE'      => 'required|numeric|exists:soa_item_type,id',
                'editITEM_NAME' => 'required|string',
                'editUNIT_NAME' => 'required|string',
                'editRATE'      => 'required|numeric|not_in:0',
            ],
            [],
            [
                'editTYPE'      => 'Type',
                'editITEM_NAME' => 'Item Name',
                'editUNIT_NAME' => 'Unit Name',
                'editRATE'      => 'Rate'
            ]
        );


        try {
            $this->itemSoaServices->update(
                $this->editid,
                $this->editTYPE,
                $this->editITEM_NAME,
                $this->editUNIT_NAME,
                $this->editRATE
            );
            $this->Canceled();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    public function Delete(int $ID)
    {
        dd('Stop');
        // return;
        // $this->itemSoaServices->Delete($ID);
    }
    public function Canceled()
    {
        $this->editid = null;
        $this->editTYPE =  0;
        $this->editITEM_NAME = '';
        $this->editUNIT_NAME = '';
        $this->editRATE = 0;
    }
    private function refreshList()
    {
        $this->dataList = $this->itemSoaServices->Search($this->search, $this->LOCATION_ID);
    }
    public function render()
    {

        $this->refreshList();
        return view('livewire.location.soa-item');
    }
}
