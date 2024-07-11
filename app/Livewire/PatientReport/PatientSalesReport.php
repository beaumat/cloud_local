<?php

namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\DateServices;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Sales Report')]
class PatientSalesReport extends Component
{

    private $contactServices;
    private $dateServices;
    public function boot(ContactServices $contactServices, DateServices $dateServices)
     {
        $this->contactServices = $contactServices;
     }
    public function mount() {

    }
    public function render()
    {   
        return view('livewire.patient-report.patient-sales-report');
    }
}
