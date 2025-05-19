<?php

namespace App\Livewire\DoctorBatchPayment;

use App\Services\DoctorBatchServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DoctorBatchPaidList extends Component
{
    #[Reactive]
    public int $DOCTOR_BATCH_ID;
    public $dataList = [];
    private $doctorBatchServices;

    public function boot(DoctorBatchServices $doctorBatchServices)
    {
        $this->doctorBatchServices = $doctorBatchServices;
    }
    public function deleteItem(int $id)
    {
        $this->doctorBatchServices->DeletePaid($id);
    }
    #[On('refresh-list-doctor-batch')]
    public function render()
    {
        $this->dataList = $this->doctorBatchServices->PaidList($this->DOCTOR_BATCH_ID);
        return view('livewire.doctor-batch-payment.doctor-batch-paid-list');
    }
}
