<?php
namespace App\Livewire\AccountingReport;

use App\Services\AccountJournalServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Trial Balance - Preview')]

class TrialBalanceGenerate extends Component
{

    private $selectedAccountType;
    private $selectedAccount;
    private $accountJournalServices;
    public $DATE_FROM;
    public $DATE_TO;
    public $LOCATION_ID;
    public $dataList = [];
    public function boot(AccountJournalServices $accountJournalServices)
    {
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

        try {
            $this->dataList = $this->accountJournalServices->getTrialBalance(
                $this->DATE_FROM,
                $this->DATE_TO,
                $this->LOCATION_ID,
                $this->selectedAccount,
                $this->selectedAccountType
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function render()
    {
        return view('livewire.accounting-report.trial-balance-generate');
    }
}
