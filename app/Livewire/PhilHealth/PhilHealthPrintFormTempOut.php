<?php

namespace App\Livewire\PhilHealth;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('PhilHealth Printing Form Pre-sign')]
class PhilHealthPrintFormTempOut extends Component
{

    public $PRINT_ID = [];
    public function mount($id)
    {
        if (!$id) {
            $this->PRINT_ID = [];
            return;
        }

        $this->PRINT_ID = explode(',', $id);
        $this->dispatch('preview_print_form');
    }
    #[On('preview_print_form')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.phil-health.phil-health-print-form-temp-out');
    }
}
