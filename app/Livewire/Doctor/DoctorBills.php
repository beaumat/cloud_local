<?php

namespace App\Livewire\Doctor;

use App\Services\BillingServices;
use App\Services\BillPaymentServices;
use Livewire\Component;

class DoctorBills extends Component
{
    public $dataList = [];
    public int $contact_id = 0;
    private $billingServices;

    public function boot(BillingServices $billingServices)
    {
        $this->billingServices = $billingServices;
    }
    public function mount($id = 0)
    {
        // Initialize the contact_id with the provided id or default to 0
        $this->contact_id = $id;
    }

    public function render()
    {
        // Fetch the list of bills for the contact_id
        $this->dataList = $this->billingServices->billListViaContact($this->contact_id);

        return view('livewire.doctor.doctor-bills');
    }
}
