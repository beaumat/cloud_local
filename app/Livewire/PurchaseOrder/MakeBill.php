<?php

namespace App\Livewire\PurchaseOrder;

use App\Services\BillingServices;
use App\Services\DateServices;
use App\Services\PurchaseOrderServices;
use Livewire\Attributes\On;
use Livewire\Component;

class MakeBill extends Component
{

    public string $DATE;
    public bool $showModal = false;
    public int $PO_ID;
    public int $LOCATION_ID;
    private $purchaseOrderServices;
    private  $dateServices;
    private $billingServices;
    public function boot(PurchaseOrderServices $purchaseOrderServices, DateServices $dateServices, BillingServices $billingServices)
    {
        $this->purchaseOrderServices = $purchaseOrderServices;
        $this->dateServices = $dateServices;
        $this->billingServices = $dateServices;
    }
    #[On('open-make-bill')]
    public function openModal($purchase)
    {   
        $dataPO = $this->purchaseOrderServices->get( $purchase['PO_ID']);

        if($dataPO) {
            $this->PO_ID = $dataPO->ID;
            $this->DATE = $this->dateServices->NowDate();
            $this->LOCATION_ID = $dataPO->LOCATION_ID;
            $this->showModal = true;
        }

    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function save() {

            

        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
    public function render()
    {
        return view('livewire.purchase-order.make-bill');
    }
}
