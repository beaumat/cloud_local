<?php

namespace App\Livewire\DoctorBatchPayment;

use App\Services\BillPaymentServices;
use App\Services\DoctorBatchServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillPaymentListModal extends Component
{

    #[Reactive]
    public int $DOCTOR_BATCH_ID;

    public $dataList = [];
    public bool $showModal = false;
    private $doctorBatchServices;
    private $billPaymentServices;
    public function boot(DoctorBatchServices $doctorBatchServices, BillPaymentServices $billPaymentServices)
    {
        $this->doctorBatchServices = $doctorBatchServices;
        $this->billPaymentServices = $billPaymentServices;
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }


    public function render()
    {
        return view('livewire.doctor-batch-payment.bill-payment-list-modal');
    }
}
