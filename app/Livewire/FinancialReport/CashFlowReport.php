<?php

namespace App\Livewire\FinancialReport;

use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Cash Flow Report')]
class CashFlowReport extends Component
{   
    public string $DATE;
    public int $LOCATION_ID;
    public $locationList = [];


    public function render()
    {
        return view('livewire.financial-report.cash-flow-report');
    }
}
