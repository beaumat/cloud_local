<?php

namespace App\Livewire\AccountingReport;

use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Transaction Journal Report')]
class TransactionJournalReport extends Component
{
    public function render()
    {
        return view('livewire.accounting-report.transaction-journal-report');
    }
}
