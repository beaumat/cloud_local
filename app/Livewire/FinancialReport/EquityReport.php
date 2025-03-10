<?php

namespace App\Livewire\FinancialReport;

use Livewire\Attributes\Title;
use Livewire\Component;
#[Title("Equity Movement")]
class EquityReport extends Component
{

    public function boot() {

    }

    
    public function render()
    {
        return view('livewire.financial-report.equity-report');
    }
}
