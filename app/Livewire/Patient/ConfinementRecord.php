<?php

namespace App\Livewire\Patient;

use App\Services\PatientConfinementServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ConfinementRecord extends Component
{
    #[Reactive]
    public int $PATIENT_ID;
    public $DATE_START;
    public $DATE_END;
    public string $DESCRIPTION;
    public $E_ID = null;
    public $E_DATE_START;
    public $E_DATE_END;
    public string $E_DESCRIPTION;


    public $dataList = [];
    private $patientConfinementServices;
    public function boot(PatientConfinementServices $patientConfinementServices)
    {
        $this->patientConfinementServices = $patientConfinementServices;
    }
    public function save()
    {
        $this->patientConfinementServices->store(
            $this->DATE_START,
            $this->DATE_END,
            $this->DESCRIPTION,
            $this->PATIENT_ID
        );
    }
    public function update()
    {
        $this->patientConfinementServices->update(
            $this->E_ID,
            $this->E_DATE_START,
            $this->E_DATE_END,
            $this->E_DESCRIPTION
        );
    }
    public function edit(int $ID)
    {
        $data = $this->patientConfinementServices->get($ID);
        if ($data) {
            $this->E_ID = $data->ID;
            $this->E_DATE_START = $data->DATE_START ?? '';
            $this->E_DATE_END = $data->DATE_END ?? '';
            $this->E_DESCRIPTION = $data->DESCRIPTION ?? '';
        }
    }

    public function delete(int $ID)
    {
        $this->patientConfinementServices->delete($ID);
    }


    public function render()
    {

        $this->dataList = $this->patientConfinementServices->list($this->PATIENT_ID ?? 0);

        return view('livewire.patient.confinement-record');
    }
}
