<?php

namespace App\Livewire\DoctorBatchPayment;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Doctor Batch Payment')]
class DoctorBatchForm extends Component
{

    public function boot() {

    }
    public function mount($id = null) {
            if(is_numeric($id)) {

            }
            else{

            }
    }
    
    public function render()
    {
        return view('livewire.doctor-batch-payment.doctor-batch-form');
    }
}
