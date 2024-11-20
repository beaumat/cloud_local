<?php

namespace App\Livewire\ReceivableReport;

use App\Services\AgingServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Customer Balance Report")]
class CustomerBalance extends Component
{


    public string $DATE;
    public int $LOCATION_ID;
    public $dataList = [];
    public $locationList = [];
    private $locationServices;
    private $userServices;
    private $agingServices;

    public function boot(
        LocationServices $locationServices,
        UserServices $userServices,
        AgingServices $agingServices
    ) {

        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->agingServices = $agingServices;
    }

    public function mount()
    {
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function generate() {

        $this->dataList = $this->agingServices->CustomerBalance($this->DATE, $this->LOCATION_ID,[]);
    }
    public function render()
    {
        return view('livewire.receivable-report.customer-balance');
    }
}
