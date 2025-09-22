<?php
namespace App\Livewire\ChartOfAccount;

use App\Services\AccountJournalEndingServices;
use App\Services\AccountJournalServices;
use App\Services\DateServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Chart of Account - Ending Balance')]
class ChartOfAccountEndingBalance extends Component
{
    public $dataList = [];
    private $accountJournalServices;
    private $accountJournalEndingServices;
    private $dateServices;
    public int $ACCOUNT_ID = 0;
    public int $LOCATION_ID;
    public function boot(AccountJournalServices $accountJournalServices, DateServices $dateService, AccountJournalEndingServices $accountJournalEndingServices)
    {
        $this->accountJournalServices       = $accountJournalServices;
        $this->accountJournalEndingServices = $accountJournalEndingServices;
    }
    public function mount(int $id, int $locationid)
    {
        $this->ACCOUNT_ID  = $id;
        $this->LOCATION_ID = $locationid;
        $this->dataList    = $this->accountJournalServices->getTransactionBalance($id, $locationid);

    }
    public function balanceUpdate()
    {
        $this->accountJournalEndingServices->ResetFirstEntryAccount($this->ACCOUNT_ID, $this->LOCATION_ID);
        session()->flash('request reset');
    }
    public function render()
    {
        return view('livewire.chart-of-account.chart-of-account-ending-balance');
    }
}
