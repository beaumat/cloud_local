<?php

namespace App\Livewire\AccountingReport;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('General Ledger Report')]
class GeneralLedgerReport extends Component
{
    public function render()
    {
        return view('livewire.accounting-report.general-ledger-report');
    }
}
