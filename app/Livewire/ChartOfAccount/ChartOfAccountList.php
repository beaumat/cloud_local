<?php

namespace App\Livewire\ChartOfAccount;

use App\Services\AccountServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Chart Of Account')]
class ChartOfAccountList extends Component
{
    public $accounts = [];
    public $search = '';
    private $accountServices;
    public function boot(AccountServices $accountServices)
    {
        $this->accountServices = $accountServices;
    }

    public function delete($id)
    {
        try {
            $this->accountServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->accounts = $this->accountServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount()
    {
        $this->accounts = $this->accountServices->Search($this->search);
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function accountInactive(int $id, int $status)
    {
        $this->accountServices->Inactive($id, $status);
    }
    public function render()
    {
        $this->accounts = $this->accountServices->Search($this->search);
        return view('livewire.chart-of-account.chart-of-account-list');
    }
}
