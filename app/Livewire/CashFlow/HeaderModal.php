<?php

namespace App\Livewire\CashFlow;

use App\Services\CashFlowServices;
use Livewire\Attributes\On;
use Livewire\Component;

class HeaderModal extends Component
{

    public $showModal = false;



    public $ID;
    public $NAME;
    public int $LOCATION_ID;
    public int $LINE_NO;
    public bool $INACTIVE;
    private $cashFlowServices;
    public function boot(CashFlowServices $cashFlowServices)
    {
        $this->cashFlowServices  =  $cashFlowServices;
    }

    public function save()
    {

        $this->validate(
            [
                'NAME' => 'required|min:6',
                'LOCATION_ID' => 'required|numeric',
                'LINE_NO'     => 'required|numeric',
                'INACTIVE'  => 'required',
            ],
            [],
            []
        );


        try {
            $this->cashFlowServices->StoreHeader($this->NAME, $this->LOCATION_ID, $this->LINE_NO, $this->INACTIVE);
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }


    #[On('open-cf-header')]
    public function openModal($result)
    {
        $this->showModal = true;
        $dataID = $result['ID'] ?? 0;
        $this->LOCATION_ID = $result['LOCATION_ID'];
        if ($dataID > 0) {
            $data =    $this->cashFlowServices->getHeader($dataID);
            if ($data) {
                $this->ID = $data->ID;
                $this->NAME = $data->NAME;
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->LINE_NO = $data->LINE_NO;
                $this->INACTIVE = $data->INACTIVE;
                return;
            }
        }

        $this->LINE_NO = 0;
        $this->INACTIVE = false;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.cash-flow.header-modal');
    }
}
