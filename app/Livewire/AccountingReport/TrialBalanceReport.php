<?php

namespace App\Livewire\AccountingReport;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Trial Balance Report')]
class TrialBalanceReport extends Component
{
    public function render()
    {
        return view('livewire.accounting-report.trial-balance-report');
    }
}
