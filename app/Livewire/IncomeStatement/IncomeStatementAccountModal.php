<?php

namespace App\Livewire\IncomeStatement;

use Livewire\Attributes\On;
use Livewire\Component;

class IncomeStatementAccountModal extends Component
{
    

    public int $LOCATION_ID;
    public int $YEAR;
    public int $MONTH;
    public int $ACCOUNT_ID;
    public bool $showModal = false;

    #[On("open-income-account-details")]
    public function openModal($result) {

        $this->LOCATION_ID = (int) $result['LOCATION_ID'];
        $this->YEAR = (int) $result['YEAR'];
        $this->MONTH = (int) $result['MONTH'];
        $this->ACCOUNT_ID = (int) $result['ACCOUNT_ID'];

        $this->showModal = true;
    }

    public function closeModal() {
        $this->showModal =false;
    }

    public function render()
    {
        return view('livewire.income-statement.income-statement-account-modal');
    }
}
