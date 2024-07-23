<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class OtherDetails extends Component
{
    #[Reactive]
    public int $HEMO_ID;
    #[Reactive]
    public bool $Modify;
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
    public bool $ORDER_USE_NEXT;
    private $hemoServices;
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    public function mount()
    {
        $this->reload();
    }
    #[On('cancel-other')]
    public function reload()
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
            $this->ORDER_USE_NEXT = $data->ORDER_USE_NEXT ?? false;
        }
    }
    #[On('save-other')]
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
            $this->DETAILS_USE_NEXT,
            $this->ORDER_USE_NEXT

        );
        //  session()->flash('message','Save change successfully'); 
    }
    public function detailsUseNext()
    {
        if ($this->Modify) {
            return;
        }
        $result = (bool) $this->hemoServices->UpdatedSpecialOrder($this->HEMO_ID);
        $this->DETAILS_USE_NEXT = $result;
        if ($result) {

            session()->flash('message', 'Special order will be used for the next treatment successfully.');
            return;
        }
        session()->flash('error', 'Special order will not be used for the next treatment.');
    }
    public function orderUseNext()
    {
        if ($this->Modify) {
            return;
        }
        $result = (bool) $this->hemoServices->UpdatedStandingOrder($this->HEMO_ID);
        $this->ORDER_USE_NEXT = $result;
        if ($result) {
            session()->flash('message', 'Standing order will be used for the next treatment successfully.');
            return;
        }
        session()->flash('error', 'Standing order will not be used for the next treatment.');
    }
    public function render()
    {
        return view('livewire.hemodialysis.other-details');
    }
}
