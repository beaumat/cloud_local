<?php

namespace App\Livewire\PatientReport;

use Livewire\Attributes\Title;
use Livewire\Component;

class PatientSalesReport extends Component
{
    #[Title('Sales Report')]
    public function render()
    {
        return view('livewire.patient-report.patient-sales-report');
    }
}
