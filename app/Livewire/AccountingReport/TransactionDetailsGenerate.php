<?php

namespace App\Livewire\AccountingReport;

use App\Services\AccountJournalServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Account Transaction - Preview')]
class TransactionDetailsGenerate extends Component
{
    public $dataList = [];
    private $accountJournalServices;
    public function boot(AccountJournalServices $accountJournalServices) {
        $this->accountJournalServices = $accountJournalServices;
    }
    public function mount($from, $to, $location, string $account, string $accounttype)
    {
        $this->DATE_FROM           = $from;
        $this->DATE_TO             = $to;
        $this->LOCATION_ID         = $location;
        $this->selectedAccount     = $account !== 'none' ? explode(',', $account) : [];
        $this->selectedAccountType = $accounttype !== 'none' ? explode(',', $accounttype) : [];
        $this->Generete();
    }

    public function Generete()
    {

        $this->dataList = $this->accountJournalServices->getAccountTransaction(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID,
            $this->selectedAccount,
            $this->selectedAccountType
        );
    }

    public function openDetails(int $JN)
    {
        $url = $this->accountJournalServices->getUrlBy($JN);

        $this->js("window.open('$url', '_blank')");

    }
    public function render()
    {
        return view('livewire.accounting-report.transaction-details-generate');
    }
}
