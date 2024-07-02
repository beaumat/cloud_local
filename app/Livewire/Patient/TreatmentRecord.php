<?php

namespace App\Livewire\Patient;

use App\Services\HemoServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class TreatmentRecord extends Component
{


    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    #[Reactive]
    public int $CONTACT_ID;

    public $search = '';
    private $hemoServices;
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    public function render()
    {

        $dataList = $this->hemoServices->PatientRecord($this->search, $this->CONTACT_ID, 15);

        return view('livewire.patient.treatment-record', ['dataList' => $dataList]);
    }
}
