<?php

namespace App\Livewire\FinancialReport;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Income Statement Report')]
class IncomeStatementReport extends Component
{
    public function render()
    {
        return view('livewire.financial-report.income-statement-report');
    }
}
