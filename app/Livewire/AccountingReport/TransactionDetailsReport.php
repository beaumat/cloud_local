<?php

namespace App\Livewire\AccountingReport;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Transaction Details Report')]
class TransactionDetailsReport extends Component
{
    public function render()
    {
        return view('livewire.accounting-report.transaction-details-report');
    }
}
