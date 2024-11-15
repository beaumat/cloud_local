<?php

namespace App\Livewire\Deposit;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bank Deposit')]
class DepositForm extends Component
{
    public function render()
    {
        return view('livewire.deposit.deposit-form');
    }
}
