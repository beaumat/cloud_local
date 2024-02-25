<?php

namespace App\Livewire\Bills;
use App\Services\BillingServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bills')]
class BillingList extends Component
{   
    public $dataList = [];
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $billingServices;
    private $locationServices;
    private $userServices;

    public function boot (BillingServices $billingServices,LocationServices $locationServices, UserServices $userServices)
    {
        $this->billingServices = $billingServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;

    }
    public function updatedlocationid()
    {
        $this->dataList = $this->billingServices->Search($this->search, $this->locationid);
    }
    public function updatedsearch()
    {
        $this->dataList = $this->billingServices->Search($this->search, $this->locationid);
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid =  $this->userServices->getLocationDefault();
        $this->dataList = $this->billingServices->Search($this->search, $this->locationid);
    }
    public function delete($id)
    {
        try {
            $this->billingServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->dataList = $this->billingServices->Search($this->search, $this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.bills.billing-list');
    }
}
