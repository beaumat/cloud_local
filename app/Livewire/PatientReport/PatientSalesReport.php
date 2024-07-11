<?php

namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\DateServices;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Sales Report')]
class PatientSalesReport extends Component
{
    public $contactList= [];
    public string $dateForm;
    public string $dateTo;
    private $contactServices;
    private $dateServices;
    public function boot(ContactServices $contactServices, DateServices $dateServices)
     {
        $this->contactServices = $contactServices;
        $this->dateServices =   $dateServices;
     }
    public function mount() {
        $this->dateForm = $this->dateServices->NowDate();
        $this->dateTo = $this->dateServices->NowDate();
        $this->contactList = $this->contactServices->getList(3);
    }
    public function render()
    {       


        return view('livewire.patient-report.patient-sales-report');
    }
}
