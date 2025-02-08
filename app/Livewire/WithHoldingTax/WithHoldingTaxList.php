<?php

namespace App\Livewire\WithHoldingTax;

use App\Services\LocationServices;
use App\Services\UserServices;
use App\Services\WithholdingTaxServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Witholding Tax')]
class WithHoldingTaxList extends Component
{
    use WithPagination;

    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public int $locationid;
    public $locationList = [];
    private $withholdingTaxServices;
    private $userServices;
    private $locationServices;
    public function boot(WithholdingTaxServices $withholdingTaxServices, UserServices $userServices, LocationServices $locationServices)
    {
        $this->withholdingTaxServices = $withholdingTaxServices;
        $this->userServices = $userServices;
        $this->locationServices = $locationServices;
    }
    public function mount()
    {
        $this->locationid = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function updatedlocationid()
    {

        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function delete(int $ID)
    {
        $this->withholdingTaxServices->Delete($ID);
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
        $dataList = $this->withholdingTaxServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.with-holding-tax.with-holding-tax-list', ['dataList' => $dataList]);
    }
}
