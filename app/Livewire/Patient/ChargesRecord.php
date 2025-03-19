<?php

namespace App\Livewire\Patient;

use App\Services\DateServices;
use App\Services\ServiceChargeServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class ChargesRecord extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    #[Reactive]
    public int $CONTACT_ID;
    #[Reactive]
    public int $LOCK_LOCATION_ID;

    #[Reactive()]
    public int $LOCATION_ID;
    public $yearList = [];
    public int $YEAR;
    public $search = "";
    private $serviceChargeServices;
    private $dateServices;
    public function boot(ServiceChargeServices $serviceChargeServices, DateServices $dateServices)
    {
        $this->serviceChargeServices = $serviceChargeServices;
        $this->dateServices =  $dateServices;
    }
    public function mount()
    {
        $this->YEAR = $this->dateServices->NowYear();
        $this->yearList = $this->dateServices->YearList();
    }
    public function modifyPhilhealth() {
        $this->dispatch('open-philhealth-modifiy');
    }
    public function TransferRecordTo(int $ID)
    {
        $this->dispatch('open-transfer-contact', result: [
            'TRANSACTION_ID' => $ID,
            'LOCATION_ID'    => $this->LOCATION_ID,
            'IS_TREATMENT'   => false
        ]);
    }
    #[On('refresh-service-charge-record')]
    public function render()
    {
        $dataList = $this->serviceChargeServices->PatientRecord($this->search, $this->CONTACT_ID, 15, $this->LOCK_LOCATION_ID);
        return view('livewire.patient.charges-record', ['dataList' => $dataList]);
    }


}
