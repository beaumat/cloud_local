<?php

namespace App\Livewire\FinancialReport;

use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Cash Flow Report')]
class CashFlowReport extends Component
{
    public function render()
    {
        return view('livewire.financial-report.cash-flow-report');
    }
}
