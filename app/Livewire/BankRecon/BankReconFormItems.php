<?php

namespace App\Livewire\BankRecon;

use App\Services\BankReconServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankReconFormItems extends Component
{
    #[Reactive]
    public int $ACCOUNT_RECONCILIATION_ID;
    #[Reactive]
    public int $STATUS;
    public $dataList = [];
    public $search;
    private $bankReconServices;
    public function boot(BankReconServices $bankReconServices)
    {
        $this->bankReconServices = $bankReconServices;
    }
    public function delete(int $ID)
    {
        $this->bankReconServices->ItemDelete($ID, $this->ACCOUNT_RECONCILIATION_ID);
        $this->dispatch('refresh-details');

    }
    #[On('refresh-item')]
    public function render()
    {
        $this->dataList = $this->bankReconServices->ItemList($this->ACCOUNT_RECONCILIATION_ID, $this->search);

        return view('livewire.bank-recon.bank-recon-form-items');
    }
}
