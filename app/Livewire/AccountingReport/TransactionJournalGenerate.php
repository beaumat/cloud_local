<?php
namespace App\Livewire\AccountingReport;

use App\Exports\TransactionJournalReportExport;
use App\Services\AccountJournalServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Transaction Journal - Preview')]
class TransactionJournalGenerate extends Component
{

    private $selectedAccountType;
    private $selectedAccount;
    private $accountJournalServices;
    public $DATE_FROM;
    public $DATE_TO;
    public $LOCATION_ID;


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
        $this->dispatch('reload');
    }

    public function Generete()
    {

        try {

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
    public function export()
    {

       $dataList = $this->accountJournalServices->getTransactionJournal(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID,
            $this->selectedAccount ?? [],
            $this->selectedAccountType ?? [],
        );


        return Excel::download(new TransactionJournalReportExport(
            $dataList
        ), 'transaction-journal-export.xlsx');
    }
    public function setZero(int $JOURNAL_ID)
    {
        $this->accountJournalServices->setZeroUpdate($JOURNAL_ID);
        session()->flash('message', 'Success update');
    }
    public function openDetails(int $JN)
    {
        $url = $this->accountJournalServices->getUrlBy($JN);
        $this->js("window.open('$url', '_blank')");

    }
    #[On('reload')]
    public function render()
    {

        $dataList = $this->accountJournalServices->getTransactionJournal(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID,
            $this->selectedAccount ?? [],
            $this->selectedAccountType ?? [],
        );

        return view('livewire.accounting-report.transaction-journal-generate', ['dataList' => $dataList]);
    }
}
