<?php

namespace App\Livewire\ReceivableReport;

use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Accounts Receivable Aging Reports")]
class AccountReceivableAging extends Component
{

    public string $DATE;
    public int $LOCATION_ID;
    public $locationList = [];

    private $locationServices;
    private $userServices;

    public function boot(
        LocationServices $locationServices,
        UserServices $userServices
    ) {

        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function generate(){
            
    }
    public function render()
    {
        return view('livewire.receivable-report.account-receivable-aging');
    }
}
