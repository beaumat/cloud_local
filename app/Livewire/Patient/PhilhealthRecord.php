<?php

namespace App\Livewire\Patient;

use App\Services\PhilHealthServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class PhilhealthRecord extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    #[Reactive]
    public int $CONTACT_ID;
    public $search = "";

    private $philHealthServices;
    public function boot(PhilHealthServices $philHealthServices)
    {
        $this->philHealthServices = $philHealthServices;
    }

    public function render()
    {
        $dataList = $this->philHealthServices->PatientRecord($this->search, $this->CONTACT_ID, 15);
        return view('livewire.patient.philhealth-record', ['dataList' => $dataList]);
    }
}
