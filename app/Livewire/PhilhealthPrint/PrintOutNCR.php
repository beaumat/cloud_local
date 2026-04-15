<?php

namespace App\Livewire\PhilhealthPrint;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

<<<<<<< HEAD
#[Title('PhilHealth Print CF2')]
=======
#[Title('PhilHealth Print BPN Consent Form')]
>>>>>>> cad6f31879e7447d848943a8d4d247d12b40e9a1
class PrintOutNCR extends Component
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
        return view('livewire.philhealth-print.print-out-ncr');
    }
}
