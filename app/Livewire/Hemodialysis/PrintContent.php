<?php

namespace App\Livewire\Hemodialysis;

use App\Services\ContactServices;
use App\Services\HemoServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PrintContent extends Component
{
    #[Reactive]
    public int $HEMO_ID;
    public $DATE;
    public $PHIC_NO;
    public string $FULL_NAME;
    public string $CODE;
    public string $DOB;
    public int $AGE = 0;
    private $hemoServices;
    private $contactServices;

    public function boot(HemoServices $hemoServices, ContactServices $contactServices)
    {
        $this->hemoServices = $hemoServices;
        $this->contactServices = $contactServices;
    }
    public function mount()
    {
        $data = $this->hemoServices->GetFirst($this->HEMO_ID);
        if ($data) {
            $this->FULL_NAME = $data->CONTACT_NAME;
            $this->DATE = $data->DATE;
            $this->CODE = $data->CODE;
            $this->PHIC_NO = $data->PHIC_NO;
            $this->DOB = $data->DATE_OF_BIRTH;
            $this->AGE = $this->contactServices->calculateUserAge($this->DOB);
        }
    }

    public array $collection = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];

    public function render()
    {


        return view('livewire.hemodialysis.print-content');
    }
}
