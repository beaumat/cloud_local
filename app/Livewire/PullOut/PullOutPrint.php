<?php

namespace App\Livewire\PullOut;

use App\Services\ContactServices;
use App\Services\PullOutServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Pull Out Printing')]
class PullOutPrint extends Component
{

    public string $DATE;
    public string $CODE;
    public string $PREPARED_BY_NAME;
    public string $NOTES;
    public $itemList = [];
    private $pullOutServices;
    private $contactServices;
    public function boot(PullOutServices $pullOutServices, ContactServices $contactServices)
    {
        $this->pullOutServices = $pullOutServices;
        $this->contactServices = $contactServices;
    }
    public function mount($id = null)
    {
        $data = $this->pullOutServices->get($id);

        if ($data) {
            $this->DATE = $data->DATE;
            $this->CODE = $data->CODE;
            $this->NOTES = $data->NOTES;
            $pd = $this->contactServices->get($data->PREPARED_BY_ID, 2);
            if ($pd) {
                $this->PREPARED_BY_NAME = $pd->NAME ?? '';
            }
            $this->itemList  = $this->pullOutServices->ItemView($data->ID);
        }
        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.pull-out.pull-out-print');
    }
}
