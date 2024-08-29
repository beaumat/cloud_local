<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\ItemServices;
use App\Services\ItemTreatmentServices;
use App\Services\LocationServices;
use App\Services\PriceLevelLineServices;
use App\Services\ServiceChargeServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class OtherChargesModal extends Component
{

    public int $ITEM_ID;
    public string $ITEM_NAME;
    public int $HEMO_ID;
    public float $QUANTITY;
    public bool $showModal;
    private $serviceChargeServices;
    private $itemTreatmentServices;
    private $unitOfMeasureServices;
    private $locationServices;
    private $priceLevelLineServices;
    private $itemServices;
    private $hemoServices;
    public function boot(
        ServiceChargeServices $serviceChargeServices,
        ItemTreatmentServices $itemTreatmentServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        LocationServices $locationServices,
        PriceLevelLineServices  $priceLevelLineServices,
        ItemServices $itemServices,
        HemoServices $hemoServices
    ) {
        $this->serviceChargeServices = $serviceChargeServices;
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->locationServices = $locationServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->itemServices = $itemServices;
        $this->hemoServices = $hemoServices;
    }
    #[On('adding-item')]
    public function openModal($result)
    {
        $this->QUANTITY = 0;
        $this->HEMO_ID = $result['HEMO_ID'];
        $this->ITEM_ID = $result['ITEM_ID'];
        $this->ITEM_NAME = $result['ITEM_NAME'];

        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function AddCharge()
    {
        $this->validate([
            'QUANTITY' => 'required|integer|min:1'
        ], [], [
            'QUANTITY' => 'Quantity'
        ]);
        $hemoData = $this->hemoServices->Get($this->HEMO_ID);
        $data = $this->itemServices->get($this->ITEM_ID);
        if ($data) {
            $QTY_BASED = 1;
            $PRICE_LEVEL_ID  = 0;
            $UNIT_ID  = $data->BASE_UNIT_ID ?? 0;
            $RATE = $data->RATE ?? 0;
            $TAX = $data->TAXABLE ?? 0;
            $SK_LINE_ID = null;
            DB::beginTransaction();
            try {
                $scData =  $this->serviceChargeServices->ServicesChargesGetFirst($hemoData->DATE, $hemoData->CUSTOMER_ID, $hemoData->LOCATION_ID);
                if ($scData) {
                    $dataLoc = $this->locationServices->get($hemoData->LOCATION_ID);
                    if ($dataLoc) {
                        if ($dataLoc->PRICE_LEVEL_ID > 0) {
                            $PRICE_LEVEL_ID = $dataLoc->PRICE_LEVEL_ID ?? 0;
                            if ($PRICE_LEVEL_ID > 0) {
                                $RATE = $this->priceLevelLineServices->PriceExists($this->ITEM_ID, $hemoData->LOCATION_ID);
                            }
                        }
                    }
                    $SC_ITEM_ID =   $this->serviceChargeServices->ItemStore($scData->ID, $this->ITEM_ID, $this->QUANTITY, $UNIT_ID, $QTY_BASED, $RATE, 0, $this->QUANTITY * $RATE, $TAX, 0, 0, $data->COGS_ACCOUNT_ID ?? 0, $data->ASSET_ACCOUNT_ID ?? 0, $data->GL_ACCOUNT_ID ?? 0, 0, false, $PRICE_LEVEL_ID);
                    $SK_LINE_ID =  $this->hemoServices->ItemStore($this->HEMO_ID, $this->ITEM_ID, $this->QUANTITY, $UNIT_ID, $QTY_BASED, true, false, true, $SC_ITEM_ID);
                    $this->serviceChargeServices->ReComputed($scData->ID); // recompute balance
                } else {
                    $SK_LINE_ID = $this->hemoServices->ItemStore($this->HEMO_ID, $this->ITEM_ID, $this->QUANTITY, $UNIT_ID, $QTY_BASED, true, false, true);
                }

                $dataTrigger = $this->itemTreatmentServices->getItemTrigger($this->ITEM_ID, $hemoData->LOCATION_ID, $UNIT_ID);
                foreach ($dataTrigger  as $list) {
                    $trUnitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($list->ITEM_ID, $list->UNIT_ID ?? 0);
                    $TR_UNIT_BASE_QUANTITY = (float) $trUnitRelated['QUANTITY'];
                    $R_QTY = $list->QUANTITY * $this->QUANTITY;
                    $this->hemoServices->ItemStore($this->HEMO_ID, $list->ITEM_ID, $R_QTY, $list->UNIT_ID ?? 0, $TR_UNIT_BASE_QUANTITY, true, true, false, null, $SK_LINE_ID);
                }

                DB::commit();
                $this->closeModal();
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
        return view('livewire.hemodialysis.other-charges-modal');
    }
}
