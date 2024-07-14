<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class OtherDetails extends Component
{
    #[Reactive]
    public int $HEMO_ID;
    public string $SE_DETAILS;
    public string $SO_DETAILS;
    public int $BFR;
    public int $DFR;
    public int $DURATION;
    public string $DIALYZER;
    public string $DIALSATE_N;
    public string $DIALSATE_K;
    public string $DIALSATE_C;
    public bool $DETAILS_USE_NEXT;

    private $hemoServices;
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    public function mount()
    {
        $data = $this->hemoServices->Get($this->HEMO_ID);

        if ($data) {
            $this->SE_DETAILS = $data->SE_DETAILS ?? '';
            $this->SO_DETAILS =  $data->SO_DETAILS ?? '';
            $this->BFR = $data->BFR ?? '';
            $this->DFR = $data->DFR ?? '';
            $this->DURATION = $data->DURATION ?? 0;
            $this->DIALYZER = $data->DIALYZER ?? '';
            $this->DIALSATE_N =  $data->DIALSATE_N ?? '';
            $this->DIALSATE_K = $data->DIALSATE_K ?? '';
            $this->DIALSATE_C = $data->DIALSATE_C ?? '';
            $this->DETAILS_USE_NEXT = $data->DETAILS_USE_NEXT ?? false;
        }
    }
    public function save()
    {
        $this->hemoServices->SaveOthers(
            $this->HEMO_ID,
            $this->SE_DETAILS,
            $this->SO_DETAILS,
            $this->BFR,
            $this->DFR,
            $this->DURATION,
            $this->DIALYZER,
            $this->DIALSATE_N,
            $this->DIALSATE_K,
            $this->DIALSATE_C,
            $this->DETAILS_USE_NEXT

        );
        session()->flash('message','Save change successfully'); 
    }
    public function render()
    {
        return view('livewire.hemodialysis.other-details');
    }
}
