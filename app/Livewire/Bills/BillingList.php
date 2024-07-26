<?php

namespace App\Livewire\Bills;

use App\Services\AccountJournalServices;
use App\Services\BillingServices;
use App\Services\DocumentTypeServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\ObjectServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Add Stock')]
class BillingList extends Component
{

    use WithPagination;
    public int $perPage = 15;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $billingServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;
    private $objectServices;
    private $itemInventoryServices;
    private $documentTypeServices;
    public function boot(
        BillingServices $billingServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountJournalServices $accountJournalServices,
        ObjectServices $objectServices,
        ItemInventoryServices $itemInventoryServices,
        DocumentTypeServices $documentTypeServices

    ) {
        $this->billingServices = $billingServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountJournalServices =  $accountJournalServices;
        $this->objectServices = $objectServices;
        $this->itemInventoryServices = $itemInventoryServices;
        $this->documentTypeServices = $documentTypeServices;
    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }

    private function ResetEntries(int $BILL_ID)
    {
        $MAIN_OBJECT_TYPE = (int) $this->objectServices->ObjectTypeID('BILL');
        $ITEM_OBJECT_TYPE = (int) $this->objectServices->ObjectTypeID('BILL_ITEMS');
        $EXPENSES_OBJECT_TYPE = (int) $this->objectServices->ObjectTypeID('BILL_EXPENSES');
        $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($MAIN_OBJECT_TYPE, $BILL_ID);
        $SOURCE_REF_TYPE = (int) $this->documentTypeServices->getId('Bill');
        $data = $this->billingServices->get($BILL_ID);

        if ($data) {

            $items =  $this->billingServices->ItemView($data->ID);
            foreach ($items as $list) {
                $this->itemInventoryServices->InventoryModify($list->ITEM_ID, $data->LOCATION_ID, $list->ID, $SOURCE_REF_TYPE, $data->DATE, 0,  0, 0);
                $this->billingServices->updateJournal($list->ID, $list->ACCOUNT_ID, $JOURNAL_NO, $data->LOCATION_ID, $data->DATE, $list->ITEM_ID, $ITEM_OBJECT_TYPE, 0, 0, "ASSET");
            }

            $expenses =  $this->billingServices->ExpenseView($data->ID);
            foreach ($expenses as $list) {
                $this->billingServices->updateJournal($list->ID, $list->ACCOUNT_ID, $JOURNAL_NO, $data->LOCATION_ID, $data->DATE, 0, $EXPENSES_OBJECT_TYPE, 0, 0, "EXPENSES");
            }
            $this->billingServices->updateJournal($data->ID, $data->ACCOUNTS_PAYABLE_ID, $JOURNAL_NO, $data->LOCATION_ID, $data->DATE, 0, $MAIN_OBJECT_TYPE, 0, 0, "AP");
            $this->billingServices->updateJournal($data->ID, $data->INPUT_TAX_ACCOUNT_ID ?? 0, $JOURNAL_NO, $data->LOCATION_ID, $data->DATE, 0, $MAIN_OBJECT_TYPE, 0, 0, "TAX");
        }
    }

    public function delete($BILL_ID)
    {
        DB::beginTransaction();
        try {


            $this->ResetEntries($BILL_ID);
            $this->billingServices->Delete($BILL_ID);
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
        $dataList = $this->billingServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.bills.billing-list', ['dataList' => $dataList]);
    }
}
