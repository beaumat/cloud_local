<?php

namespace App\Livewire\FinancialReport;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Balance Sheet Report')]
class BalanceSheetReport extends Component
{
    public function render()
    {
        return view('livewire.financial-report.balance-sheet-report');
    }
}
