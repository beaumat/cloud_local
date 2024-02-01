<?php

namespace App\Livewire\PaymentMethod;

use App\Services\PaymentMethodServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Payment Method')]
class PaymentMethodList extends Component
{

    public $paymentMethods = [];
    public $search = '';
    public function updatedsearch(PaymentMethodServices $paymentMethodServices)
    {
        $this->paymentMethods = $paymentMethodServices->Search($this->search);
    }
    public function delete($id, PaymentMethodServices $paymentMethodServices)
    {
        try {
            $paymentMethodServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->paymentMethods = $paymentMethodServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(PaymentMethodServices $paymentMethodServices)
    {
        $this->paymentMethods = $paymentMethodServices->Search($this->search);
    }

    public function render()
    {
        return view('livewire.payment-method.payment-method-list');
    }
}
