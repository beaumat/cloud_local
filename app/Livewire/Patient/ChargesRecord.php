<?php

namespace App\Livewire\Patient;

use App\Services\ServiceChargeServices;
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
    public $search = "";
    private $serviceChargeServices;
    public function boot(ServiceChargeServices $serviceChargeServices)
    {
        $this->serviceChargeServices = $serviceChargeServices;
    }
    public function render()
    {
        $dataList = $this->serviceChargeServices->PatientRecord($this->search, $this->CONTACT_ID, 15, $this->LOCK_LOCATION_ID);
        return view('livewire.patient.charges-record', ['dataList' => $dataList]);
    }
}
