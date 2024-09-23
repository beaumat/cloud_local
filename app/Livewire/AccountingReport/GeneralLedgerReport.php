<?php

namespace App\Livewire\AccountingReport;

use App\Services\AccountJournalServices;
use App\Services\AccountReportServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('General Ledger Report')]
class GeneralLedgerReport extends Component
{

    private $accountReportServices;
    public function boot(AccountReportServices $accountReportServices)
    {
        $this->accountReportServices = $accountReportServices;
    }
    public function generate() {




    }

    public function render()
    {
        return view('livewire.accounting-report.general-ledger-report');
    }
}
