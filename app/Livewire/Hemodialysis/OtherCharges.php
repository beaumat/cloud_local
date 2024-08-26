<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\ItemServices;
use App\Services\ItemSubClassServices;
use App\Services\ItemTreatmentServices;
use App\Services\ServiceChargeServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class OtherCharges extends Component
{
    #[Reactive]
    public int $HEMO_ID;
    public $hemoData;
    public bool $showModal = false;
    public int $SUB_CLASS_ID;
    public string $ITEM_SUB_NAME;
    private $itemSubClassServices;
    private $itemServices;
    public $itemList = [];
    public $search = '';
    private $hemoServices;
    private $serviceChargeServices;
    private $itemTreatmentServices;
    private $unitOfMeasureServices;
    public function boot(
        ItemSubClassServices  $itemSubClassServices,
        ItemServices $itemServices,
        HemoServices $hemoServices,
        ServiceChargeServices $serviceChargeServices,
        ItemTreatmentServices    $itemTreatmentServices,
        UnitOfMeasureServices    $unitOfMeasureServices
    ) {
        $this->itemSubClassServices = $itemSubClassServices;
        $this->itemServices = $itemServices;
        $this->hemoServices = $hemoServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
    }
    #[On('open-list-sub-item')]
    public function openModal($result)
    {
        $this->SUB_CLASS_ID = $result['SUB_CLASS_ID'];
        $data = $this->itemSubClassServices->Get($this->SUB_CLASS_ID);
        if ($data) {
            $this->hemoData = $this->hemoServices->Get($this->HEMO_ID);
            $this->ITEM_SUB_NAME = $data->DESCRIPTION ?? '';
            $this->showModal = true;
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function AddCharge(int $ITEM_ID)
    {

        $data = $this->itemServices->get($ITEM_ID);
        if ($data) {
            $QTY = 1;
            $QTY_BASED = 1;
            $PRICE_LEVEL_ID  = 0;
            $UNIT_ID  = $data->BASE_UNIT_ID ?? 0;
            $RATE = $data->RATE ?? 0;
            $TAX = $data->TAXABLE ?? 0;
            $SK_LINE_ID = null;
            DB::beginTransaction();
            try {

                $scData =  $this->serviceChargeServices->ServicesChargesGetFirst($this->hemoData->DATE, $this->hemoData->CUSTOMER_ID, $this->hemoData->LOCATION_ID);

                if ($scData) {
          
                    $SC_ITEM_ID =   $this->serviceChargeServices->ItemStore($scData->ID, $ITEM_ID, $QTY, $UNIT_ID, $QTY_BASED, $RATE, 0, $QTY * $RATE, $TAX, 0, 0, $data->COGS_ACCOUNT_ID ?? 0, $data->ASSET_ACCOUNT_ID ?? 0, $data->GL_ACCOUNT_ID ?? 0, 0, false, $PRICE_LEVEL_ID);
                    $SK_LINE_ID =  $this->hemoServices->ItemStore($this->HEMO_ID, $ITEM_ID, $QTY, $UNIT_ID, $QTY_BASED, true, false, true, $SC_ITEM_ID);
                    $this->serviceChargeServices->ReComputed($scData->ID); // recompute balance
                } else {
                    $SK_LINE_ID = $this->hemoServices->ItemStore($this->HEMO_ID, $ITEM_ID, 1, $UNIT_ID, 1, true, false, true);
                }

                $dataTrigger = $this->itemTreatmentServices->getItemTrigger($ITEM_ID, $this->hemoData->LOCATION_ID, $UNIT_ID);
                foreach ($dataTrigger  as $list) {
                    $trUnitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($list->ITEM_ID, $list->UNIT_ID ?? 0);
                    $TR_UNIT_BASE_QUANTITY = (float) $trUnitRelated['QUANTITY'];
                    $this->hemoServices->ItemStore($this->HEMO_ID, $list->ITEM_ID, $list->QUANTITY, $list->UNIT_ID ?? 0, $TR_UNIT_BASE_QUANTITY, true, true, false, null, $SK_LINE_ID);
                }

                DB::commit();
                session()->flash('message', 'Successsfully added');
            } catch (\Throwable $th) {
                session()->flash('error', $th->getMessage());
                DB::rollBack();
                return;
            }

            $this->dispatch('refresh-item-treatment');
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
        if ($this->showModal) {
            $this->itemList = $this->itemServices->getItemListBySubId($this->SUB_CLASS_ID, $this->search);
        }
        return view('livewire.hemodialysis.other-charges');
    }
}
