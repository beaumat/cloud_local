<?php
namespace App\Livewire\ChartOfAccount;

use App\Services\AccountServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Chart Of Account')]
class ChartOfAccountList extends Component
{
    public $accounts     = [];
    public $search       = '';
    public $locationList = [];
    public int $locationid;
    private $accountServices;
    private $locationServices;
    private $userServices;
    public function boot(AccountServices $accountServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->accountServices  = $accountServices;
        $this->locationServices = $locationServices;
        $this->userServices     = $userServices;
    }
    public function delete($id)
    {
        try {
            $this->accountServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');

        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount()
    {

        $this->locationList = $this->locationServices->getList();
        $this->locationid   = $this->userServices->getLocationDefault();
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
        $this->accounts = $this->accountServices->Search($this->search, $this->locationid);
        return view('livewire.chart-of-account.chart-of-account-list');
    }
}
