<?php

namespace App\Livewire\InventoryAdjustment;

use App\Services\AccountJournalServices;
use App\Services\InventoryAdjustmentServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Inventory Adjustment')]
class InventoryAdjustmentList extends Component
{
    use WithPagination;
    public int $perPage = 15;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $inventoryAdjustmentServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;
    private $itemInventoryServices;
    public function boot(InventoryAdjustmentServices $inventoryAdjustmentServices, LocationServices $locationServices, UserServices $userServices, AccountJournalServices $accountJournalServices, ItemInventoryServices $itemInventoryServices )
    {
        $this->inventoryAdjustmentServices = $inventoryAdjustmentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->itemInventoryServices = $itemInventoryServices;
    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {

            $this->inventoryAdjustmentServices->Delete($id);
            DB::commit();
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        $dataList = $this->inventoryAdjustmentServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.inventory-adjustment.inventory-adjustment-list', ['dataList' => $dataList]);
    }
    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->locationid );
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }

    }
}
