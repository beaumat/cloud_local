<?php

namespace App\Livewire\Bills;

use App\Services\BillingServices;
use App\Services\ContactServices;
use App\Services\LocationServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bill Print')]
class BillingPrint extends Component
{


    public int $BILL_ID;
    public string $CODE;
    public string $DATE;
    public  int $VENDOR_ID;
    public string $CONTACT_NAME;
    public int $LOCATION_ID;
    public string $LOCATION_NAME;
    public float $AMOUNT;
    public string $NOTES;
    public $itemList = [];

    private $billingServices;
    private $contactServices;
    private $locationServices;


    public function boot(BillingServices $billingServices, ContactServices $contactServices, LocationServices $locationServices)
    {
        $this->billingServices = $billingServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
    }
    public function mount($id = null)
    {

        $data = $this->billingServices->get($id);
        if ($data) {
            $this->BILL_ID = $data->ID;
            $this->VENDOR_ID = $data->VENDOR_ID;
            $this->LOCATION_ID = $data->LOCATION_ID;
            $this->CODE = $data->CODE;
            $this->DATE = $data->DATE;
            $this->NOTES = $data->NOTES;
            $this->AMOUNT = $data->AMOUNT;
            $con = $this->contactServices->get($this->VENDOR_ID, 0);

            if ($con) {
                $this->CONTACT_NAME = $con->PRINT_NAME_AS;
            }
            $loc = $this->locationServices->get($this->LOCATION_ID);
            if($loc) {
                $this->LOCATION_NAME  = $loc->NAME;
            }
            $this->itemList = $this->billingServices->ItemView($this->BILL_ID);
            $this->dispatch('preview_print');
            return;
        }
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.bills.billing-print');
    }
}
