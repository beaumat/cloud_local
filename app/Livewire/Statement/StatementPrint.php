<?php
namespace App\Livewire\Statement;

use App\Services\StatementServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Customer Soa Print')]
class StatementPrint extends Component
{

    public $dataList = [];
    private $statementServices;
    public function boot(StatementServices $statementServices   ) {
        $this->statementServices = $statementServices;
    }
    public function mount(int $id, string $datefrom, string $dateTo = '')
    {
        $this->dataList = $this->statementServices->CustomerSoaEntryList($id, $datefrom);
        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.statement.statement-print');
    }
}
