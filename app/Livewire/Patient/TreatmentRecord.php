<?php

namespace App\Livewire\Patient;

use App\Models\Locations;
use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PriceLevelLineServices;
use App\Services\ServiceChargeServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class TreatmentRecord extends Component
{


    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    #[Reactive]
    public int $CONTACT_ID;

    #[Reactive]
    public int $LOCK_LOCATION_ID;
    public $search = '';
    private $hemoServices;
    private $serviceChargeServices;
    private $accountServices;
    private $dateServices;
    private $priceLevelLineServices;
    private $locationServices;
    public function boot(HemoServices $hemoServices, ServiceChargeServices $serviceChargeServices, AccountServices $accountServices, DateServices $dateServices, PriceLevelLineServices $priceLevelLineServices, LocationServices $locationServices)
    {
        $this->hemoServices = $hemoServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->accountServices = $accountServices;
        $this->dateServices = $dateServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->locationServices = $locationServices;
    }
    public function CreateServiceCharge($DATE)
    {
   

        try {
            $isExist =  $this->serviceChargeServices->ServicesChargesExists($DATE, $this->CONTACT_ID, $this->LOCK_LOCATION_ID);
            if ($isExist) {
                session()->flash('error', "Invalid service charge on $DATE. already exists");
                return;
            }
    
    
            $datetimeLog = $this->dateServices->Now();
            DB::beginTransaction();
            
            $ID = (int) $this->serviceChargeServices->Store(
                '',
                $DATE,
                $this->CONTACT_ID,
                $this->LOCK_LOCATION_ID,
                "Create on Patient Profile On $datetimeLog",
                $this->accountServices->ACCOUNTS_RECEIVABLE_ID,
                0,
                0,
                0,
                0,
                0,
                false
            );


            $PRICE_LEVEL_ID = 0;
            $dataItem =  $this->hemoServices->ItemListWithIsCashier($this->CONTACT_ID, $this->LOCK_LOCATION_ID, $this->DATE);
            $dataLoc = $this->locationServices->get($this->LOCK_LOCATION_ID);
            if ($dataLoc) {
                if ($dataLoc->PRICE_LEVEL_ID > 0) {
                    $PRICE_LEVEL_ID = (int) $dataLoc->PRICE_LEVEL_ID ?? 0;
                }
            }

            foreach ($dataItem as $list) {
                $RATE = 0;
                if ($PRICE_LEVEL_ID > 0) {
                    $RATE = (float) $this->priceLevelLineServices->GetPriceByLocation($this->LOCATION_ID, $list->ITEM_ID);
                } else {
                    $RATE = (float) $list->RATE ?? 0;
                }

                $AMOUNT = $list->QUANTITY  * $RATE;

                $SC_ITEM_ID = $this->serviceChargeServices->ItemStore(
                    $ID,
                    $list->ITEM_ID,
                    $list->QUANTITY,
                    $list->UNIT_ID ?? 0,
                    $list->UNIT_BASE_QUANTITY ?? 1,
                    $RATE ?? 0,
                    0,
                    $AMOUNT,
                    $list->TAXABLE,
                    0,
                    0,
                    $list->COGS_ACCOUNT_ID ?? 0,
                    $list->ASSET_ACCOUNT_ID ?? 0,
                    0,
                    0,
                    false,
                    $PRICE_LEVEL_ID
                );
                $this->hemoServices->ItemUpdateSC_ITEM_ID($list->ID, $list->HEMO_ID, $list->ITEM_ID, $SC_ITEM_ID);
            }
            $this->serviceChargeServices->ReComputed($this->ID);
            DB::commit();
        } catch (\Throwable $th) {

            DB::rollBack();
            session()->flash("error", $th->getMessage());
        }
    }
    public function render()
    {
        $dataList = $this->hemoServices->PatientRecord($this->search, $this->CONTACT_ID, 15, $this->LOCK_LOCATION_ID);
        return view('livewire.patient.treatment-record', ['dataList' => $dataList]);
    }
}
