<?php

namespace App\Livewire\AccountingReport;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('General Journal Report')]
class GeneralJournalReport extends Component
{
    public function render()
    {
        return view('livewire.accounting-report.general-journal-report');
    }
}
