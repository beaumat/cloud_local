<?php

namespace App\Livewire\Doctor;

use App\Services\WithholdingTaxServices;
use Livewire\Component;

class DoctorWithholdingTax extends Component
{   
    public $dataList = [];
    private $withholdingTaxServices;
    public int $contact_id = 0;
    public function boot(WithholdingTaxServices $withholdingTaxServices)
    {
        $this->withholdingTaxServices = $withholdingTaxServices;

        
    }
    public function mount($id = 0)
    {
        $this->contact_id = $id; // Initialize the contact_id with the provided id or default to 0
        // Initialize any properties or services needed for the component
        // For example, you might want to set a contact_id or fetch initial data
    }
    public function render()
    {   
        $this->dataList = $this->withholdingTaxServices->listViaContact($this->contact_id);
        // Fetch the list of withholding taxes for the contact_id
        return view('livewire.doctor.doctor-withholding-tax');
    }
}
