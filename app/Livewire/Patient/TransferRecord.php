<?php

namespace App\Livewire\Patient;

use App\Services\PatientTransferServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class TransferRecord extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    #[Reactive]
    public int $PATIENT_ID;

    public string $NOTES;
    public $DATE_TRANSFER;

    public string $E_NOTES;
    public int $E_ID;
    private $patientTransferServices;
    public function boot(PatientTransferServices $patientTransferServices)
    {
        $this->patientTransferServices = $patientTransferServices;
    }
    public function save()
    {
        $this->patientTransferServices->store($this->PATIENT_ID, $this->DATE_TRANSFER, $this->NOTES);
    }
    public function update()
    {

        $this->patientTransferServices->update($this->E_ID, $this->E_NOTES);
    }
    public function edit(int $ID)
    {

        $data =  $this->patientTransferServices->get($ID);
        if ($data) {
            $this->E_ID = $data->ID;
            $this->E_NOTES = $data->NOTES ?? '';
        }
    }
    public function delete(int $ID)
    {
        $this->patientTransferServices->delete($ID);
    }

    public function render()
    {
        $dataList = $this->patientTransferServices->list($this->PATIENT_ID,25);
        return view('livewire.patient.transfer-record',['dataList' => $dataList]);
    }
}
