<?php
namespace App\Livewire\BankRecon;

use App\Services\BankReconServices;
use App\Services\BankStatementServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankStatement extends Component
{
    #[Reactive()]
    public int $ACCOUNT_RECONCILIATION_ID;
    #[Reactive()]
    public int $BANK_STATEMENT_ID;

    public $search;
    private $bankRecordServices;
    private $bankStatementServices;
    public $dataList = [];
    public function boot(BankReconServices $bankRecordServices, BankStatementServices $bankStatementServices)
    {
        $this->bankRecordServices    = $bankRecordServices;
        $this->bankStatementServices = $bankStatementServices;
    }
    public function LoadList()
    {
        $this->dataList = $this->bankStatementServices->getbankStatement($this->BANK_STATEMENT_ID, $this->search);
    }
    public function getMatching()
    {

    }
    public function render()
    {
        $this->LoadList();
        return view('livewire.bank-recon.bank-statement');
    }
}
