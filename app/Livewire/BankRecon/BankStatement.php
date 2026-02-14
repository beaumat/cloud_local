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
    #[Reactive()]
    public int $ACCOUNT_ID;
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
        $this->dataList = $this->bankStatementServices->getbankStatementRecon($this->BANK_STATEMENT_ID, $this->ACCOUNT_ID, $this->search);
    }
    public function getMatching()
    {

    }
    public function FindEntry(float $isCredit, int $ID)
    {
        $result = ['ID' => $ID];
        if ($isCredit > 0) {

            $this->dispatch('open-check', result: $result);
        } else {

            $this->dispatch('open-collection', result: $result);
        }

    }
    public function render()
    {
        $this->LoadList();
        return view('livewire.bank-recon.bank-statement');
    }
}
