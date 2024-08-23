<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\ItemServices;
use App\Services\ItemSubClassServices;
use App\Services\ServiceChargeServices;
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

    public function boot(ItemSubClassServices  $itemSubClassServices, ItemServices $itemServices, HemoServices $hemoServices, ServiceChargeServices $serviceChargeServices)
    {
        $this->itemSubClassServices = $itemSubClassServices;
        $this->itemServices = $itemServices;
        $this->hemoServices = $hemoServices;
        $this->serviceChargeServices = $serviceChargeServices;
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

            DB::beginTransaction();
            try {

                $scData =  $this->serviceChargeServices->ServicesChargesGetFirst($this->hemoData->DATE, $this->hemoData->CUSTOMER_ID, $this->hemoData->LOCATION_ID);

                if ($scData) {
                    // from cashier
                    $SC_ITEM_ID =   $this->serviceChargeServices->ItemStore($scData->ID, $ITEM_ID, $QTY, $UNIT_ID, $QTY_BASED, $RATE, 0, $QTY * $RATE, $TAX, 0, 0, $data->COGS_ACCOUNT_ID ?? 0, $data->ASSET_ACCOUNT_ID ?? 0, $data->GL_ACCOUNT_ID ?? 0, 0, false, $PRICE_LEVEL_ID);
                    // from treatment
                    $this->hemoServices->ItemStore($this->HEMO_ID, $ITEM_ID, $QTY, $UNIT_ID, $QTY_BASED, true, false, true, $SC_ITEM_ID);
                    // calculate
                    $this->serviceChargeServices->ReComputed($scData->ID); // recompute balance
                } else {
                    // only treatment
                    $this->hemoServices->ItemStore($this->HEMO_ID, $ITEM_ID, 1, $UNIT_ID, 1, true, false, true);
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
