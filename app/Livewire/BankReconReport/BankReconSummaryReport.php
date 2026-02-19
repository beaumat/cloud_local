<?php
namespace App\Livewire\BankReconReport;

use App\Services\BankReconServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankReconSummaryReport extends Component
{
    #[Reactive]
    public $BANK_RECON_ID;
    private $bankReconServices;
    public function boot(BankReconServices $bankReconServices)
    {
        $this->bankReconServices = $bankReconServices;
    }
    public function mount()
    {
        
    }
    public function render()
    {
        return view('livewire.bank-recon-report.bank-recon-summary-report');
    }
}
