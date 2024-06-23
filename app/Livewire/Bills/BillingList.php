<?php

namespace App\Livewire\Bills;

use App\Services\BillingServices;
use App\Services\LocationServices;
use App\Services\UserServices;
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

    public function boot(
        BillingServices $billingServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->billingServices = $billingServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;

    }
  
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
     
    }
    public function delete($id)
    {
        try {
            $this->billingServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
           
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        $dataList = $this->billingServices->Search($this->search, $this->locationid,$this->perPage);
        return view('livewire.bills.billing-list',['dataList' => $dataList]);
    }
}
