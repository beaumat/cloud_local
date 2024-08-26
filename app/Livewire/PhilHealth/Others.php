<?php

namespace App\Livewire\PhilHealth;

use App\Services\PhilHealthServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Others extends Component
{
    #[Reactive]
    public int $PHILHEALTH_ID;
    public string $RR_NO;

    private $philHealthServices;
    public function boot(PhilHealthServices $philHealthServices)
    {
        $this->philHealthServices = $philHealthServices;
    }
    public function mount()
    {
        $data = $this->philHealthServices->get($this->PHILHEALTH_ID);
        if ($data) {
            $this->RR_NO = $data->RR_NO ?? '';
        }
    }
    public function saveData()
    {
        $this->philHealthServices->setRRUpdate($this->PHILHEALTH_ID, $this->RR_NO);
        session()->flash('message', 'Successfully update');
    }
    public function render()
    {
        return view('livewire.phil-health.others');
    }
}
