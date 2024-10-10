<?php

namespace App\Livewire\SalesReceipt;

use App\Services\LocationServices;
use App\Services\SalesReceiptServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Sales Receipt')]
class SalesReceiptList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 25;
    public int $locationid;
    public $dateFrom;
    public $dateTo;

    public $locationList = [];
    private $salesReceiptServices;
    private $locationServices;
    private $userServices;

    public function boot(SalesReceiptServices $salesReceiptServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->salesReceiptServices = $salesReceiptServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
        $this->dateFrom = $this->userServices->getTransactionDateDefault();
        $this->dateTo = $this->userServices->getTransactionDateDefault();
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $this->salesReceiptServices->Delete($id);
            DB::commit();
            session()->flash('message', 'Successfully deleted.');
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
        $dataList = $this->salesReceiptServices->Search(
            $this->search,
            $this->locationid,
            $this->perPage,
            $this->dateFrom,
            $this->dateTo
        );

        return view('livewire.sales-receipt.sales-receipt-list', ['dataList' => $dataList]);
    }
}
